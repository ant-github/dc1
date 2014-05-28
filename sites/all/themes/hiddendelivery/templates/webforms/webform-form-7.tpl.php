<div class="row">
	<div class="col-md-12">
		<?php print drupal_render($form['submitted']['icon_title']); ?>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<?php print drupal_render($form['submitted']['name']); ?>
		<?php print drupal_render($form['submitted']['email']); ?>
	</div>
	<div class="col-md-6">
		<div class="social-links">
			<p class="social-links-title">Or contact us via social media: </p>
			<div class="social-icons">
				<a class="bg-sprite-circle-facebook bg-sprite block-sprite-fixed" href="https://www.facebook.com" target="_blank">Share on Facebook</a>
				<a class="bg-sprite-circle-twitter bg-sprite block-sprite-fixed" href="http://twitter.com/" rel="nofollow" title="Tweet This">Tweet Widget</a>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?php print drupal_render($form['submitted']['message']); ?>
		<?php print drupal_render($form['actions']['submit']); ?>
	</div>
</div>