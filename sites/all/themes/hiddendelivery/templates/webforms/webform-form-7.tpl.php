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
			<p class="social-links-title">Or contact us <br/>via social media: </p>
			<div class="social-icons">
				<a class="bg-sprite-circle-instagram inverted bg-sprite block-sprite-fixed" href="https://www.instagram.com/deliverycode" target="_blank">DeliveryCode on Instagram </a>
				<a class="bg-sprite-circle-facebook bg-sprite block-sprite-fixed" href="https://www.facebook.com/deliverycode" target="_blank">DeliveryCode on Facebook</a>
				<a class="bg-sprite-circle-twitter bg-sprite block-sprite-fixed" href="http://twitter.com/delivery_code" rel="nofollow">DeliveryCode on Twitter"></a>
				<a class="bg-sprite-circle-pinterest bg-sprite block-sprite-fixed" href="http://pinterest.com/deliverycode" rel="nofollow">DeliveryCode on Twitter"></a>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?php print drupal_render($form['submitted']['message']); ?>
		<?php print drupal_render_children($form); ?>
	</div>
</div>
