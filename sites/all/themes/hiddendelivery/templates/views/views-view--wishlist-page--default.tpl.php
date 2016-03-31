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
global $base_url;
global $user;
$userWishlistId = arg(1);
if($user->uid == 0){
?>
        <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
        <!-- jQuery is used only for this example; it isn't required to use Stripe -->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script type="text/javascript">
            // this identifies your website in the createToken call below
            Stripe.setPublishableKey('pk_test_G8SCpA40VC7QPMiDdL5zlDcI');
//            Stripe.setPublishableKey('pk_test_4S8XotFis6j7VWvhD0nWT2Jd');
            function stripeResponseHandler(status, response) {
                if (response.error) {
                    // re-enable the submit button
                    $('.submit-button').removeAttr("disabled");
                    // show the errors on the form
                    $(".payment-errors").css("display", "block");
                    $(".payment-errors").html(response.error.message);
                } else {
                    var form$ = $("#payment-form");
                    // token contains id, last4, and card type
                    var token = response['id'];
                    // insert the token into the form so it gets submitted to the server
                    form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                    // and submit
                    form$.get(0).submit();
                }
            }
            $(document).ready(function() {
                $("#payment-form").submit(function(event) {
                    var user_name = $('#payment-form .user_name').val();
                    var user_email = $('#payment-form .user_email').val();
                    var address_country = $('#payment-form .address_country').val();
                    var address_state = $('#payment-form .address_state').val();
                    var address_zip = $('#payment-form .address_zip').val();
                    var address_line1 = $('#payment-form .address_line1').val();
                    var address_city = $('#payment-form .address_city').val();
                    var user_message = $('#payment-form .user_message').val();
                    var amount = $('#payment-form .payment-amount').val();
                    if(amount < 5){
                        $(".payment-errors").css("display", "block");
                        $(".payment-errors").html('Amount should be greater than 5.');
                        $('html,body').animate({'scrollTop' : 200},1000);
                        return false;                        
                    }
                    if(amount >= 1000){
                        $(".payment-errors").css("display", "block");
                        $(".payment-errors").html('Amount should be less than 1000.');
                        $('html,body').animate({'scrollTop' : 200},1000);
                        return false;                        
                    }
                    if(user_name == '' || user_email == '' || address_country == '' || address_state == '' || address_zip == '' || address_line1 == '' || address_city == '' || user_message == ''){
                        $(".payment-errors").css("display", "block");
                        $(".payment-errors").html('All fields are required. Please enter the required information.');
                        $('html,body').animate({'scrollTop' : 200},1000);
                        return false;
                    }
                    // disable the submit button to prevent repeated clicks
                    $('.submit-button').attr("disabled", "disabled");
                    // createToken returns immediately - the supplied callback submits the form if there are no errors
                    Stripe.createToken({
                        number: $('.card_number').val(),
                        cvc: $('.card_cvc').val(),
                        exp_month: $('.card_expiry_month').val(),
                        exp_year: $('.card_expiry_year').val(),
                        name: $('.user_name').val(),
                        address_line1: $('.address_line1').val(),
                        address_city: $('.address_city').val(),
                        address_state: $('.address_state').val(),
                        address_zip: $('.address_zip').val(),
                        address_country: $('.address_country').val()
                    }, stripeResponseHandler);
                    return false; // submit from callback
                });
            });
        </script>
<?php
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
      <div class="row">
      <div class="view-filters col-md-7">
        <?php print $exposed; ?>
        <?php if ($is_owner): ?>
          <div class="manage-wishlist-display"><a href="<?php echo $base_url;?>/wishlist_manage/<?php echo arg(1);?>">Manage Wishlist Items Display</a></div>       	
        <?php endif; ?>
      </div>
      <div class="share-wishlist-container col-md-5">
        <?php if ($is_owner){ ?>
          <button class="col-md-12 col-sm-12 white-button btn btn-primary btn-lg wishlistShare" data-toggle="modal" data-target="#<?php print $share_links_popup_id ;?>">
            <span class="bg-sprite bg-sprite-circle-star2 share-wishlist"></span><?php print t('Share Your Wishlist Online'); ?>
          </button>
      <?php print $add_an_item_to_wishlist; ?>
      <?php print $add_an_item_to_wishlist_popup; ?>
      <?php print $add_items_from_amazon_wishlist; ?>
      <?php print $add_items_from_amazon_wishlist_popup; ?>
          <?php print render($share_links_popup); ?>
        <?php }else{
//            print $send_a_gift_voucher;  
            if ($user->uid==0):
            print $send_a_gift;
            endif;
            
        } ?>  
          
      </div>
    </div>
    </div>
  <?php endif; ?>

  <?php if ($exposed): ?>
  <?php endif; ?>

  <?php if ($attachment_before): ?>
    <div class="attachment attachment-before">
      <?php print $attachment_before; ?>
    </div>
  <?php endif; ?>
    <?php
       
       $getUserId = db_query("SELECT uid FROM wishlist WHERE wishlist_id ='".$userWishlistId."'");
       foreach($getUserId AS $wishlistUser){
           $userId = $wishlistUser->uid;		   
       }
       $wishlistUserDetails = user_load($userId);
    ?>
    <div class="send-a-gift-section" style="display: none">
        <div class="send-a-gift-header">
            <span class="send-a-gift-close-button">x</span>
            <span class="gift-header-text">Send a gift to </span>
            <span class="gift-header-text-bold"><?php echo ucfirst($wishlistUserDetails->name);?></span>
        </div>
        <div class="send-a-gift-section-body">   
            <span class="payment-errors"></span>
            <form action="<?php echo $base_url;?>/dc_gift_payment/payment_status/<?php echo $userWishlistId;?>" method="POST" id="payment-form">
                <div class="col-sm-12">
                     <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-1">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-btn" style="width: 7%;">
                          <select class="form-control" name="currency_code">
                            <option selected="selected"value="usd">$</option>
                            <option value="gbp">£</option>
                            <option value="eur">€</option>
                          </select>
                        </div>
                        <div class="select-value">
                        <input class="form-control payment-amount" name="amount" type="text" placeholder="0">
                        </div>
                      </div>
                    </div>
                </div>
                 </div>
                </div>
                
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 send-gift-form">
                <div class="col-sm-12">
                     <div class="row">
                    <div class="col-sm-6">
                        <input type="text" value=""  placeholder="Debit Card Number" class="card_number"/>
                    </div>
                    <div class="col-sm-6">
                     <div class="row">
                    <div class="col-sm-6">
                        <input type="text" value=""  placeholder="MM" class="card_expiry_month"/>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" value=""  placeholder="YY" class="card_expiry_year"/>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    
                     <div class="col-sm-12">
                     <div class="row">
                    <div class="col-sm-6">
                        <input type="text" value="" name="card_cvc" placeholder="CVV" class="card_cvc"/>
                    </div>            
                    <div class="col-sm-6">
                        <input type="text" value="" name="user_name" placeholder="Name" class="user_name"/>
                    </div>
                    </div>
                    </div>  
                    
                    <div class="col-sm-12">
                     <div class="row">          
                    <div class="col-sm-6">
                        <input type="text" value="" name="user_email" placeholder="Email" class="user_email"/>
                    </div>            
                    <div class="col-sm-6">
                        <input type="text" value="" name="address_line1" placeholder="Address" class="address_line1"/>
                    </div>
                    </div>
                    </div>    
                    
                       <div class="col-sm-12">
                     <div class="row">          
                    <div class="col-sm-6">
                        <input type="text" value="" name="address_country" placeholder="Country" class="address_country"/>
                    </div>            
                    <div class="col-sm-6">
                        <input type="text" value="" name="address_state" placeholder="State" class="address_state"/>
                    </div> 
                    </div>
                    </div>    
                        
                        <div class="col-sm-12">
                     <div class="row">            
                    <div class="col-sm-6">
                        <input type="text" value="" name="address_city" placeholder="City" class="address_city"/>
                    </div>            
                    <div class="col-sm-6">
                        <input type="text" value="" name="address_zip" placeholder="Postal Code/Zip" class="address_zip"/>
                    </div> 
                    </div>
                    </div> 
                              
                    <div class="col-sm-12">
                        <input type="text" value="" name="user_message" placeholder="Your Message" class="user_message"/>
                    </div>            
                    <div class="col-sm-12 btn-sendCash">
                        <button type="submit" class="submit-button">Send Gift</button>
                    </div>            
                </div>
            </form>
        </div>
    </div>    
  <?php if ($rows): ?>
    <div class="view-content">
      <?php print $rows; ?>
    </div>
  <?php elseif ($empty): ?>
    <div class="view-empty">
      <div class="alert alert-warning empty-wishlist">
        <?php if($is_owner): ?>
        <h3 class="empty-wishlist-header">There are no products in your wishlist</h3>
      <?php else:?>
        <?php print $empty; ?>
      <?php endif;?>
      </div>
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
  
  <?php if ($is_owner):
  		if ($footer): 
  ?>
    <div class="view-footer">
      <?php print $footer; ?>
    </div>
  <?php 
  		endif; 
  		endif;
  ?>

  <?php if ($feed_icon): ?>
    <div class="feed-icon">
      <?php print $feed_icon; ?>
    </div>
  <?php endif; ?>

</div><?php /* class view */ ?>
