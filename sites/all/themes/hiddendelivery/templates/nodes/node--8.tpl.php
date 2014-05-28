<!-- NODE -->
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
	<div class="content"<?php print $content_attributes; ?>>
		<?php print render($content['body']); ?>
	</div>
	<div class="col-md-8">
		<?php
		print $webform;
		?>
	</div>
	<?php print render($content['links']); ?>
	<?php print render($content['comments']); ?>
</div>
