<?php
/**
 * Mailing list unsubscribe confirmation page.
 *
 * @package     Интоор Library (intoor)
 * @author      Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright   2014 Hazard Media Group LLC
 * @license     MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link        https://github.com/Alekhen/intoor-lib
 * @version     Release: 1.2
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
require_once dirname( dirname( __FILE__ ) ) . '/config.php';

if( !defined( 'INTOOR_RESTRICT_ACCESS' ) || !INTOOR_RESTRICT_ACCESS ) { die( 'Unauthorized Access' ); }

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	
	<title>Mailing List Unsubscribe Confirmation | <?php bloginfo( 'blogname' ); ?></title>
	
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<?php wp_head(); ?>
</head>

<body>

	<h1>Mailing List Unsubscribe Confirmation</h1>

	<form id="mailing-list-unsubscribe-form" method="POST" action="<?= get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/api/mailing-list.php'; ?>">
		<?php if( !empty( $_GET['recipient'] ) ) : ?>
		<div id="unsubscribe-form">
			<p>Are you sure you want to unsubscribe from our mailing list?</p>
			<input id="mailing-list-unsubscribe-action" class="action" type="hidden" name="action" value="unsubscribe">
			<input id="mailing-list-unsubscribe-api-key" class="api-key" type="hidden" name="api_key" value="<?= API::get_key( 'mailing_list' ); ?>">
			<input id="mailing-list-unsubscribe-redirect" class="redirect" type="hidden" name="redirect" value="<?= get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/views/mailing-list-unsubscribe.php' ?>">
			<input id="mailing-list-unsubscribe-email" class="email" type="text" name="email" value="<?= $_GET['recipient']; ?>" placeholder="Email" required>
			<input id="mailing-list-unsubscribe-cc" class="cc" type="text" name="cc" value="" placeholder="Leave this field blank" style="visibility:hidden; position:absolute; left:-9999px;">
			<input id="mailing-list-unsubscribe-submit" class="unsubscribe" type="submit" value="Unsubscribe">
		</div><!--#unsubscribe-form-->
		<?php endif; ?>
		<?= ( !empty( $_GET['status'] ) ) ? '<p class="message">' . urldecode( base64_decode( $_GET['display'] ) ) . '</p>' : '<p class="message" style="display:none;"></p>'; ?>
	</form>

	<?php if( get_option( 'mailing_list_settings_ajax' ) ) : ?>
	<script>
	$(function() {
		var form = $('#mailing-list-unsubscribe-form');
		form.on('submit', function(e) {
			e.preventDefault();
			var error = '';
			error = validate_mailing_list_form_captcha(form.find('.cc'));
			if(error !== '') {
				form.find('.message').empty().append(error).fadeIn();
			} else {
				error = validate_mailing_list_form_email(form.find('.email'));
				if(error !== '') {
					form.find('.message').empty().append(error).fadeIn();
				} else {
					form.find('.redirect').remove();
					$.ajax({
						url: $(this).attr('action'),
						type: 'POST',
						dataType: 'json',
						data: $(this).serialize(),
						error: function() {
							form.find('.message').empty().append('Sorry, something went wrong. Please try again later.').fadeIn();
						},
						success: function(resp) {
							console.log(resp);
							if(resp.status === 'success') {
								form.find('.message').empty().append(resp.display).append('<br><a href="<?= get_bloginfo( 'url' ); ?>">Back to Home</a>').fadeIn();
								$('#unsubscribe-form').remove();
							} else {
								form.find('.message').empty().append(resp.display).fadeIn();
								if(resp.type == 'not-found') {
									form.find('.message').append('<br><a href="<?= get_bloginfo( 'url' ); ?>">Back to Home</a>');
									$('#unsubscribe-form').remove();
								}
							}
						}
					});
				}
			}
		});
		function validate_mailing_list_form_email(el) {
			var value = el.val(),
				format = /^[A-Za-z0-9._%\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,4}$/,
				error = '';
			if(!value) {
				error = 'Your email address must be entered in this field.';
			} else if(!value.match(format)) {
				error = 'Your email address isn\'t valid. Please try again.';
			}
			return error;
		};
		function validate_mailing_list_form_captcha(el) {
			var value = el.val();
			return (value) ? 'Sorry, but robots are not allowed to participate in our mailing list.' : '';
		};
	});
	</script>
	<?php endif; ?>

</body>
</html>