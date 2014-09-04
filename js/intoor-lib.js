/**
 * Интоор Library JS Object
 *
 * @require jquery.js
 * @version 1.0
 */

(function($) {

var Intoor = {

	init: function() {

		this.setSocialMediaShareCount();

	},

	setSocialMediaShareCount: function() {

		$('a.share-counter').on('click', function(e) {
			e.preventDefault();
			var el = $(this);
			var data = {
				action: 'share',
				post_id: el.data('id'),
				key: el.data('key')
			};
			ga('send', 'event', 'Social', 'Share', data.key);
			$.ajax({
				url: $(this).data('api'),
				type: 'POST',
				data: data,
				dataType: 'json',
				error: function() {
					ga('send', 'event', 'Social', 'API Status', 'Error');
				},
				success: function() {
					ga('send', 'event', 'Social', 'API Status', 'Success');
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