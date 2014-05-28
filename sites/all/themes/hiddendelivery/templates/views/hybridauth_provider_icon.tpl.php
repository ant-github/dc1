<?php 
	switch ($provider_name) {
		case "Facebook":
			print_r('<span class="btn-register-ico bg-sprite bg-sprite-ico-facebook pull-left"></span><span class="facebook btn-social-link" title="' . $provider_name . '">Sign in with Facebook</span>');
			break;
		case "Twitter":
			print_r('<span class="btn-register-ico bg-sprite bg-sprite-ico-twitter pull-left"></span><span class="twitter btn-social-link" title="' . $provider_name . '">Sign in with Twitter</span>');
			break;
		default:
			print_r('<span class="btn-register btn-social-link" title="' . $provider_name . '"></span>');
	}
?>