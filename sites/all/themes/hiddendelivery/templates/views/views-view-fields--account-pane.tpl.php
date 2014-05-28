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
?>
<div>
	<?php print $fields['field_name']->wrapper_prefix; ?>
	<?php print $fields['field_name']->label_html; ?>
	<?php print $fields['field_name']->content; ?>
	<?php print $fields['field_name']->wrapper_suffix; ?>
	<?php print $fields['picture']->wrapper_prefix; ?>
	<?php print $fields['picture']->label_html; ?>
	<?php print $fields['picture']->content; ?>
	<?php print $fields['picture']->wrapper_suffix; ?>
	<?php print $fields['field_profile_description']->wrapper_prefix; ?>
	<?php print $fields['field_profile_description']->label_html; ?>
	<?php print $fields['field_profile_description']->content; ?>
	<?php print $fields['field_profile_description']->wrapper_suffix; ?>
	<div class="profile-sizes">
		<?php if (($fields['field_gender']->content) == '<div>Male</div>') {
			print $fields['field_shirt_size']->wrapper_prefix;
			print $fields['field_shirt_size']->label_html;
			print $fields['field_shirt_size']->content;
			print $fields['field_shirt_size']->wrapper_suffix;
			print $fields['field_waist_size']->wrapper_prefix;
			print $fields['field_waist_size']->label_html;
			print $fields['field_waist_size']->content;
			print $fields['field_waist_size']->wrapper_suffix;
			print $fields['field_shoe_size']->wrapper_prefix;
			print $fields['field_shoe_size']->label_html;
			print $fields['field_shoe_size']->content;
			print $fields['field_shoe_size']->wrapper_suffix;
		}
		else {
			print $fields['field_dress_size']->wrapper_prefix;
			print $fields['field_dress_size']->label_html;
			print $fields['field_dress_size']->content;
			print $fields['field_dress_size']->wrapper_suffix;
			print $fields['field_bra_size']->wrapper_prefix;
			print $fields['field_bra_size']->label_html;
			print $fields['field_bra_size']->content;
			print $fields['field_bra_size']->wrapper_suffix;
			print $fields['field_shirt_size']->wrapper_prefix;
			print $fields['field_shirt_size']->label_html;
			print $fields['field_shirt_size']->content;
			print $fields['field_shirt_size']->wrapper_suffix;
		}
		?>
	</div>
</div>