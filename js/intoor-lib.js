/**
 * Интоор Library JS Object
 *
 * @require jquery.js
 * @version 1.0
 */

(function($) {

var Intoor = {

	init: function() {

		this.setPopularLikeButton();
		this.setSocialMediaShareCount();

	},

	setPopularLikeButton: function() {

		// Do nothing

	},

	setSocialMediaShareCount: function() {

		$('a.share-counter').on('click', function(e) {
			e.preventDefault();
			var el = $(this);
			var data = {
				action: 'share',
				post_id: el.data('id'),
				network: el.data('network'),
				api_key: el.data('key')
			};
			ga('send', 'event', 'Social', 'Share', data.key);
			$.ajax({
				url: el.data('api'),
				type: 'POST',
				async: false,
				data: data,
				dataType: 'json',
				error: function() {
					ga('send', 'event', 'Social', 'API Status', 'Error');
				},
				success: function(resp) {
					ga('send', 'event', 'Social', 'API Status', 'Success');
					counter = el.find('.social-media-share-button-count');
					newCount = parseInt( counter.html(), 10 ) + 1;
					counter.empty().append(newCount);
				},
				complete: function() {
					window.open(el.attr('href'));
				}
			});
		});

	}

};

$(function() {
	Intoor.init();
});

})(jQuery);