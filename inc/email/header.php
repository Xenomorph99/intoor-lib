<?php
/**
 * HTML Email Header.
 *
 * @package     Интоор Library (intoor)
 * @author      Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright   2014 Hazard Media Group LLC
 * @license     MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link        https://github.com/Alekhen/intoor-lib
 * @version     Release: 1.2
 */

if( !defined( 'INTOOR_RESTRICT_ACCESS' ) || !INTOOR_RESTRICT_ACCESS ) { die( 'Unauthorized Access' ); }

$args = [
	'sender' => '',
	'recipient' => '',
	'subject' => '',
];

if( isset( $this ) ) :
	$args = wp_parse_args( $this->args, $args );
elseif( !empty( $_GET['sender'] ) && !empty( $_GET['recipient'] ) && !empty( $_GET['subject'] ) ) :
	$args = wp_parse_args( $_GET, $args );
else :
	exit( 'You do not have permission to view this page.' );
endif;

extract( $args );

$prefix = 'mailing_list_settings_';

$color = [
	'body' => get_option( $prefix . 'color_body', 'f0f0f0' ),
	'container' => get_option( $prefix . 'color_container', 'ffffff' ),
	'banner' => get_option( $prefix . 'color_banner', 'bdbdbd' ),
	'text_heading' => get_option( $prefix . 'color_text_heading', '58595b' ),
	'text_primary' => get_option( $prefix . 'color_text_primary', '58595b' ),
	'text_secondary' => get_option( $prefix . 'color_text_secondary', 'bcbec0' ),
	'text_link' => get_option( $prefix . 'color_text_link', 'ff3c00' ),
];

$social = [
	'facebook' => get_option( $prefix . 'facebook', '' ),
	'twitter' => get_option( $prefix . 'twitter', '' ),
	'pinterest' => get_option( $prefix . 'pinterest', '' ),
	'google' => get_option( $prefix . 'google', '' ),
	'instagram' => get_option( $prefix . 'instagram', '' ),
	'linkedin' => get_option( $prefix . 'linkedin', '' ),
	'youtube' => get_option( $prefix . 'youtube', '' ),
	'tumblr' => get_option( $prefix . 'tumblr', '' ),
];

foreach( $social as $network => $url ) {
	if( empty( $url ) ) {
		unset( $social[$network] );
	}
}

$web_url = get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/views/email/' . $template_name . '.php?' . http_build_query( $args );
$unsubscribe_url = get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/views/mailing-list-unsubscribe.php?' . http_build_query( $args );

$reset = 'margin:0; padding:0;';
$helvetica = "'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif";
$posts = wp_get_recent_posts( array( 'numberposts' => 3, 'post_status' => 'publish' ), OBJECT );
$count = count( $posts );

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<title><?php echo $subject . ' | ' . get_bloginfo( 'blogname' ); ?></title>
</head>

<body style="color:#<?php echo $color['text_secondary']; ?>; font:300 13px/19px <?php echo $helvetica; ?>; background-color:#<?php echo $color['body']; ?>; <?php echo $reset; ?>">

	<table style="border-collapse:collapse; width:600px; margin:0 auto; padding:0; border:none; line-height:0;" cellpadding="0" cellspacing="0">
		<tbody style="<?php echo $reset; ?>">

			<tr style="<?php echo $reset; ?>">
				<td style="width:600px; <?php echo $reset; ?>">
					<?php if( $template_name == 'unsubscribe' ) : ?>
					<p style="line-height:19px; text-align:center; margin:8px 0 16px 0; padding:0;">View in <a href="<?php echo $web_url; ?>" style="color:#<?php echo $color['text_secondary']; ?>; <?php echo $reset; ?>">Web Browser</a></p>
					<?php else : ?>
					<p style="line-height:19px; text-align:center; margin:8px 0 16px 0; padding:0;">View in <a href="<?php echo $web_url; ?>" style="color:#<?php echo $color['text_secondary']; ?>; <?php echo $reset; ?>">Web Browser</a> | <a href="<?php echo $unsubscribe_url; ?>" style="color:#<?php echo $color['text_secondary']; ?>; <?php echo $reset; ?>">Unsubscribe</a></p>
					<?php endif; ?>
				</td>
			</tr>

			<tr style="<?php echo $reset; ?>">
				<td style="width:600px; <?php echo $reset; ?>">

					<table style="color:#<?php echo $color['text_primary']; ?>; background-color:#<?php echo $color['container']; ?>; border-collapse:collapse; width:600px; margin:0 auto; padding:0; line-height:0; border:none;" cellpadding="0" cellspacing="0">

						<thead style="<?php echo $reset; ?>">

							<tr class="email-header" style="<?php echo $reset; ?>">
								<td style="width:20px; display:block; <?php echo $reset; ?>">&nbsp;</td>
								<td style="<?php echo $reset; ?>">
									<table style="border-collapse:collapse; width:560px; border:none; margin:0 auto; padding:0; line-height:0;" cellpadding="0" cellspacing="0">
										<tbody style="<?php echo $reset; ?>">
											<tr style="height:90px; <?php echo $reset; ?>">

												<?php if( empty( $social ) ) : ?>
												<td id="logo" style="text-align:center; <?php echo $reset; ?>">
												<?php else : ?>
												<td id="logo" style="<?php echo $reset; ?>">
												<?php endif; ?>
													<a href="<?php bloginfo( 'url' ); ?>" style="display:inline-block; <?php echo $reset; ?>">
														<?php if( get_option( $prefix . 'logo' ) !== '' ) : ?>
														<img src="<?php echo get_option( $prefix . 'logo' ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="<?php echo get_option( $prefix . 'logo_width' ); ?>" height="90" style="<?php echo $reset; ?>" ondragstart="return false;">
														<?php else : ?>
														<h1 style="font:300 32px/90px <?php echo $helvetica; ?>; <?php echo $reset; ?>"><?php bloginfo( 'name' ); ?></h1>
														<?php endif; ?>
													</a>
												</td>

												<?php foreach( $social as $name => $url ) : ?>
												<td style="width:26px; margin:0; padding:0 4px;">
													<a href="<?php echo $url; ?>" style="width:26px; height:26px; display:block; <?php echo $reset; ?>" target="_blank">
														<img src="<?php echo get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/images/email/social/' . $name . '.png'; ?>" alt="<?php echo $name; ?>" width="26" height="26" style="<?php echo $reset; ?>" ondragstart="return false;">
													</a>
												</td>
												<?php endforeach; ?>

											</tr>
										</tbody>
									</table>
								</td>
								<td style="width:20px; display:block; <?php echo $reset; ?>">&nbsp;</td>
							</tr>

						</thead>