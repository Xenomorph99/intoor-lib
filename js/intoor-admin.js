/**
 * Интоор Library JS Object
 *
 * @require jquery.js
 * @version 1.0
 */

var Intoor = {

	init: function() {

		this.setMetaBoxToggles();
		this.setMetaBoxCheckboxContainers();
		this.setMetaBoxButtons();
		this.exportCSV();
		this.setPopularMetaBox();

	},

	setMetaBoxToggles: function() {

		jQuery('input[type="checkbox"]').on('change', function() {
			var id = jQuery(this).attr('id'),
				hidden = jQuery(this).parent().find('#hidden-' + id),
				val = (jQuery(this).is(':checked')) ? '1' : '0';
			hidden.val(val);
		});

	},

	setMetaBoxCheckboxContainers: function() {

		jQuery('.contained-checkbox').on('change', function() {
			var value = jQuery(this).val(),
				controller = jQuery(this).parent().parent().parent().parent().parent().find('.checkbox-container-controller'),
				controllerValue = controller.val(),
				newControllerValue = '';
			if(jQuery(this).is(':checked')) {
				if( controllerValue === '' ) {
					controller.val(value);
				} else {
					newControllerValue = controllerValue + ',' + value;
					newControllerValue.replace(',,', ',');
					controller.val(newControllerValue);
				}
			} else {
				newControllerValue = controllerValue.replace(value, '').replace(',,', ',').replace(/,jQuery/, '').replace(/^,/, '');
				controller.val(newControllerValue);
			}
		});

	},

	setMetaBoxButtons: function() {

		jQuery('.meta-box-restore-defaults').on('click', function(e) {
			e.preventDefault();
			var conf = confirm('Are you sure you want to reset this meta box with the default values?');
			if(conf){
				var box = jQuery(this).parent().parent();
				var id = box.find('.meta-box-form-section .meta-box-section-id').val();
				var defaults = box.find('.meta-box-form-defaults').html();
				box.find('.meta-box-form-section').remove();
				box.find('.meta-box-buttons').before('<div class="meta-box-form-section">' + defaults + '</div>');
				box.find('.meta-box-form-section .meta-box-section-id').val(id);
				Intoor.setMetaBoxToggles();
			}
		});

		jQuery('.meta-box-add-form-section').on('click', function(e) {
			e.preventDefault();
			var box = jQuery(this).parent().parent();
			var defaults = box.find('.meta-box-form-defaults').html();
			box.find('.meta-box-buttons').before('<div class="meta-box-form-section">' + defaults + '</div>');
			Intoor.setMetaBoxToggles();
		});

		jQuery('.meta-box-remove-form-section').on('click', function(e) {
			e.preventDefault();
			var box = jQuery(this).parent().parent();
			var section = box.find('.meta-box-form-section').last();
			section.css('display', 'none').removeClass('meta-box-form-section').addClass('meta-box-form-section-disabled');
			section.find('.meta-box-section-id').val('-' + section.find('.meta-box-section-id').val());
		});

	},

	exportCSV: function() {

		jQuery('#mailing-list-export-btn').on('click', function(e) {
			e.preventDefault();
			var url = jQuery(this).data('api');
			window.open(url, 'csv');
		});

	},

	setPopularMetaBox: function() {

		var popularCount = 0,
			temp;

		jQuery('#popular-posts-popular').on('change', function() {
			temp = parseInt(jQuery('#total-popular-count').text());
			if(jQuery(this).is(':checked')) {
				popularCount = temp + 1;
			} else {
				popularCount = temp - 1;
			}
			jQuery('#total-popular-count').text(popularCount);
		});

	}

};

jQuery(function() {
	Intoor.init();
});