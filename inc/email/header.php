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
	} else {
		$social[$network] = Social::get_social_media_url( $network );
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
	<title><?= $subject . ' | ' . get_bloginfo( 'blogname' ) ?></title>
</head>

<body style="color:#<?= $color['text_secondary'] ?>; font:300 13px/19px <?= $helvetica ?>; background-color:#<?= $color['body'] ?>; <?= $reset ?>" role="document">

	<table style="border-collapse:collapse; width:600px; margin:0 auto; padding:0; border:none; line-height:0;" cellpadding="0" cellspacing="0">
		<tbody style="<?= $reset ?>" role="rowgroup">

			<tr style="<?= $reset ?>">
				<td style="width:600px; <?= $reset ?>">
					<?php if( $template_name == 'unsubscribe' ) : ?>
					<p style="line-height:19px; text-align:center; margin:8px 0 16px 0; padding:0;">View in <a href="<?= $web_url ?>" role="link" style="color:#<?= $color['text_secondary'] ?>; <?= $reset ?>">Web Browser</a></p>
					<?php else : ?>
					<p style="line-height:19px; text-align:center; margin:8px 0 16px 0; padding:0;">View in <a href="<?= $web_url ?>" role="link" style="color:#<?= $color['text_secondary'] ?>; <?= $reset ?>">Web Browser</a> | <a href="<?= $unsubscribe_url ?>" role="link" style="color:#<?= $color['text_secondary'] ?>; <?= $reset ?>">Unsubscribe</a></p>
					<?php endif; ?>
				</td>
			</tr>

			<tr style="<?= $reset ?>">
				<td style="width:600px; <?= $reset ?>">

					<table style="color:#<?= $color['text_primary'] ?>; background-color:#<?= $color['container'] ?>; border-collapse:collapse; width:600px; margin:0 auto; padding:0; line-height:0; border:none;" cellpadding="0" cellspacing="0">

						<thead style="<?= $reset ?>" role="rowgroup">

							<tr class="email-header" style="<?= $reset ?>">
								<td style="width:20px; display:block; <?= $reset ?>">&nbsp;</td>
								<td style="<?= $reset ?>">
									<table style="border-collapse:collapse; width:560px; border:none; margin:0 auto; padding:0; line-height:0;" cellpadding="0" cellspacing="0">
										<tbody style="<?= $reset ?>" role="rowgroup">
											<tr style="height:90px; <?= $reset ?>">

												<?php if( empty( $social ) ) : ?>
												<td id="logo" style="text-align:center; <?= $reset ?>">
												<?php else : ?>
												<td id="logo" style="<?= $reset ?>">
												<?php endif; ?>
													<a href="<?php bloginfo( 'url' ); ?>" role="link" style="display:inline-block; <?= $reset ?>">
														<?php if( get_option( $prefix . 'logo' ) !== '' ) : ?>
														<img src="<?= get_option( $prefix . 'logo' ) ?>" alt="<?php bloginfo( 'name' ); ?>" width="<?= get_option( $prefix . 'logo_width' ) ?>" height="90" role="img" style="<?= $reset ?>" ondragstart="return false;">
														<?php else : ?>
														<h1 role="heading" aria-level="1" style="font:300 32px/90px <?= $helvetica ?>; <?= $reset ?>"><?php bloginfo( 'name' ); ?></h1>
														<?php endif; ?>
													</a>
												</td>

												<?php foreach( $social as $network => $url ) : ?>
												<td style="width:26px; margin:0; padding:0 4px;">
													<a href="<?= $url ?>" style="width:26px; height:26px; display:block; <?= $reset ?>" role="link" target="_blank">
														<img src="<?= get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/images/email/social/' . $network . '.png' ?>" alt="<?= $network ?>" width="26" height="26" role="img" style="<?= $reset ?>" ondragstart="return false;">
													</a>
												</td>
												<?php endforeach; ?>

											</tr>
										</tbody>
									</table>
								</td>
								<td style="width:20px; display:block; <?= $reset ?>">&nbsp;</td>
							</tr>

						</thead>