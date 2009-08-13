var Site = {

	// this vars should be set in <head> server-side
	config: {
		base_url: '',
		site_url: ''
	},
	
	// this method is called on every page
	init: function() {

		// On Dom Ready
		jQuery(function($) {

		});
		
		// On Window Load
		jQuery(window).load(function ($) {

		});
		
		// Load Immediately
		(function($) {
		
		})(jQuery);

		if ($.browser.msie && $.browser.version <= 6 )
		{
			// gay stuff goes here
		}
	}
	
	/*
	Use the following methodology when creating additional functions
	home: function() {
		// This function shall be called inline around a domready event.
		init: function() {
			
		}
		
		update: function() {
			// Function associated with home
		},
		
		add: function() {
			// Another function associated with home
		}
	}
	*/
	
};

Site.init();