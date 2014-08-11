<?php
/**
 * Mailing list subscription form
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor
 * @version		Release: 1.0
 */

$desc_tag_open = ( !empty( $desc_tag ) ) ? "<$desc_tag>" : '<label for="mailing-list-form-email">';
$desc_tag_close = ( !empty( $desc_tag ) ) ? "</$desc_tag>" : '</label>';

?>
<form id="mailing-list-form" class="mailing-list-form" method="GET" action="<?php echo get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/api/mailing-list.php'; ?>">
	<?php echo $desc = ( !empty( $desc ) ) ? $desc_tag_open . $desc . $desc_tag_close : ''; ?>
	<input id="mailing-list-form-action" class="action" type="hidden" name="action" value="save">
	<input id="mailing-list-form-redirect" class="redirect" type="hidden" name="redirect" value="<?php bloginfo( 'url' ); ?>">
	<input id="mailing-list-form-email" class="email" type="text" name="email" value="" placeholder="Email" required>
	<input id="mailing-list-form-submit" type="submit" value="Subscribe">
	<?php echo $message = ( !empty( $_GET['user'] ) ) ? '<p class="message">' . urldecode( $_GET['user'] ) . '</p>' : ''; ?>
</form>

<?php if( get_option( 'mailing_list_settings_ajax' ) ) : ?>
<script>
$(function() {
	var form = $('#mailing-list-form');
	form.on('submit', function(e) {
		e.preventDefault();
		form.find('.redirect').remove();
		form.find('.message').remove();
		$.ajax({
			url: $(this).attr('action'),
			type: 'GET',
			dataType: 'json',
			data: $(this).serialize(),
			error: function() {
				form.append('<p class="message">Sorry, something went wrong.  Please try again later.</p>');
			},
			success: function(resp) {
				if(resp.status === 'success') {
					form.find('.email').val('');
					form.append('<p class="message">' + resp.user + '</p>');
				} else {
					form.append('<p class="message">' + resp.user + '</p>');
				}
			}
		});
	});
});
</script>
<?php endif; ?>
