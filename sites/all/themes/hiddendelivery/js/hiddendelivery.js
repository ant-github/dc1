(function ($) {
	Drupal.behaviors.globalScript = {
    attach: function (context, settings) {

			//remove click on menu delimiter
			$(".navbar a.menu-delimiter", context).click(function(e){
				e.preventDefault();
			});

      if($('#introVideo').length) {
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
          var iOS = /(iPad|iPhone|iPod)/g.test( navigator.userAgent );
          if(iOS) {
            $('.video-overlay').hide();
          } else {
             var tag = document.createElement('script');

            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            Drupal.settings.introVideo = false;
          }
        }

        window.onYouTubeIframeAPIReady = function() {
          Drupal.settings.introVideo = new YT.Player('introVideo', {
            height: '390',
            width: '640',
            videoId: 'niEKZ-CQ1tY',
            events: {
              'onReady': onPlayerReady,
              'onStateChange': onPlayerStateChange
            }
          });
        };

        function onPlayerReady() {
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
          if(Drupal.settings.introVideo) {
              e.preventDefault();
            $(this).fadeOut(200, function() {
              Drupal.settings.introVideo.playVideo();
            });
          }
        });


		}
	};
})(jQuery);
