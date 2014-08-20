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
			$.ajax({
				url: $(this).data('api'),
				type: 'POST',
				data: data,
				dataType: 'json',
				success: function(resp) {
					//alert(resp.message);
				},
				complete: function() {
					window.location = el.attr('href');
				}
			});
		});

	}

};

$(function() {
	Intoor.init();
});

})(jQuery);