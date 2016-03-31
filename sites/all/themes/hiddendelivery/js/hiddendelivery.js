(function ($) {
Drupal.behaviors.globalScript = {
    attach: function (context, settings) {
/*
 * Payment form desgin changes and hide the review form
 */
if($('.page-checkout-review').length ){
         
    var order_total_value = $('.page-checkout-review #commerce-checkout-form-review #edit-checkout-review .view-commerce-cart-summary .view-footer .field-name-commerce-order-total .component-type-commerce-price-formatted-amount .component-total').html();
    $('.page-checkout-review #commerce-checkout-form-review #edit-commerce-payment .panel-heading .panel-title').html(order_total_value);
    $('.page-checkout-review #commerce-checkout-form-review #edit-buttons #edit-continue').html('Pay '+order_total_value);

    
    $('#commerce-checkout-form-review #edit-commerce-payment-payment-details-credit-card-owner').attr("placeholder", "Your name on card");
    $('#commerce-checkout-form-review #edit-commerce-payment-payment-details-credit-card-number').attr("placeholder", "Your card number *");
    $('#commerce-checkout-form-review #edit-commerce-payment-payment-details-credit-card-code').attr("placeholder", "Security code / CVC *");
    
//    $("#commerce-checkout-form-review #edit-commerce-payment-payment-details-credit-card-exp-month option:selected").each(function () {
//           $(this).removeAttr('selected'); 
//    });
//    $("#commerce-checkout-form-review #edit-commerce-payment-payment-details-credit-card-exp-year option:selected").each(function () {
//           $(this).removeAttr('selected'); 
//    });               
//    $('<option value="00" selected="selected">MM *</option>').insertBefore('#commerce-checkout-form-review #edit-commerce-payment-payment-details-credit-card-exp-month option:first-child');
//    $('<option value="00" selected="selected">YY *</option>').insertBefore('#commerce-checkout-form-review #edit-commerce-payment-payment-details-credit-card-exp-year option:first-child');
//    
    
    $('#commerce-checkout-form-review #edit-customer-profile-billing-commerce-customer-address .first-name').attr("placeholder", "First name *");
    $('#commerce-checkout-form-review #edit-customer-profile-billing-commerce-customer-address .last-name').attr("placeholder", "Last name *");
    $('#commerce-checkout-form-review #edit-customer-profile-billing-commerce-customer-address .commerce-stripe-thoroughfare').attr("placeholder", "Address 1 *");
//    $('#commerce-checkout-form-review #edit-customer-profile-billing-commerce-customer-address .commerce-stripe-premise').attr("placeholder", "Address 2");
    $('#commerce-checkout-form-review #edit-customer-profile-billing-commerce-customer-address .commerce-stripe-locality').attr("placeholder", "Town / City *");
    $('#commerce-checkout-form-review #edit-customer-profile-billing-commerce-customer-address input.state.commerce-stripe-administrative-area').attr("placeholder", "County / State");
    $('#commerce-checkout-form-review #edit-customer-profile-billing-commerce-customer-address .commerce-stripe-postal-code').attr("placeholder", "Postcode / ZIP Code *");

//    $("#commerce-checkout-form-review #edit-customer-profile-billing-commerce-customer-address-und-0-country option:selected").each(function () {
//           $(this).removeAttr('selected'); 
//    });     
    $("#commerce-checkout-form-review #edit-customer-profile-billing-commerce-customer-address select.state.commerce-stripe-administrative-area option:selected").text('County / State *');
//    $('<option value="" selected="selected">Country *</option>').insertBefore('#commerce-checkout-form-review #edit-customer-profile-billing-commerce-customer-address-und-0-country option:first-child');            

    if($('.page-checkout-review #commerce-checkout-form-review .messages.error').length){
        $( '<div id="payment-details-heading">Enter Your Card Details</div>' ).insertBefore( ".page-checkout-review #commerce-checkout-form-review #edit-commerce-payment #payment-details" );
        $('.page-checkout-review #commerce-checkout-form-review #edit-commerce-payment #edit-commerce-payment-payment-method label').html("Pay with Credit Card");
        $('.page-checkout-review #commerce-checkout-form-review #edit-customer-profile-billing .panel-heading .panel-title').html("Billing Address");        
            $('.page-checkout-review .page-title-wrapper h1.page-header').html('Payment');
            $('.page-checkout-review #commerce-checkout-form-review #edit-continue-to-payment').css('display', 'none');
            $('.page-checkout-review #commerce-checkout-form-review #edit-buttons .button-operator').css('display', 'none');
            $('.page-checkout-review #commerce-checkout-form-review #edit-buttons #edit-back').css('display', 'none');
            $('.page-checkout-review #commerce-checkout-form-review #edit-checkout-review').css('display', 'none');
            $('.page-checkout-review #commerce-checkout-form-review .checkout-help').css('display', 'none');      
            $('.page-checkout-review #commerce-checkout-form-review #edit-buttons #edit-continue').css('display', 'block');
            $('.page-checkout-review #commerce-checkout-form-review #edit-terms-conditions').css('display', 'block');
            $('.page-checkout-review #commerce-checkout-form-review #edit-commerce-payment').css('display', 'block');
            $('.page-checkout-review #commerce-checkout-form-review #edit-customer-profile-billing').css('display', 'block');
            $('.page-checkout-review #commerce-checkout-form-review #edit-buttons #edit-continue').css('float', 'left');
            $('.page-checkout-review #commerce-checkout-form-review').css("background", "rgba(0, 0, 0, 0) linear-gradient(to bottom, #00bea4 0%, #00c1a7 30%, #00b29a 100%, #a3e3da 100%) repeat scroll 0 0");
            $('.page-checkout-review #commerce-checkout-form-review').css("padding-bottom", "10px");
            $('.page-checkout-review #commerce-checkout-form-review #edit-customer-profile-billing').css("background", "none");
            $('.page-checkout-review #commerce-checkout-form-review #edit-customer-profile-billing').css("box-shadow", "none");
            $('.page-checkout-review #commerce-checkout-form-review #edit-commerce-payment').css("background", "none");
            $('.page-checkout-review #commerce-checkout-form-review #edit-commerce-payment').css("box-shadow", "none");
            $('.page-checkout-review #commerce-checkout-form-review #edit-commerce-payment .panel-heading').css("margin-top", "0px");
            $('.page-checkout-review #commerce-checkout-form-review #edit-terms-conditions').css("background", "none");
            $('.page-checkout-review #commerce-checkout-form-review #edit-terms-conditions').css("box-shadow", "none");
            $('.page-checkout-review #commerce-checkout-form-review #edit-terms-conditions .form-type-checkbox label a').css("color", "#000");
            $('.page-checkout-review #commerce-checkout-form-review #edit-buttons').css("background", "none");
            $('.page-checkout-review #commerce-checkout-form-review #edit-buttons').css("box-shadow", "none");
            $('.page-checkout-review #commerce-checkout-form-review #edit-buttons #edit-continue').css("background", "#000");
            $('.page-checkout-review #block-commerce-checkout-progress-indication li.payment').css("color", "#20b29b");
            $('.page-checkout-review #commerce-checkout-form-review #edit-buttons').css("padding", "0 60px");
    }
    if($('.page-checkout-review #commerce-checkout-form-review #edit-continue-to-payment').length){
        
    }else{
        $( '<a href="#" id="edit-continue-to-payment" class="checkout-continue btn btn-default form-submit">Continue to pay</a>' ).insertBefore( ".page-checkout-review #commerce-checkout-form-review #edit-buttons #edit-continue" );
    }

    $('.page-checkout-review #commerce-checkout-form-review #edit-continue-to-payment').click(function() {
        $( '<div id="payment-details-heading">Enter Your Card Details</div>' ).insertBefore( ".page-checkout-review #commerce-checkout-form-review #edit-commerce-payment #payment-details" );
        $('.page-checkout-review #commerce-checkout-form-review #edit-commerce-payment #edit-commerce-payment-payment-method label').html("Pay with Credit Card");
        $('.page-checkout-review #commerce-checkout-form-review #edit-customer-profile-billing .panel-heading .panel-title').html("Billing Address");
        $('.page-checkout-review .page-title-wrapper h1.page-header').html('Payment');
        $('.page-checkout-review #commerce-checkout-form-review #edit-continue-to-payment').css('display', 'none');
        $('.page-checkout-review #commerce-checkout-form-review #edit-buttons .button-operator').css('display', 'none');
        $('.page-checkout-review #commerce-checkout-form-review #edit-buttons #edit-back').css('display', 'none');
        $('.page-checkout-review #commerce-checkout-form-review #edit-checkout-review').css('display', 'none');
        $('.page-checkout-review #commerce-checkout-form-review .checkout-help').css('display', 'none');      
        $('.page-checkout-review #commerce-checkout-form-review #edit-buttons #edit-continue').css('display', 'block');
        $('.page-checkout-review #commerce-checkout-form-review #edit-terms-conditions').css('display', 'block');
        $('.page-checkout-review #commerce-checkout-form-review #edit-commerce-payment').css('display', 'block');
        $('.page-checkout-review #commerce-checkout-form-review #edit-customer-profile-billing').css('display', 'block');
        $('.page-checkout-review #commerce-checkout-form-review #edit-buttons #edit-continue').css('float', 'left');
        $('.page-checkout-review #commerce-checkout-form-review').css("background", "rgba(0, 0, 0, 0) linear-gradient(to bottom, #00bea4 0%, #00c1a7 30%, #00b29a 100%, #a3e3da 100%) repeat scroll 0 0");
        $('.page-checkout-review #commerce-checkout-form-review').css("padding-bottom", "10px");
        $('.page-checkout-review #commerce-checkout-form-review #edit-customer-profile-billing').css("background", "none");
        $('.page-checkout-review #commerce-checkout-form-review #edit-customer-profile-billing').css("box-shadow", "none");        
        $('.page-checkout-review #commerce-checkout-form-review #edit-commerce-payment').css("background", "none");
        $('.page-checkout-review #commerce-checkout-form-review #edit-commerce-payment').css("box-shadow", "none");
        $('.page-checkout-review #commerce-checkout-form-review #edit-commerce-payment .panel-heading').css("margin-top", "0px");
        $('.page-checkout-review #commerce-checkout-form-review #edit-terms-conditions').css("background", "none");
        $('.page-checkout-review #commerce-checkout-form-review #edit-terms-conditions').css("box-shadow", "none");
        $('.page-checkout-review #commerce-checkout-form-review #edit-terms-conditions .form-type-checkbox label a').css("color", "#000");
        $('.page-checkout-review #commerce-checkout-form-review #edit-buttons').css("background", "none");
        $('.page-checkout-review #commerce-checkout-form-review #edit-buttons').css("box-shadow", "none");
        $('.page-checkout-review #commerce-checkout-form-review #edit-buttons #edit-continue').css("background", "#000");
        $('.page-checkout-review #block-commerce-checkout-progress-indication li.payment').css("color", "#20b29b");
        $('.page-checkout-review #commerce-checkout-form-review #edit-buttons').css("padding", "0 60px");
    }); 


}
/*
 * End of Payment form desgin and review form
 */

/*
 * Model add bank/payout details
 */
if($('.user-bank-payout-forms').length){    
    $('.user-bank-payout-forms .bank-account-detail .add-new-bank-button').click(function() {
        $('.user-bank-payout-forms .bank-account-detail').css("display", "none");
        $('.user-bank-payout-forms .add-bank-account-form').css("display", "block");
    });     
}

/*
 * Model's redeem gift section
 */
//if($('.view-model-gifts').length){
//    var get_redeem_val = '';
//    get_redeem_val = $('.view-model-gifts tbody .views-field-field-model-redeem').html();
//    if(get_redeem_val == ' Pending '){
//        $('.view-model-gifts tbody .views-field-field-model-redeem').addClass('redeem-pending');
//    }else{
//        $('.view-model-gifts tbody .views-field-field-model-redeem').addClass('redeem-completed');
//    }
//}
if($('.redeem-gift-first-section').length){
    $('.page-title-wrapper h1.page-header').html('Redeem Gift');
}
if($('.redeem-gift-second-section').length){
    $('.page-title-wrapper h1.page-header').html('Redeem Gift');
}
$('.redeem-gift-first-section .redeem-gift-first-section-body .redeem-gift-amount .amount').click(function() {
    $('.redeem-gift-first-section').css("display", "none");
    $('.redeem-gift-second-section').css("display", "block");
}); 

$('.close-product-model-box span').click(function() {
    $('#simple-dialog-container').dialog('close');
});        
        
$('.view-wishlist-page.view-display-id-block_2 .field-name-field-product-image .image-widget-data button.btn-danger').html('Edit Image');

//$(".form-type-select.form-item-field-product-store-country-value .form-item .form-item-edit-field-product-store-country-value-all").insertAfter($(".form-type-select.form-item-field-product-store-country-value .form-item div:last"));
$(".form-type-select.form-item-field-product-category-tid .form-item .form-item-edit-field-product-category-tid-all").insertAfter($(".form-type-select.form-item-field-product-category-tid .form-item div:last"));
$(".form-type-select.form-item-field-product-brand-tid .form-item .form-item-edit-field-product-brand-tid-all").insertAfter($(".form-type-select.form-item-field-product-brand-tid .form-item div:last"));
//$(".form-item-edit-field-product-store-country-value-all a").text('See all');
$('#edit-commerce-price-amount-wrapper').insertAfter($("#edit-field-product-brand-tid-wrapper"));

var min  = $('.view-all-shop-products #edit-commerce-price-amount-wrapper #edit-commerce-price-amount-min').val();
var max  = $('.view-all-shop-products #edit-commerce-price-amount-wrapper #edit-commerce-price-amount-max').val();
$('.view-all-shop-products #edit-commerce-price-amount-wrapper .ui-slider-horizontal a:first').html('<span class="min-price-filter">'+min+'</span>');
$('.view-all-shop-products #edit-commerce-price-amount-wrapper .ui-slider-horizontal a:last').html('<span class="max-price-filter">'+max+'</span>');

$(".view-all-shop-products #edit-commerce-price-amount-wrapper .ui-slider-horizontal a:first").mousemove(function(e){
var min  = $('.view-all-shop-products #edit-commerce-price-amount-wrapper #edit-commerce-price-amount-min').val();
$('.view-all-shop-products #edit-commerce-price-amount-wrapper .ui-slider-horizontal a:first').html('<span class="min-price-filter">'+min+'</span>'); 
var min_length = min.length;
if(min_length == 3){
$('.view-all-shop-products #edit-commerce-price-amount-wrapper .ui-slider-horizontal a .min-price-filter').css('left','-9px');    
}
});
$(".view-all-shop-products #edit-commerce-price-amount-wrapper .ui-slider-horizontal a:last").mousemove(function(e){
var max  = $('.view-all-shop-products #edit-commerce-price-amount-wrapper #edit-commerce-price-amount-max').val();
$('.view-all-shop-products #edit-commerce-price-amount-wrapper .ui-slider-horizontal a:last').html('<span class="max-price-filter">'+max+'</span>'); 
});

$(".page-checkout .component-type-taxcustom-sales-tax-for-particular-stores .component-title").attr('title', 'This third party store charges a sales tax on all purchases.');

$(".view-wishlist-page .view-header .share-wishlist-container .send-a-Gift-button").click(function(){
   $(".view-wishlist-page .view-content").css('display', 'none');
   $(".view-wishlist-page .text-center").css('display', 'none');
   $(".view-wishlist-page .send-a-gift-section").css('display', 'block');
});
$(".send-a-gift-section .send-a-gift-header .send-a-gift-close-button").click(function(){
   $(".view-wishlist-page .view-content").css('display', 'block');
   $(".view-wishlist-page .text-center").css('display', 'block');
   $(".view-wishlist-page .send-a-gift-section").css('display', 'none');
});

$("#add-items-from-amazon-wishlist .modal-header button.close").click(function(){
  $("#add-items-from-amazon-wishlist #dc-import-amazon-wl-add-amazon-wishlist-url-form #edit-amazon-wishlist-url").val('');
});
$("#add-item-to-wishlist .modal-header button.close").click(function(){
  $("#add-item-to-wishlist #hd-tweaks-add-a-product-url-form #edit-product-url").val('');
});

$("#topBarGreetings .close-greetings").click(function(){
   $("#topBarGreetings").css('display', 'none');
   $.cookie('topBarGreetings', '1');
   console.log($.cookie('topBarGreetings'));
});

$('.share-wishlist-container .wishlistShare').click(function(){
    $('#wishlist-share').css('visibility', 'visible');
});
$('#wishlist-share .modal-header .close').click(function(){
    $('#wishlist-share').css('visibility', 'hidden');
});

/*
 * User's profile image preview while uploading new image
 */

$("#edit-picture-upload").attr("onChange", "showpreviewimage(this)");



// remove master card logo on checkout review page

      $(".commerce-paypal-icons img").each(function () {
        if ($(this).attr("title") == "Mastercard") {
            $(this).remove();
        }
      }); 
    
 // user profile page change currency for logged in user
 
 $("#userProfileCurrency").change(function() {
 	
    var selectedValue = this.value;
    $.ajax({
        url: '../dc_gift_vouchers.php',
        type: 'POST',
        data: {option : selectedValue},
        success: function(result) {
            $('.gift-balance-user p').html(result);
        }
    });
});     
 $(".addToDcWidgets").click(function() {
    var widget_status;    
    var wishlist_item_id;
    var checkb  =   $(this);
    
    wishlist_item_id = this.value;
     
    if($(this).is(":checked")){
         widget_status = '1';
    }else{
         widget_status = '0';
    }
//    $(modal).dialog()
    $( ".item-add-to-widget" ).dialog({
      resizable: false,
      modal: true
    });
    $.ajax({
        
        url: '../dc_widgets_add_remove.php',
        type: 'POST',
        data: {widget_status : widget_status, wishlist_item_id : wishlist_item_id},
        success: function(result) {
            if(result == 'nomore'){
                $('.item-add-to-widget').dialog('close');
                checkb.removeAttr('checked');
                $( ".item-add-to-widget-notice" ).dialog({
                  resizable: false,
                  modal: true,
                  width: 300
                });                
            }else{
                $('.item-add-to-widget').dialog('close');
                console.log('Your action is successfully completed.');                
            }
        }
    });    

}); 

if( $('#widgetBlack').length ){
    if(document.getElementById('widgetBlack').checked) {
        $('#my-widgets-black').css('display', 'block');
    }    
}
if( $('#widgetWhite').length ){
    if(document.getElementById('widgetWhite').checked) {
        $('#my-widgets-white').css('display', 'block');
    }    
}
 
 
$("#widgetSelectBackground #widgetBlack").click(function() {
    $('#my-widgets-white').css('display', 'none');
    $('#my-widgets-black').css('display', 'block');
});
$("#widgetSelectBackground #widgetWhite").click(function() {
    $('#my-widgets-white').css('display', 'block');
    $('#my-widgets-black').css('display', 'none');
});
$(".closeDcWidgetModel").click(function() {
    $('.item-add-to-widget').dialog('close');
    $('.item-add-to-widget-notice').dialog('close');
});


//$(".manage-wishlist-display").click(function(e){
//$(this).css('display', 'none');
//$('#views-exposed-form-wishlist-page-default').css('display', 'none');
//$('.share-wishlist-container').css('display', 'none');
//$('.back-to-wishlist-items').css('display', 'block');  
//$('.view-wishlist-page.view-display-id-default .view-content').css('display', 'none'); 
//$('.view-wishlist-page.view-display-id-default .text-center').css('display', 'none');
//$('.view-id-wishlist_page.view-display-id-default .view-footer').css('display', 'block');  
//$('.view-wishlist-page.view-display-id-default .view-footer .view-content').css('display', 'block'); 
//$('.view-wishlist-page.view-display-id-default .view-footer .text-center').css('display', 'block');  
//});

$(".back-to-wishlist-items").click(function(e){
	window.location.reload();
  
});

//remove click on menu delimiter
	$(".navbar a.menu-delimiter", context).click(function(e){
		e.preventDefault();
	});

      if($('.youtubeVideo').length) {
        setupVideo();
      }
      if($('#wishlist-share').length) {
        fixTwitterShareLink();
      }

      function fixTwitterShareLink() {
        var link = $('#wishlist-share a.twitter-share-button'),
          href = link.attr('href');

        href = href.replace('%26%23039%3B', '\'');
        link.attr('href', href);
      }

      function setupVideo() {
            var tag = document.createElement('script');

            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            Drupal.settings.introVideo = false;
      }

        window.onYouTubeIframeAPIReady = function() {
          var videos = $('.youtubeVideo');
          Drupal.settings.videos = Drupal.settings.videos || [];
          videos.each(function() {
            var videoID = $(this).data('videoid'),
              wrapperID = 'video' + videoID,
              overlay;

            this.id = wrapperID;
            overlay = $(this).next('.video-overlay');
            overlay.attr('rel', videoID);

            var videoAPI = new YT.Player(wrapperID, {
              height: '390',
              width: '640',
              videoId: videoID,
              playerVars: {
                rel: 0
              },
              events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
              }
            });

            Drupal.settings.videos[videoID] = videoAPI;

          });
        };

        function onPlayerReady() {
          var iOS = /(iPad|iPhone|iPod)/g.test( navigator.userAgent );
          if(iOS) {
            $('.video-overlay').hide();
          }
          console.log(arguments);
        }

        function onPlayerStateChange() {
          console.log(arguments)
        }

        // Loading button text - adding product url to wishlist.
        $('#add-item-url').on('click', function () {
          var $btn = $(this).button('loading');
          // business logic...
          // $btn.button('reset')
        });

        $('.video-overlay').on('click', function(e) {
          var vidid = this.getAttribute('rel'),
            video = Drupal.settings.videos[vidid];

          if(video) {
              e.preventDefault();
            $(this).fadeOut(200, function() {
              video.playVideo();
            });
          }
        });


		}
	};
})(jQuery);
