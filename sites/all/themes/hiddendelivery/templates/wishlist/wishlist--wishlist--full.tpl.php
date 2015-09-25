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
<?php
global $base_url;
$wishlist_id = arg(1);
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
      <?php if ($is_owner): ?>
      <div id="widgetSelectBackground">
          <div id="widgetSelectBackgroundLabel">
            <h3>My Widgets</h3>
          </div>
          <div class="selectBgLable">Preferred Background Color:</div>
          <input type="radio" id="widgetWhite" name="widget-color" checked="checked"/><span class="color-text">White</span><input type="radio" id="widgetBlack" name="widget-color"/><span class="color-text">Black</span>
      </div>
          
      <div id="my-widgets-white" style="display: none;">
            <div class="my-widget-area">      
              <button class="my-widget-button col-md-12 col-sm-12 btn btn-primary btn-lg collapsed" data-toggle="collapse" data-target="#myWidgetHorizontal" aria-expanded="false" aria-controls="myWidgetHorizontal"><span class="my-widget-first">My widget </span><span class="my-widget-second">Horizontal</span></button>     
                    <div id="myWidgetHorizontal" class="collapse">
                        <textarea readonly="readonly"><iframe title="My widget" width="800" height="250" src="<?php echo $base_url;?>/my-widget-items/<?php echo $wishlist_id;?>" frameborder="0"></iframe></textarea>
                    </div>
            </div>
            <div class="my-widget-area"> 
              <button class="my-widget-button col-md-12 col-sm-12 btn btn-primary btn-lg collapsed" data-toggle="collapse" data-target="#myWidgetVertical" aria-expanded="false" aria-controls="myWidgetVertical"><span class="my-widget-first">My widget </span><span class="my-widget-second">Vertical</span></button> 
                    <div id="myWidgetVertical" class="collapse">
                        <textarea readonly="readonly"><iframe title="My new iframe" width="250" height="800" src="<?php echo $base_url;?>/my-widget-items-vertical/<?php echo $wishlist_id;?>" frameborder="0"></iframe></textarea>         
                    </div> 
            </div>
      </div>
      <div id="my-widgets-black" style="display: none">
            <div class="my-widget-area">      
              <button class="my-widget-button col-md-12 col-sm-12 btn btn-primary btn-lg collapsed" data-toggle="collapse" data-target="#myWidgetHorizontalBlack" aria-expanded="false" aria-controls="myWidgetHorizontalBlack"><span class="my-widget-first">My widget </span><span class="my-widget-second">Horizontal</span></button>     
                    <div id="myWidgetHorizontalBlack" class="collapse">
                        <textarea readonly="readonly"><iframe title="My widget" width="800" height="250" src="<?php echo $base_url;?>/my-widget-items-black/<?php echo $wishlist_id;?>" frameborder="0"></iframe></textarea>
                    </div>
            </div>
            <div class="my-widget-area"> 
              <button class="my-widget-button col-md-12 col-sm-12 btn btn-primary btn-lg collapsed" data-toggle="collapse" data-target="#myWidgetVerticalBlack" aria-expanded="false" aria-controls="myWidgetVerticalBlack"><span class="my-widget-first">My widget </span><span class="my-widget-second">Vertical</span></button> 
                    <div id="myWidgetVerticalBlack" class="collapse">
                        <textarea readonly="readonly"><iframe title="My new iframe" width="250" height="800" src="<?php echo $base_url;?>/my-widget-items-vertical-black/<?php echo $wishlist_id;?>" frameborder="0"></iframe></textarea>         
                    </div> 
            </div>
      </div>      
      <?php endif; ?>
  </div>
  <div class="col-sm-9">
    <div class="wishlist-view">
      <?php  print $wishlist_view; ?>
    </div>
    <div class="item-add-to-widget simple-dialog">
        <div class="closeDcWidgetModel"><span>X</span></div>
        <div class="product-info">
<!--            <p>Please wait your item is adding to widget...</p>-->
            <span><img src="<?php echo $base_url;?>/sites/all/themes/hiddendelivery/images/widgets-loader.gif"></span>
        </div>
    </div>
    <div class="item-add-to-widget-notice simple-dialog">
        <div class="closeDcWidgetModel"><span>X</span></div>
        <div class="product-info">
            <p>You have already added five items to your DeliveryCode widget. You can not add more than five.</p>
        </div>
    </div>      
    <div class="item-remove-to-widget simple-dialog">
        <div class="closeDcWidgetModel"><span>X</span></div>
        <div class="product-info">
<!--            <p>Please wait removing your item from widget...</p>-->
            <span><img src="<?php echo $base_url;?>/sites/all/themes/hiddendelivery/images/widgets-loader.gif"></span>
        </div>
    </div>      
  </div>
</div>
