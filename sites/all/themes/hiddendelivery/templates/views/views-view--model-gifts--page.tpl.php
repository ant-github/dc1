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
<!--Redeem your gift first pop up-->
<?php
global $base_url;
global $user;

$user_details = user_load($user->uid);
if(isset($_GET['gift_id'])){
   $nid = $_GET['gift_id'];
   $node = node_load($nid);
if($user->uid == $node->field_model_id['und'][0]['value']){   
//   print "<pre>"; print_r($node); die();
    $amount_in_dollar = '';
    $amount_transfer_bank = '';
    $usd_amount_transfer_bank = '';
    
   $currency = $node->field_gift_currency['und'][0]['value'];
   if($currency == 'usd'){
       $currency_symbol = '$';
//       $amount_in_dollar = '';
       $amount_transfer_bank = number_format(($node->field_gift_amount_transfer['und'][0]['value']), 2);
//       $usd_amount_transfer_bank = '';       
       $amount = number_format(($node->field_gift_amount['und'][0]['value']), 2);
   }elseif($currency == 'gbp'){
       $currency_symbol = '£';
//       $amount_in_dollar = '($'.number_format(($node->field_gift_amount_in_dollar['und'][0]['value']), 2).')';
       $amount_transfer_bank = number_format(($node->field_gift_amount_transfer['und'][0]['value']), 2);
//       $usd_amount_transfer_bank = '($'.number_format(($node->field_usd_gift_amount_transfer['und'][0]['value']), 2).')'; 
       $amount = number_format(($node->field_gift_amount['und'][0]['value']), 2);
   }elseif($currency == 'eur'){
       /******* showing currency symbol as gbp(£), because stripe returns "EUR" transactions in "GBP" **********/
      
       if((isset($node->field_gross_amount['und'][0]['value']) && $node->field_gross_amount['und'][0]['value'] != '') && (isset($node->field_gross_amount_currency['und'][0]['value']) && $node->field_gross_amount_currency['und'][0]['value'] == 'gbp')){
            $currency_symbol = '£';

            /******* Gross amount is showing in place of gift amount, because gift amount is in "EUR", but stripe accept it in "GBP" and stored in gross amount field ***************/
            $amount = $node->field_gross_amount['und'][0]['value'];
            $amount_transfer_bank = number_format(($node->field_gift_amount_transfer['und'][0]['value']), 2);
       }else{
        
            $currency_symbol = '€';
            $amount = number_format(($node->field_gift_amount['und'][0]['value']), 2);

            $amount_in_dollar = '($'.number_format(($node->field_gift_amount_in_dollar['und'][0]['value']), 2).')';
            $amount_transfer_bank = number_format(($node->field_gift_amount_transfer['und'][0]['value']), 2);
            $usd_amount_transfer_bank = '($'.number_format(($node->field_usd_gift_amount_transfer['und'][0]['value']), 2).')';
        
       }
   }
   
   if(isset($node->field_sender_email['und'][0]['value']) && $node->field_sender_email['und'][0]['value'] != ''){ 
        $sender_email = $node->field_sender_email['und'][0]['value'];
   }else{
       $sender_email = 'Anonymous';
   }
   if(isset($node->field_gift_message['und'][0]['value']) && $node->field_gift_message['und'][0]['value'] != ''){
        $sender_msg = $node->field_gift_message['und'][0]['value'];
   }else{
       $sender_msg = 'No message';
   }
?>
<div class="redeem-gift-first-section redeem-gift-received">
        <div class="redeem-gift-first-section-header">
            <span class="redeem-gift-first-section-close-button"><a href="<?php echo $base_url;?>/user/gifts">x</a></span>
            <span class="redeem-gift-first-section-text">You've </span>
            <span class="redeem-gift-first-section-text-bold">received a gift!</span>
        </div>
        <div class="redeem-gift-first-section-body">   
            <div class="first-section-inner-text">
                <div class="redeem-gift-amount">
                    <div class="heading"><span>Amount</span></div>
                    <div class="amount"><span class="amount-num"><?php echo $currency_symbol.$amount.' '.$amount_in_dollar;?></span></div>
                </div>
                <div class="redeem-gift-sender-email">
                    <div class="heading"><span>Email</span></div>
                    <div class="sender-email"><?php echo $sender_email;?></div>
                </div>
                <div class="redeem-gift-sender-message">
                    <div class="heading"><span>Message</span></div>
                    <div class="sender-message"><?php echo $sender_msg;?></div>
                </div>
            </div>
        </div>
</div>
<!--Redeem your gift second pop up-->
<div class="redeem-gift-second-section redeem-gift-received" style="display: none;">
        <div class="redeem-gift-second-section-header">
            <span class="redeem-gift-second-section-close-button"><a href="<?php echo $base_url;?>/user/gifts">x</a></span>
            <span class="redeem-gift-second-section-text">How would you like to </span>
            <span class="redeem-gift-second-section-text-bold">receive your gift?</span>
        </div>
        <div class="redeem-gift-second-section-body account-opt-sec">   
            <div class="second-section-inner-text">
            <div class="col-sm-6 border-r">
             <div class="row">
                <div class="receive-in-bank-account">
                    <div class="heading"><span>Bank Account</span></div>
                    <div class="bank-account-icon"><img src="<?php echo $base_url;?>/sites/all/themes/hiddendelivery/images/icon-bankaccount.png" /></div>
                    <div class="amount">Withdraw <strong><?php echo $currency_symbol.$amount_transfer_bank.' '.$usd_amount_transfer_bank;?></strong> into your <br /> bank account now</div>
                    <?php
                        /* get stripe coutries list to transfer ammount from user's field_stripe_account_country */
                    $stripe_country_list = field_info_field('field_stripe_account_country');
                    $stripe_country_list_options = list_allowed_values($stripe_country_list);    
                    if (array_key_exists($user_details->field_delivery_address['und'][0]['country'], $stripe_country_list_options)) {                     
                    ?>
                    <?php if($node->field_model_redeem['und'][0]['value']==0) { ?>
                    <div class="send-to-bank-account-now"><a href="<?php echo $base_url;?>/dc_gift_payment/receive_cash_bank_account/<?php echo $nid;?>">Withdraw</a></div>
                    <?php }?>
                    <?php
                    }else{
                    ?>
                    <div class="send-to-bank-account-now"><p>This option is currently not available for your country.</p></div>
                    <?php
                    }
                    ?>
                </div>
             </div>
            </div>
                <div class="col-sm-6">
                <div class="row">
                <div class="receive-as-gift-voucher">
                    <div class="heading"><span>Gift Voucher</span></div>
                    <div class="gift-voucher-icon"><img src="<?php echo $base_url;?>/sites/all/themes/hiddendelivery/images/account-vochure.png" /></div>
                    <div class="amount">Add <strong><?php echo $currency_symbol.$amount;?></strong> into your gift <br /> voucher balance</div>
                    <?php if($node->field_model_redeem['und'][0]['value']==0) { ?>
                    <div class="send-to-gift-voucher-balance"><a href="<?php echo $base_url;?>/dc_gift_payment/receive_cash_gift_voucher/<?php echo $nid;?>">Add</a></div>
                    <?php }?>
                </div> 
                </div>
                </div>
            </div>            
        </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {     
    jQuery('.send-to-bank-account-now a').click(function(){
        jQuery('.send-to-bank-account-now a').css('display', 'none');
    })
    jQuery('.send-to-gift-voucher-balance a').click(function(){
        jQuery('.send-to-gift-voucher-balance a').css('display', 'none');
    })
});
</script>
<div class="<?php print $classes; ?>" style="display: none;">
  <?php print render($title_prefix); ?>
  <?php if ($title): ?>
    <?php print $title; ?>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
  <?php if ($header): ?>
    <div class="view-header">
      <?php print $header; ?>
    </div>
  <?php endif; ?>

  <?php if ($exposed): ?>
    <div class="view-filters">
      <?php print $exposed; ?>
    </div>
  <?php endif; ?>

  <?php if ($attachment_before): ?>
    <div class="attachment attachment-before">
      <?php print $attachment_before; ?>
    </div>
  <?php endif; ?>

  <?php if ($rows): ?>
    <div class="view-content">
      <?php print $rows; ?>
    </div>
  <?php elseif ($empty): ?>
    <div class="view-empty">
      <?php print $empty; ?>
    </div>
  <?php endif; ?>

  <?php if ($pager): ?>
    <?php print $pager; ?>
  <?php endif; ?>

  <?php if ($attachment_after): ?>
    <div class="attachment attachment-after">
      <?php print $attachment_after; ?>
    </div>
  <?php endif; ?>

  <?php if ($more): ?>
    <?php print $more; ?>
  <?php endif; ?>

  <?php if ($footer): ?>
    <div class="view-footer">
      <?php print $footer; ?>
    </div>
  <?php endif; ?>

  <?php if ($feed_icon): ?>
    <div class="feed-icon">
      <?php print $feed_icon; ?>
    </div>
  <?php endif; ?>

</div><?php /* class view */ ?>
<?php
}
}else{
 /***** unhold the model gifts after 24 hours of the received time****/   
$hold_nodes = '';
$select_hold_gifts = db_query("SELECT n.nid, n.created FROM field_data_field_hold_gift AS h LEFT JOIN field_data_field_model_id AS mid ON mid.entity_id = h.entity_id LEFT JOIN node AS n ON n.nid = mid.entity_id WHERE mid.bundle='model_gifts' AND mid.field_model_id_value = ".$user->uid." AND h.field_hold_gift_value ='yes'"); 
foreach($select_hold_gifts AS $res_hold_gifts){
    $hold_nodes = $res_hold_gifts->nid;
    $hold_nodes_time = $res_hold_gifts->created;
    $current_date = date("Y-m-d H:i", strtotime('-24 hours'));
    $before_24_hours_strtotime = strtotime($current_date);
    if($hold_nodes != ''){
        if($hold_nodes_time <= $before_24_hours_strtotime){
            $node = node_load($hold_nodes);
            $node->field_hold_gift['und'][0]['value'] = '';
            node_save($node);           
        }
    }
}
    
$processed_nodes = '';
$select_processed_gifts_not_completed = db_query("SELECT r.entity_id FROM field_data_field_model_redeem AS r LEFT JOIN field_data_field_model_id AS mid ON mid.entity_id = r.entity_id WHERE mid.bundle='model_gifts' AND mid.field_model_id_value = ".$user->uid." AND r.field_model_redeem_value =2"); 
foreach($select_processed_gifts_not_completed AS $res_processed_gifts_not_completed){
    $processed_nodes = $res_processed_gifts_not_completed->entity_id;
    if($processed_nodes != ''){
        $node = node_load($processed_nodes);
        $node->field_model_redeem['und'][0]['value'] = 0;
        node_save($node);
    }
}
?>
<div class="<?php print $classes; ?>">
  <?php print render($title_prefix); ?>
  <?php if ($title): ?>
    <?php print $title; ?>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
  <?php if ($header): ?>
    <div class="view-header">
      <?php print $header; ?>
    </div>
  <?php endif; ?>

  <?php if ($exposed): ?>
    <div class="view-filters">
      <?php print $exposed; ?>
    </div>
  <?php endif; ?>

  <?php if ($attachment_before): ?>
    <div class="attachment attachment-before">
      <?php print $attachment_before; ?>
    </div>
  <?php endif; ?>

  <?php if ($rows): ?>
    <div class="view-content">
      <?php print $rows; ?>
    </div>
  <?php elseif ($empty): ?>
    <div class="view-empty">
      <?php print $empty; ?>
    </div>
  <?php endif; ?>

  <?php if ($pager): ?>
    <?php print $pager; ?>
  <?php endif; ?>

  <?php if ($attachment_after): ?>
    <div class="attachment attachment-after">
      <?php print $attachment_after; ?>
    </div>
  <?php endif; ?>

  <?php if ($more): ?>
    <?php print $more; ?>
  <?php endif; ?>

  <?php if ($footer): ?>
    <div class="view-footer">
      <?php print $footer; ?>
    </div>
  <?php endif; ?>

  <?php if ($feed_icon): ?>
    <div class="feed-icon">
      <?php print $feed_icon; ?>
    </div>
  <?php endif; ?>

</div><?php /* class view */ ?>
<?php } ?>
