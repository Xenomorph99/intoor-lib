<?php
/**
 * Mailing list subscription form
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

$form = array(
	'id' => 'mailing-list-form',
	'label' => '',
	'label_tag' => '',
	'submit_btn' => 'Subscribe',
	'placeholder' => 'Email'
);

$form = wp_parse_args( $args, $form );

$label_tag_open = ( !empty( $form['label_tag'] ) ) ? '<' . $form['label_tag'] . '>' : '<label for="mailing-list-form-email">';
$label_tag_close = ( !empty( $form['label_tag'] ) ) ? '</' . $form['label_tag'] . '>' : '</label>';

?>
<form id="<?php echo $form['id']; ?>" class="mailing-list-form" method="GET" action="<?php echo get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/api/mailing-list.php'; ?>">
	<?php echo ( !empty( $form['label'] ) ) ? $label_tag_open . $form['label'] . $label_tag_close : ''; ?>
	<input id="mailing-list-form-action" class="action" type="hidden" name="action" value="save">
	<input id="mailing-list-form-redirect" class="redirect" type="hidden" name="redirect" value="<?php bloginfo( 'url' ); ?>">
	<input id="mailing-list-form-email" class="email" type="text" name="email" value="" placeholder="<?php echo $form['placeholder']; ?>" required>
	<input id="mailing-list-form-submit" type="submit" value="<?php echo $form['submit_btn']; ?>">
	<?php echo ( !empty( $_GET['user'] ) ) ? '<p class="message">' . urldecode( $_GET['user'] ) . '</p>' : ''; ?>
</form>

<?php if( get_option( 'mailing_list_settings_ajax' ) ) : ?>
<script>
$(function() {
	<?php echo "var form = $('#" . $form['id'] . "');"; ?>
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
				<?php echo ( GOOGLE_ANALYTICS ) ? "ga( 'send', 'event', 'Mailing List', 'API Status', 'Error' );" : ''; ?>
			},
			success: function(resp) {
				if(resp.status === 'success') {
					form.find('.email').val('');
					form.append('<p class="message">' + resp.user + '</p>');
					<?php echo ( GOOGLE_ANALYTICS ) ? "ga( 'send', 'event', 'Mailing List', 'Subscribe', 'Success' );" : ''; ?>
				} else if(resp.status === 'duplicate') {
					form.append('<p class="message">' + resp.user + '</p>');
					<?php echo ( GOOGLE_ANALYTICS ) ? "ga( 'send', 'event', 'Mailing List', 'Subscribe', 'Duplicate Email' );" : ''; ?>
				} else {
					form.append('<p class="message">' + resp.user + '</p>');
					<?php echo ( GOOGLE_ANALYTICS ) ? "ga( 'send', 'event', 'Mailing List', 'Subscribe', 'Invalid Email' );" : ''; ?>
				}
				<?php echo ( GOOGLE_ANALYTICS ) ? "ga( 'send', 'event', 'Mailing List', 'API Status', 'Success' );" : ''; ?>
			}
		});
	});
});
</script>
<?php endif;