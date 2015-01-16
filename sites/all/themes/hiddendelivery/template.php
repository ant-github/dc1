<?php

/**
 * @file
 * template.php
 */

function hiddendelivery_preprocess(&$vars, $hook) {
  //dpm($hook);
}

function hiddendelivery_preprocess_page(&$vars) {
  //upon client request hide breadcrumb
if(isset($vars['breadcrumb'])){
  $vars['breadcrumb'] = "";
}
  if (!user_is_anonymous()) {
    $user = $vars['user'];
    $account_link = l(t('You are logged in as @username', array('@username' => $user->name)), 'user');
    $logout_link = l(t('Logout'), 'user/logout', array('attributes' => array('class' => array('logout-link'))));
    $vars['user_info_panel'] = $account_link . ' / ' . $logout_link;
  }
  else {
    $vars['user_info_panel'] = FALSE;
  }

}

function hiddendelivery_preprocess_entity(&$vars) {
  global $user;
  $wid = arg(1);
  // Wishlist.
  if ($vars['entity_type'] == 'wishlist') {
    if ($vars['view_mode'] == 'full') {
      // Add a view of wishlist items to the wishlist template.
      $vars['wishlist_view'] = views_embed_view('wishlist_page', 'default', $vars['wishlist']->wishlist_id);
      $vars['account_pane'] = views_embed_view('account_pane', 'default', $vars['wishlist']->uid);
      if ($user->uid == $vars['wishlist']->uid || user_access('Administer users')) {
        $account_edit_link_markup = '<span class="pull-left glyphicon glyphicon-pencil"></span>';
        $vars['account_edit_link'] = l($account_edit_link_markup . t('Edit Profile'), "user/{$user->uid}/edit", array(
          'html' => TRUE,
          'attributes' => array(
            'class' => array('btn', 'btn-default')
          )
        ));
        $vars['is_owner'] = TRUE;
      }
      else {
        $vars['is_owner'] = FALSE;
      }
    }
  }

  // Product.
  if ($vars['entity_type'] == 'product') {
    if ($vars['view_mode'] == 'wishlist') {
      // Add the store logo to the product template.
      $pw = entity_metadata_wrapper('product', $vars['product']);
      $store_logo = $pw->field_store->field_store_logo->value();
      $vars['store_logo'] = theme('image_style',array('style_name' => 'thumbnail', 'path' => $store_logo['uri']));
    }
  }

  // Wishlist Item.
  if ($vars['entity_type'] == 'wishlist_item') {
    // Add the entity wrapper for all view modes to use.
    $wrapper = entity_metadata_wrapper('wishlist_item', $vars['wishlist_item']);
    if ($vars['view_mode'] == 'full') {
      $vars['reserve_button'] = FALSE;
        // Add the reserve item button.
        if (!user_is_anonymous()) {
          $reserving_user = $wrapper->field_reserving_user->value();
          // Show the reserve form if the item is not reserved or if the user is the reserving user.
          if ($reserving_user === NULL || (is_object($reserving_user) && $reserving_user->uid == $user->uid)) {
            $vars['reserve_button'] = drupal_get_form('hd_wishlist_item_reserve_form_' . $vars['wishlist_item']->wishlist_item_id, $vars['wishlist_item']->wishlist_item_id);
          }
        }
    }

    if ($vars['view_mode'] == 'wishlist') {
      //Render note field with a plain text format
      $vars['note'] = field_view_field('wishlist_item', $vars['wishlist_item'],
        'field_note', array('type' => 'text_default', ));
      $vars['purchase_info'] = FALSE;
      $vars['is_owner'] = $user->uid == $vars['wishlist_item']->uid ? TRUE : FALSE;
      // Calculate the status.
      switch ($wrapper->field_status->raw()) {
        case 'available':
          $vars['status'] = 'available';
          $vars['status_class'] = 'item-available';
          break;
        case 'reserved':
          $vars['status'] = 'reserved';
          $vars['status_class'] = 'item-reserved';
          break;
        case 'purchased':
          $vars['status'] = 'purchased';
          $vars['status_class'] = 'item-purchased';
          break;
      }
      // Add the remove button.
      if ($vars['is_owner']) {
        //Render note field with a jeditable format
        $vars['note'] = field_view_field('wishlist_item', $vars['wishlist_item'],
          'field_note', array('type' => 'hd_jeditable_textfield', ));

        $vars['remove_button'] = drupal_get_form('hd_wishlist_item_remove_form_' . $vars['wishlist_item']->wishlist_item_id, $vars['wishlist_item']->wishlist_item_id, arg(1));
        // Get the absolute URL to this wishlist item.
        //$full_url = url('wishlist/item/' . $vars['wishlist_item']->wishlist_item_id, array('absolute' => TRUE));
        $current_user = user_load($user->uid);
        $full_url =$current_user->field_profile_wishlist_url[LANGUAGE_NONE][0]['value'];
        // Facebook share link.
        $vars['share_links']['facebook'] = '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $full_url . '" class="bg-sprite-circle-facebook bg-sprite block-sprite-fixed pull-left" target="_blank">Share on Facebook</a>';

        // Twitter share link.
        $url = 'http://twitter.com/share';
        $options = array(
          'query' => array(
            'url' => $full_url,
            'count' => 'horizontal',
            'via' => 'delivery_code',
            'text' => $vars['title'] . ' is on my wishlist!',
            'counturl' => $full_url,
          ),
        );
        $twitter_url = url($url, $options);
        $vars['share_links']['twitter'] = '<a href="'. $twitter_url .'" class="bg-sprite-circle-twitter bg-sprite block-sprite-fixed" title="Tweet This" rel="nofollow">Tweet Widget</a>';
        $vars['share_links']['email'] = '<a href="mailto:?subject=' . $vars['title'] . ' is on my wishlist!&amp;body=' . $full_url .'" class="bg-sprite-circle-email bg-sprite block-sprite-fixed pull-right" title="Share by Email" target="_blank">Email Widget</a>';
        // Add purchaser information if the item has been purchased.
        if ($vars['status'] == 'purchased') {
          // Load purchase entity for this wishlist item.
          $purchase = hd_purchase_get_purchase_by_wishlist_item_id($vars['wishlist_item']->wishlist_item_id);
          if ($purchase) {
            $vars['purchase_info'] = hd_purchase_view($purchase, 'wishlist');
          }
        }
      }

      if (!$vars['is_owner']) {
        // Add the full display of a wishlist item to show in the buy popup.
        $wishlist_item_popup_id = 'wishlist-item-buy-' . $vars['wishlist_item']->wishlist_item_id;
        $modal_options = array(
          'attributes' => array('id' => $wishlist_item_popup_id, 'class' => array('wishlist-modal')),
          'heading' => t('Here is your item code:'),
          'body' => entity_view('wishlist_item', array(entity_id('wishlist_item', $vars['wishlist_item']) => $vars['wishlist_item']), 'full'),
        );
        $vars['wishlist_item_popup'] = theme('bootstrap_modal', $modal_options);
        $vars['wishlist_item_popup_id'] = $wishlist_item_popup_id;
      }
    }
  }
}

/**
 * Implements hook_preprocess_views_view().
 */
function hiddendelivery_preprocess_views_view(&$vars) {
// Wishlist page.
  if ($vars['view']->name == 'wishlist_page') {
    global $user;
    // Get the wishlist id.
    $wishlist_id = $vars['view']->args[0];
    $wishlist = hd_wishlist_load($wishlist_id);
    $vars['is_owner'] = FALSE;

    // If the user is the wishlist owner add the share links.
    if ($wishlist->uid == $user->uid) {
      $vars['is_owner'] = TRUE;
      $current_user = user_load($user->uid);
      //print_r($current_user);
      // Email share link.$current_user->field_profile_wishlist_url[LANGUAGE_NONE][0]['value'];
      //$wishlish_url = url('wishlist/' . $wishlist_id, array('absolute' => TRUE));
      $wishlish_url = $current_user->field_profile_wishlist_url[LANGUAGE_NONE][0]['value'];
      $share_link_email = '<a href="mailto:?subject=Take a look at my wishlist!&amp;body=' . $wishlish_url .'" class="share-links-email" title="Share by Email" target="_blank"><span class="element-invisible">Email Widget</span></a>';

      // Add the share links modal.
      $share_links = service_links_render_some(array('facebook_share', 'twitter_widget'));
      $share_links = array('share_links' => array(
        '#markup' => implode($share_links, ''),
      ));
      $wishlist_share_id = 'wishlist-share';
      $modal_options = array(
        'attributes' => array('id' => $wishlist_share_id),
        'heading' => t('Share Your Wishlist Online'),
        'body' => drupal_render($share_links) . $share_link_email,
      );
      $vars['share_links_popup'] = theme('bootstrap_modal', $modal_options);
      $vars['share_links_popup_id'] = $wishlist_share_id;

      // NEW Add a product url button
      $vars['add_an_item_to_wishlist'] = '<button class="col-md-12 col-sm-12 white-button btn btn-primary btn-lg" data-toggle="modal" data-target="#add-item-to-wishlist"><span class="glyphicon glyphicon-plus"></span> Add a Product </button>';

      // Add the full display of a wishlist item to show in the buy popup.
      $add_an_item_to_wishlist_markup_id = 'add-item-to-wishlist';
      $add_an_item_to_wishlist_form = drupal_get_form('hd_tweaks_add_a_product_url_form');
      $modal_options = array(
        'attributes' => array('id' => $add_an_item_to_wishlist_markup_id, 'class' => array('wishlist-modal')),
        'heading' => t('Add a product to your wishlist below:'),
        'body' => drupal_render($add_an_item_to_wishlist_form),
      );
      $vars['add_an_item_to_wishlist_popup'] = theme('bootstrap_modal', $modal_options);
      $vars['add_an_item_to_wishlist_popup_id'] = $add_an_item_to_wishlist_markup_id;
    }
  }
}
