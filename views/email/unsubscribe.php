<?php
/**
 * HTML Email template sent to users that unsubscribe from the mailing list.
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor
 * @version		Release: 1.0
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
require_once dirname( dirname( dirname( __FILE__ ) ) ) . '/config.php';

$prefix = 'mailing_list_settings_';
$reset = 'margin:0; padding:0;';
$helvetica = "'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif";
$settings = array(
	'sender' => '',
	'reply_to' => '',
	'recipient' => '',
	'subject' => '',
	'template' => '',
	'data' => ''
);

if( isset( $this ) ) :
	$settings = Functions::merge_array( $this->settings, $settings );
elseif( !empty( $_GET['sender'] ) && !empty( $_GET['reply_to'] ) && !empty( $_GET['recipient'] ) && !empty( $_GET['subject'] ) && !empty( $_GET['template'] ) ) :
	$settings = Functions::merge_array( $_GET, $settings );
else :
	exit( 'You do not have permission to view this page.' );
endif;

extract( $settings );

$web_url = get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/views/email/unsubscribe.php?' . http_build_query( $settings );

$social = array();
if( get_option( $prefix . 'facebook' ) !== '' ) { $social['facebook'] = get_option( $prefix . 'facebook' ); }
if( get_option( $prefix . 'twitter' ) !== '' ) { $social['twitter'] = get_option( $prefix . 'twitter' ); }
if( get_option( $prefix . 'pinterest' ) !== '' ) { $social['pinterest'] = get_option( $prefix . 'pinterest' ); }
if( get_option( $prefix . 'google' ) !== '' ) { $social['google'] = get_option( $prefix . 'google' ); }
if( get_option( $prefix . 'instagram' ) !== '' ) { $social['instagram'] = get_option( $prefix . 'instagram' ); }
if( get_option( $prefix . 'linkedin' ) !== '' ) { $social['linkedin'] = get_option( $prefix . 'linkedin' ); }
if( get_option( $prefix . 'youtube' ) !== '' ) { $social['youtube'] = get_option( $prefix . 'youtube' ); }
if( get_option( $prefix . 'tumblr' ) !== '' ) { $social['tumblr'] = get_option( $prefix . 'tumblr' ); }

$color = array(
	'body' => 'f0f0f0',
	'container' => 'ffffff',
	'banner' => 'bdbdbd',
	'text_heading' => '58595b',
	'text_primary' => '58595b',
	'text_secondary' => 'bcbec0',
	'text_link' => 'f48c7f'
);
if( get_option( $prefix . 'color_body' ) !== '' ) { $color['body'] = get_option( $prefix . 'color_body' ); }
if( get_option( $prefix . 'color_container' ) !== '' ) { $color['container'] = get_option( $prefix . 'color_container' ); }
if( get_option( $prefix . 'color_banner' ) !== '' ) { $color['banner'] = get_option( $prefix . 'color_banner' ); }
if( get_option( $prefix . 'color_text_heading' ) !== '' ) { $color['text_heading'] = get_option( $prefix . 'color_text_heading' ); }
if( get_option( $prefix . 'color_text_primary' ) !== '' ) { $color['text_primary'] = get_option( $prefix . 'color_text_primary' ); }
if( get_option( $prefix . 'color_text_secondary' ) !== '' ) { $color['text_secondary'] = get_option( $prefix . 'color_text_secondary' ); }
if( get_option( $prefix . 'color_text_link' ) !== '' ) { $color['text_link'] = get_option( $prefix . 'color_text_link' ); }

$posts = wp_get_recent_posts( array( 'numberposts' => 3 ), OBJECT );
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
					<p style="line-height:19px; text-align:center; margin:8px 0 16px 0; padding:0;">View in <a href="<?php echo $web_url; ?>" style="color:#<?php echo $color['text_secondary']; ?>; <?php echo $reset; ?>">Web Browser</a></p>
				</td>
			</tr>

			<tr style="<?php echo $reset; ?>">
				<td style="width:600px; <?php echo $reset; ?>">

					<table style="color:#<?php echo $color['text_primary']; ?>; background-color:#<?php echo $color['container']; ?>; border-collapse:collapse; width:600px; margin:0 auto; padding:0; line-height:0; border:none;" cellpadding="0" cellspacing="0">

						<?php if( !empty( $posts ) ) : ?>
						<thead style="<?php echo $reset; ?>">
						<?php endif; ?>

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

							<?php if( get_option( $prefix . 'unsubscribe_banner' ) !== '' ) : ?>
							<tr class="banner" style="<?php echo $reset; ?>">
								<td style="background-color:#<?php echo $color['banner']; ?>; line-height:0; <?php echo $reset; ?>" colspan="3">
									<img src="<?php echo get_option( $prefix . 'unsubscribe_banner' ); ?>" alt="" width="600" height="<?php echo get_option( $prefix . 'unsubscribe_banner_height' ); ?>" style="<?php echo $reset; ?>" ondragstart="return false;">
								</td>
							</tr>
							<?php endif; ?>

						<?php if( !empty( $posts ) ) : ?>
						</thead>
						<?php endif; ?>

						<?php if( !empty( $posts ) ) : ?>
						<tbody style="<?php echo $reset; ?>">
							<tr style="<?php echo $reset; ?>">
								<td style="width:20px; display:block; <?php echo $reset; ?>">&nbsp;</td>
								<td style="<?php echo $reset; ?>">
									<table style="border-collapse:collapse; width: 560px; border:none; margin:0 auto; padding:0; line-height:0;" cellpadding="0" cellspacing="0">
										<tbody style="<?php echo $reset; ?>">

											<?php foreach( $posts as $post ) : ?>
												<?php if( !empty( $post->post_excerpt ) ) : ?>
													<?php if( $count == count( $posts ) && get_option( $prefix . 'subscribe_banner' ) == '' && $count == 1 ) : ?>
													<tr style="border-top:1px solid #d4d6d9; border-bottom:1px solid #d4d6d9; <?php echo $reset; ?>">
													<?php elseif( $count == count( $posts ) && get_option( $prefix . 'unsubscribe_banner' ) == '' ) : ?>
													<tr style="border-top:1px solid #d4d6d9; <?php echo $reset; ?>">
													<?php elseif( $count == 1 ) : ?>
													<tr style="border-bottom:1px solid #d4d6d9; <?php echo $reset; ?>">
													<?php else : ?>
													<tr style="<?php echo $reset; ?>">
													<?php endif; ?>

														<td style="width:20px; display:block; <?php echo $reset; ?>">&nbsp;</td>

														<td style='line-height:0; margin:0; padding:20px 0; vertical-align:top; width:150px;'>
															<span style="display:block; overflow:hidden; width:150px; height:150px; <?php echo $reset; ?> -webkit-border-radius:75px; -moz-border-radius:75px; -o-border-radius:75px; -ms-border-radius:75px; border-radius:75px;">
																<a href="<?php echo get_permalink( $post->ID ); ?>" style="display:block; <?php echo $reset; ?>">
																<?php if( has_post_thumbnail( $post->ID ) ) : ?>
																	<?php echo get_the_post_thumbnail( $post->ID, 'thumbnail' ); ?>
																<?php else : ?>
																	<img src="<?php echo get_template_directory_uri(); ?>/images/email/default-thumb.jpg" alt="<?php echo $post->post_title; ?>" width="150" height="150" style="<?php echo $reset; ?>" ondragstart="return false;">
																<?php endif; ?>
																</a>
															<span>
														</td>

														<td style="width:20px; display:block; <?php echo $reset; ?>">&nbsp;</td>

														<?php if( $count > 1 ) : ?>
														<td style="margin:0; padding:20px 0; vertical-align:top; width:350px; border-bottom:1px solid #d4d6d9;">
														<?php else : ?>
														<td style="margin:0; padding:20px 0; vertical-align:top; width:350px;">
														<?php endif; ?>
															<h2 style="font:100 20px/24px <?php echo $helvetica; ?>; margin:0 0 4px 0; padding:0;"><a href="<?php echo get_permalink( $post->ID ); ?>" style="color:#<?php echo $color['text_heading']; ?>; text-decoration:none; <?php echo $reset; ?>"><?php echo $post->post_title; ?></a></h2>
															<p style="font:100 14px/20px <?php echo $helvetica; ?>; margin:0 0 12px 0; padding:0;"><?php echo $post->post_excerpt; ?>&nbsp;&nbsp;<a href="<?php echo get_permalink( $post->ID ); ?>" style="color:#<?php echo $color['text_link']; ?>; font-style:italic; <?php echo $reset; ?>">read more</a></p>
														</td>

														<td style="width:20px; display:block; <?php echo $reset; ?>">&nbsp;</td>

													</tr>
												<?php endif; ?>
											<?php $count--; ?>
											<?php endforeach; ?>

										</tbody>
									</table>
								</td>
								<td style="width:20px; display:block; <?php echo $reset; ?>">&nbsp;</td>
							</tr>
						</tbody>

						<tfoot style="<?php echo $reset; ?>">
							<tr style="height:60px; <?php echo $reset; ?>">
								<td style="width:20px; display:block; <?php echo $reset; ?>">&nbsp;</td>
								<td style="<?php echo $reset; ?>">

									<table style="border-collapse:collapse; width:560px; margin:0 auto; padding:0; line-height:0; border:none;" cellpadding="0" cellspacing="0">
										<tbody style="<?php echo $reset; ?>">
											<tr style="<?php echo $reset; ?>">
												<td style="<?php echo $reset; ?>">&nbsp;</td>

												<?php foreach( $social as $name => $url ) : ?>
												<td style="width:26px; margin:0; padding:0 6px;">
													<a href="<?php echo $url; ?>" style="margin:0 12px; width:26px; height:26px; display:block; <?php echo $reset; ?>" target="_blank">
														<img src="<?php echo get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/images/email/social/' . $name . '.png'; ?>" alt="<?php echo $name; ?>" width="26" height="26" style="<?php echo $reset; ?>" ondragstart="return false;">
													</a>
												</td>
												<?php endforeach; ?>

												<td style="<?php echo $reset; ?>">&nbsp;</td>
											</tr>
										</tbody>
									</table>

								</td>
								<td style="width:20px; display:block; <?php echo $reset; ?>">&nbsp;</td>
							</tr>
						</tfoot>
						<?php endif; ?>

					</table>

				</td>
			</tr>

			<tr style="<?php echo $reset; ?>">
				<td style="width:600px; <?php echo $reset; ?>">
					<p style="line-height: 19px; text-align:center; margin:10px 0 16px 0; padding:0;">Copyright &copy; <?php echo date("Y"); ?>,&nbsp;<a href="<?php echo home_url(); ?>" style="color:#<?php echo $color['text_secondary']; ?>; text-decoration:none; <?php echo $reset; ?>"><?php bloginfo( 'blogname' ); ?></a>. All Rights Reserved.</p>
					<p style="line-height: 19px; text-align:center; margin:0 0 30px 0; padding:0;">View in <a href="<?php echo $web_url; ?>" style="color:#<?php echo $color['text_secondary']; ?>; <?php echo $reset; ?>">Web Browser</a></p>
				</td>
			</tr>

		</tbody>
	</table>

</body>

</html>