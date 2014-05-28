<?php /* Node */ if ($view_mode == 'full') : ?>
  <div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <?php print render($title_prefix); ?>
    <h2<?php print $title_attributes; ?>><?php print $title; ?></h2>
  <?php print render($title_suffix); ?>
    <div class="submitted">
      <?php print $submitted; ?>
    </div>

  <div class="content"<?php print $content_attributes; ?>>
       <?php print render($content['field_news_image']); ?>
       <?php print render($content['body']); ?>
  </div>

  <?php print render($content['links']); ?>
  <?php print render($content['comments']); ?>

</div>
<?php endif ?>


<?php /* Teaser */  if ($view_mode == 'teaser') : ?>
  <div class="media">

    <div class="pull-left">
      <div class="media-object">
        <?php print render($content['field_news_image']); ?>
      </div>
    </div>

    <div class="media-body">
      <h2 class="media-heading"><a href="<?php print $node_url; ?>"><?php print render($title); ?></a></h2>
      <div class="submitted"><?php print render($submitted); ?></div>
      <?php print render($content['body']); ?>
    </div>

  </div>
<?php endif ?>
