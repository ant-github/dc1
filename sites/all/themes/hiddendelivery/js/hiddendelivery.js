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

      function setupVideo() {
           var tag = document.createElement('script');

            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
          Drupal.settings.introVideo = false;
        }

        window.onYouTubeIframeAPIReady = function() {
          Drupal.settings.introVideo = new YT.Player('introVideo', {
            height: '390',
            width: '640',
            videoId: 'or0L7UnaP6g',
            events: {
              'onReady': onPlayerReady,
              'onStateChange': onPlayerStateChange
            }
          });
        }

        function onPlayerReady() {
          console.log(arguments);
        }

        function onPlayerStateChange() {
          console.log(arguments)
        }

        $('.video-overlay').on('click', function(e) {
          if(Drupal.settings.introVideo) {
              e.preventDefault();
            $(this).fadeOut(200, function() {
              Drupal.settings.introVideo.playVideo();
            });
          }
        })

		}
	}

})(jQuery);
