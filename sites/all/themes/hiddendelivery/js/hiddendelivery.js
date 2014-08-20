(function ($) {
	Drupal.behaviors.globalScript = {
    attach: function (context, settings) {

			//remove click on menu delimiter
			$(".navbar a.menu-delimiter", context).click(function(e){
				e.preventDefault();
			});

      if($('.share-widget').length) {
        setupShareWidget();
      }

      function setupShareWidget() {
        if (typeof(Share) === 'undefined') {
          return;
        }
        Drupal.settings.shareWidget = new Share('.share-widget', {
          networks: {
            google_plus: {
              enabled: false //Plugin has been hack to emulate this; adding in here for future compatibility fixes
            },
            facebook: {
              load_sdk: false
            }
          }
        });
        $('h2.col-share').on('click', function(e) {
          Drupal.settings.shareWidget.toggle();
          e.preventDefault();
        });
        // Facebook button
        $('.home-column .link-facebook').on('click', function(e) {
          console.log('hello');
          $('.share-widget li.entypo-facebook').trigger('click');

          e.preventDefault();
        });
        // Twitter button
        $('.home-column .link-twitter').on('click', function(e) {
          console.log('twit hello');
          $('.share-widget li.entypo-twitter').trigger('click');
          e.preventDefault();
        });
      }

      $('.slides li').each(function(i,el){
        el.id = "slide-"+(i+1);
      });

		}
	}

  // Extension notifier
  // $(window).on("load", function(){
  //   if (!$("div#extensionInstalled").length) {
  //     $('<div id="extension-notifier"><p>Have you got the extension?<div class="install-extension-button"><a class="btn btn-primary form-submit install-extension-button" href="https://chrome.google.com/webstore/detail/delivery-code-wishlist/jfoemdhjganhhccomidjodmikemlkajo" id="edit-submit" name="op" value="submit">Add to browser</a></div></p></div>').prependTo($('body')).slideDown(1000);
  //   }
  // })

})(jQuery);