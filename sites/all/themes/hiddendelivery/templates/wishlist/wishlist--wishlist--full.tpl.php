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
<div class="<?php print $classes; ?> clearfix row"<?php print $attributes; ?>>
  <div class="col-sm-3 account-pane">
    <?php print $account_pane; ?>
    <?php if ($is_owner): ?>
      <button type="button" class="btn btn-default btn-block"><span class="pull-left glyphicon glyphicon-pencil"></span><?php print $account_edit_link; ?></button>
    <?php endif; ?>
  </div>
  <div class="col-sm-9">
    <div class="wishlist-view">
      <?php print $wishlist_view; ?>
    </div>
  </div>
</div>
