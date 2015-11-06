(function ($) {
	Drupal.behaviors.globalScript = {
    attach: function (context, settings) {
        
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

$("#topBarGreetings .close-greetings").click(function(){
   $("#topBarGreetings").css('display', 'none');
   $.cookie('topBarGreetings', '1');
   console.log($.cookie('topBarGreetings'));
});

$('.share-wishlist-container button').click(function(){
    $('#wishlist-share').css('visibility', 'visible');
});
$('#wishlist-share .modal-header .close').click(function(){
    $('#wishlist-share').css('visibility', 'hidden');
});

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


$(".manage-wishlist-display").click(function(e){
$(this).css('display', 'none');
$('#views-exposed-form-wishlist-page-default').css('display', 'none');
$('.share-wishlist-container').css('display', 'none');
$('.back-to-wishlist-items').css('display', 'block');  
$('.view-wishlist-page.view-display-id-default .view-content').css('display', 'none'); 
$('.view-wishlist-page.view-display-id-default .text-center').css('display', 'none');
$('.view-id-wishlist_page.view-display-id-default .view-footer').css('display', 'block');  
$('.view-wishlist-page.view-display-id-default .view-footer .view-content').css('display', 'block'); 
$('.view-wishlist-page.view-display-id-default .view-footer .text-center').css('display', 'block');  
});

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
