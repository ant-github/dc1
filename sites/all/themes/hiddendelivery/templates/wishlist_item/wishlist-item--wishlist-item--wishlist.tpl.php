<?php

/**
 * @file
 * Default theme implementation for entities.
 *
 * Available variables:
 * - $content: An array of comment items. Use render($content) to print them all, or
 *   print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $title: The (sanitized) entity label.
 * - $url: Direct url of the current entity if specified.
 * - $page: Flag for the full page state.
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. By default the following classes are available, where
 *   the parts enclosed by {} are replaced by the appropriate values:
 *   - entity-{ENTITY_TYPE}
 *   - {ENTITY_TYPE}-{BUNDLE}
 *
 * Other variables:
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 *
 * @see template_preprocess()
 * @see template_preprocess_entity()
 * @see template_process()
 */
?>
<div class="<?php print $classes . ' ' . $status_class; ?> clearfix row"<?php print $attributes; ?>>
  <div class="product-info col-md-9">
    <?php if ($status == 'reserved'): ?>
      <div class="reserved-message"><p><?php print t('Product Reserved'); ?></p></div>
    <?php endif; ?>

    <?php if ($status == 'purchased'): ?>
      <div class="purchased-message"><p><?php print t('Product Purchased'); ?></p></div>
    <?php endif; ?>
     <?php print render($content['product:field_product_image']); ?>
     <?php print render($content['product:title']); ?>
     <?php print render($content['product:field_info']); ?>
     <?php print render($content['product:commerce_price']); ?>
     <?php print render($content['field_note']); ?>
  </div>

  <div class="actions col-md-3">
    <div class="actions col-md-10">
    <?php if (!$is_owner): ?>
        <?php print render($content['field_commerce_produc_ref']); ?>

      <?php print render($wishlist_item_popup); ?>
    <?php endif; ?>

    <?php if ($is_owner): ?>
      <div class="social-links">
        <?php print $share_links['facebook']; ?>
        <?php print $share_links['twitter']; ?>
        <?php print $share_links['email']; ?>
      </div>
      <?php print render($remove_button); ?>
    <?php endif; ?>
    </div>
    <div class="purchase-info">
        <?php print render($purchase_info); ?>
    </div>
  </div>

</div>
