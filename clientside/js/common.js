var Site;

(function($) {
	Site = {

		// This vars should be set in <head> server-side
		config: {
			base_url: '',
			site_url: ''
		},
	
		// This method is called on every page
		init: function() {
			
			// On Dom Ready
			$(function() {
				
			});
		
			// On Window Load
			$(window).load(function ($) {

			});
		
			// Load gay stuff goes here
			if ($.browser.msie && $.browser.version <= 6 )
			{

			}
			
			// Load Immediately
		}
	
		/*
		Use the following methodology when creating additional functions
		home: {
			// This function shall be called inline on every page
			init: function() {
			
			},

			update: function() {
				// Function associated with home
			},
		
			add: function() {
				// Another function associated with home
			}
		}
		*/
	};
})(jQuery);

Site.init();