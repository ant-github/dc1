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
<div class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php print render($content['field_product']); ?>

  <ul class="item-code-steps">
    <li class="step step-1"><?php print t('Copy the item code below'); ?></li>
    <li class="step step-2"><?php print t("Click on the 'Open Site' button below and follow the link to the products site"); ?></li>
    <li class="step step-3"><?php print t('Enter the item code at checkout'); ?></li>
  </ul>

  <?php print render($reserve_button); ?>
  <?php print render($content['field_item_code']); ?>

  <a href="<?php print $product_url; ?>" data-itemcode="<?php print ($content['field_item_code']['#items'][0]['value']); ?>" class="btn btn-primary bg-sprite bg-sprite-boxes itemcode" target="_blank"><?php print t('Open Site'); ?></a>
</div>
