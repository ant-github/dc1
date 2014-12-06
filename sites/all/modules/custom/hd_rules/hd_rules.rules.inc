<?php
/**
 * Implements hook_rules_condition_info()
 * Compare wishlist owner address to store address to implement the right
 * shipping service
 */

function hd_rules_rules_condition_info(){
  $conditions = $array = array(
    'hd_rules_check_wishlist_owner_address'=>array(
      'label' => t('Compare wishlist owner country address'),
      'group' => t('hd rules'),
      'parameter'=>array(
        'commerce_order' => array(
            'type' => 'commerce_order',
            'label' => t('Order')
        ),
      ),
     ),
    );
  return $conditions;
}

function hd_rules_check_wishlist_owner_address($commerce_order){
  //load line_tem from the order
  $line_item = commerce_line_item_load(
    $commerce_order->commerce_line_items[LANGUAGE_NONE]['0']['line_item_id']);
  //check if line item is a product
  if($line_item->type == 'product'){
    //load wishlist_owner
    $wish_list_owner = user_load(
      $line_item->field_wish_list_owner_ref[LANGUAGE_NONE]['0']['target_id']);
    //get wishlist_owner country
    $wish_list_owner_country =
      $wish_list_owner->field_delivery_address[LANGUAGE_NONE]['0']['country'];

    //load store country
    $store = entity_load_single('store',
    $line_item->field_store_country_ref[LANGUAGE_NONE]['0']['target_id']);
    $store_country = $store->field_store_country[LANGUAGE_NONE]['0']['value'];

    if($wish_list_owner_country == $store_country){
      return TRUE;;
    }
  }
}

/**
 * Implements hook_rules_action_info()
 * Compare wishlist owner address to store address to implement the right
 * shipping service
 * create purchase entity ans set wishlist item to purchased
 */

function hd_rules_rules_action_info(){
  $actions = $array = array(
    'hd_rules_create_purchase'=>array(
      'label' => t('Create purchase entity and set wishlistItem to Purchased'),
      'group' => t('hd rules'),
      'parameter'=>array(
        'commerce_order' => array(
            'type' => 'commerce_order',
            'label' => t('Order')
        ),
      ),
     ),
    'hd_rules_set_item_to_reserved'=>array(
      'label' => t('Set wishlist item to reserved'),
      'group' => t('hd rules'),
      'parameter'=>array(
        'commerce_order' => array(
            'type' => 'commerce_order',
            'label' => t('Order')
        ),
      ),
     ),
    );
  return $actions;
}

function hd_rules_create_purchase($commerce_order){
  //load line_tem from the order
  $line_item = commerce_line_item_load(
    $commerce_order->commerce_line_items[LANGUAGE_NONE]['0']['line_item_id']);
  //check if line item is a product
  if($line_item->type == 'product'){
    // Update the wishlist item status to purchased
    $wishlist_item = hd_wishlist_item_load(
      $line_item->field_wishlist_item_id_ref['und'][0]['target_id']);
    $wr = entity_metadata_wrapper('wishlist_item', $wishlist_item);
    $wr->field_reserving_user = $commerce_order->uid;
    $wr->field_status = 'purchased';
    $wr->save();

    // Create a purchase entity for the wishlist item.
    $purchase_entity = entity_get_controller('purchase')->create();
    $pw = entity_metadata_wrapper('purchase', $purchase_entity);
    $pw->user = $line_item->field_wish_list_owner_ref[LANGUAGE_NONE]['0']['target_id'];
    $pw->field_wishlist_item = $wishlist_item->wishlist_item_id;
    $pw->field_purchasing_user = $commerce_order->uid;
    $pw->save();
  }
}

function hd_rules_set_item_to_reserved($commerce_order){
  //load line_tem from the order
  $line_item = commerce_line_item_load(
    $commerce_order->commerce_line_items[LANGUAGE_NONE]['0']['line_item_id']);
  //check if line item is a product
  if($line_item->type == 'product'){
    // Update the wishlist item status to purchased
    $wishlist_item = hd_wishlist_item_load(
      $line_item->field_wishlist_item_id_ref['und'][0]['target_id']);
    $wr = entity_metadata_wrapper('wishlist_item', $wishlist_item);
    $wr->field_reserving_user = $commerce_order->uid;
    $wr->field_status = 'reserved';
    $wr->save();
  }
}
