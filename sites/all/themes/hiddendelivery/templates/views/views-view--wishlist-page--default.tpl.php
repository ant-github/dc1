<?php

/**
 * @file
 * Main view template.
 *
 * Variables available:
 * - $classes_array: An array of classes determined in
 *   template_preprocess_views_view(). Default classes are:
 *     .view
 *     .view-[css_name]
 *     .view-id-[view_name]
 *     .view-display-id-[display_name]
 *     .view-dom-id-[dom_id]
 * - $classes: A string version of $classes_array for use in the class attribute
 * - $css_name: A css-safe version of the view name.
 * - $css_class: The user-specified classes names, if any
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposed: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 *
 * @ingroup views_templates
 */
global $base_url;
?>
<div class="<?php print $classes; ?>">
  <?php print render($title_prefix); ?>
  <?php if ($title): ?>
    <?php print $title; ?>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
  <?php if ($header): ?>
    <div class="view-header">
      <div class="row">
      <div class="view-filters col-md-7">
        <?php print $exposed; ?>
        <?php if ($is_owner): ?>
          <div class="manage-wishlist-display"><a href="<?php echo $base_url;?>/wishlist_manage/<?php echo arg(1);?>">Manage Wishlist Items Display</a></div>       	
        <?php endif; ?>
      </div>
      <div class="share-wishlist-container col-md-5">
        <?php if ($is_owner){ ?>
          <button class="col-md-12 col-sm-12 white-button btn btn-primary btn-lg wishlistShare" data-toggle="modal" data-target="#<?php print $share_links_popup_id ;?>">
            <span class="bg-sprite bg-sprite-circle-star2 share-wishlist"></span><?php print t('Share Your Wishlist Online'); ?>
          </button>
      <?php print $add_an_item_to_wishlist; ?>
      <?php print $add_an_item_to_wishlist_popup; ?>
      <?php print $add_items_from_amazon_wishlist; ?>
      <?php print $add_items_from_amazon_wishlist_popup; ?>
          <?php print render($share_links_popup); ?>
        <?php }else{ print $send_a_gift_voucher;  } ?>  
          
      </div>
    </div>
    </div>
  <?php endif; ?>

  <?php if ($exposed): ?>
  <?php endif; ?>

  <?php if ($attachment_before): ?>
    <div class="attachment attachment-before">
      <?php print $attachment_before; ?>
    </div>
  <?php endif; ?>

  <?php if ($rows): ?>
    <div class="view-content">
      <?php print $rows; ?>
    </div>
  <?php elseif ($empty): ?>
    <div class="view-empty">
      <div class="alert alert-warning empty-wishlist">
        <?php if($is_owner): ?>
        <h3 class="empty-wishlist-header">There are no products in your wishlist</h3>
      <?php else:?>
        <?php print $empty; ?>
      <?php endif;?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($pager): ?>
    <?php print $pager; ?>
  <?php endif; ?>

  <?php if ($attachment_after): ?>
    <div class="attachment attachment-after">
      <?php print $attachment_after; ?>
    </div>
  <?php endif; ?>

  <?php if ($more): ?>
    <?php print $more; ?>
  <?php endif; ?>
  
  <?php if ($is_owner):
  		if ($footer): 
  ?>
    <div class="view-footer">
      <?php print $footer; ?>
    </div>
  <?php 
  		endif; 
  		endif;
  ?>

  <?php if ($feed_icon): ?>
    <div class="feed-icon">
      <?php print $feed_icon; ?>
    </div>
  <?php endif; ?>

</div><?php /* class view */ ?>
