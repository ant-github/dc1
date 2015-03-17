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
  <div class="col-sm-3">
  	<div class="account-pane">
    <?php print $account_pane; ?>
    <?php if ($is_owner): ?>
      <?php print $account_edit_link; ?>
    <?php endif; ?>
    </div>
    <?php if (module_exists('dc_vote')) {
    ?>	
    <div class="dc-vote-section">
    	<?php 
    	$user_ip = ip_address(); 
		$wishlist_id = arg(1);
		$vote_done = '';
    	$vote_status = db_query("SELECT n.nid FROM field_data_wishlist_id_vote AS w LEFT JOIN field_data_user_ip_vote AS v ON v.entity_id = w.entity_id LEFT JOIN node AS n on n.nid = w.entity_id WHERE v.user_ip_vote_value='".$user_ip."' AND w.wishlist_id_vote_value ='".$wishlist_id."'");
		foreach($vote_status AS $res_vote_status){
			$vote_done = $res_vote_status->nid;
		}
		if($vote_done != ''){
			$model_votes = db_query("SELECT count(entity_id) AS votes FROM field_data_wishlist_id_vote WHERE wishlist_id_vote_value='".$wishlist_id."'");
			foreach($model_votes AS $res_model_votes){
				 $total_votes = $res_model_votes->votes; 
			}
			if($total_votes == 1){
		?>
			<div class="vouchers-vote-header"><h3>Vouchers 4 <span class="vote-header">Votes</span></h3></div>
			<div class="total-vote">I have <span class="vote-me-text"><?php print $total_votes;?></span> vote.</div>
		<?php		
			}else{
		?>
			<div class="vouchers-vote-header"><h3>Vouchers 4 <span class="vote-header">Votes</span></h3></div>
			<div class="total-vote">I have <span class="vote-me-text"><?php print $total_votes;?></span> votes.</div>
		<?php		
			}
		?>
		<?php
		}else{
		?>
			<div class="vouchers-vote-header"><h3>Vouchers 4 <span class="vote-header">Votes</span></h3></div>
	    <?php 
	    	module_load_include('inc', 'node', 'node.pages');
			$dc_vote_form = node_add('dc_vote');
			print drupal_render($dc_vote_form);
		?>
		<?php	
		}			    
    	?>

    </div>
    <?php 
    }
    ?>
  </div>
  <div class="col-sm-9">
    <div class="wishlist-view">
      <?php print $wishlist_view; ?>
    </div>
  </div>
</div>
