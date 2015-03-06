(function ($) {
	Drupal.behaviors.globalScript = {
    attach: function (context, settings) {

// remove master card logo on checkout review page
      $(".commerce-paypal-icons img").each(function () {
        if ($(this).attr("title") == "Mastercard") {
            $(this).remove();
        }
      }); 
 // user profile page change currency for logged in user
 
 $("#userProfileCurrency").change(function() {
    //get the selected value
    var selectedValue = this.value;

	 //make the ajax call
    $.ajax({
        url: '../dc_gift_vouchers.php',
        type: 'POST',
        data: {option : selectedValue},
        success: function(result) {
            $('.gift-balance-user p').html(result);
        }
    });
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
