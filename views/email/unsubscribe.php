<?php
/**
 * HTML email template sent to users when they unsubscribe from the mailing list.
 *
 * @package     Интоор Library (intoor)
 * @author      Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright   2014 Hazard Media Group LLC
 * @license     MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link        https://github.com/Alekhen/intoor-lib
 * @version     Release: 1.2
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
require_once dirname( dirname( dirname( __FILE__ ) ) ) . '/config.php';
$template_name = 'unsubscribe';

if( !defined( 'INTOOR_RESTRICT_ACCESS' ) || !INTOOR_RESTRICT_ACCESS ) { die( 'Unauthorized Access' ); }

require_once INTOOR_EMAIL_HEADER; ?>

<tbody style="<?php echo $reset; ?>" role="rowgroup">

	<?php if( get_option( $prefix . 'unsubscribe_banner' ) !== '' ) : ?>
	<tr class="banner" style="<?php echo $reset; ?>">
		<td style="background-color:#<?php echo $color['banner']; ?>; line-height:0; <?php echo $reset; ?>" colspan="3">
			<img src="<?php echo get_option( $prefix . 'unsubscribe_banner' ); ?>" alt="" width="600" height="<?php echo get_option( $prefix . 'unsubscribe_banner_height' ); ?>" role="presentation" style="<?php echo $reset; ?>" ondragstart="return false;">
		</td>
	</tr>
	<?php endif; ?>

	<tr style="<?php echo $reset; ?>">
		<td style="width:20px; display:block; <?php echo $reset; ?>">&nbsp;</td>
		<td style="<?php echo $reset; ?>">
			<table style="border-collapse:collapse; width: 560px; border:none; margin:0 auto; padding:0; line-height:0;" cellpadding="0" cellspacing="0">
				<tbody style="<?php echo $reset; ?>" role="rowgroup">

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
										<a href="<?php echo get_permalink( $post->ID ); ?>" style="display:block; <?php echo $reset; ?>" role="link">
										<?php if( has_post_thumbnail( $post->ID ) ) : ?>
											<?php echo get_the_post_thumbnail( $post->ID, 'thumbnail' ); ?>
										<?php else : ?>
											<img src="<?php echo get_template_directory_uri(); ?>/images/email/default-thumb.jpg" alt="<?php echo $post->post_title; ?>" width="150" height="150" style="<?php echo $reset; ?>" role="img" ondragstart="return false;">
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
									<h2 role="heading" aria-level="1" style="font:100 20px/24px <?php echo $helvetica; ?>; margin:0 0 4px 0; padding:0;"><a href="<?php echo get_permalink( $post->ID ); ?>" role="link" style="color:#<?php echo $color['text_heading']; ?>; text-decoration:none; <?php echo $reset; ?>"><?php echo $post->post_title; ?></a></h2>
									<p style="font:100 14px/20px <?php echo $helvetica; ?>; margin:0 0 12px 0; padding:0;"><?php echo $post->post_excerpt; ?>&nbsp;&nbsp;<a href="<?php echo get_permalink( $post->ID ); ?>" role="link" style="color:#<?php echo $color['text_link']; ?>; font-style:italic; <?php echo $reset; ?>">read more</a></p>
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

<?php require_once INTOOR_EMAIL_FOOTER;