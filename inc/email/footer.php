<?php
/**
 * HTML Email Footer.
 *
 * @package     Интоор Library (intoor)
 * @author      Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright   2014 Hazard Media Group LLC
 * @license     MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link        https://github.com/Alekhen/intoor-lib
 * @version     Release: 1.2
 */

if( !defined( 'INTOOR_RESTRICT_ACCESS' ) || !INTOOR_RESTRICT_ACCESS ) { die( 'Unauthorized Access' ); }

?>
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

					</table>

				</td>
			</tr>

			<tr style="<?php echo $reset; ?>">
				<td style="width:600px; <?php echo $reset; ?>">
					<p style="line-height: 19px; text-align:center; margin:10px 0 16px 0; padding:0;">Copyright &copy; <?php echo date("Y"); ?>,&nbsp;<a href="<?php echo home_url(); ?>" style="color:#<?php echo $color['text_secondary']; ?>; text-decoration:none; <?php echo $reset; ?>"><?php bloginfo( 'blogname' ); ?></a>. All Rights Reserved.</p>
					<?php if( $template_name == 'unsubscribe' ) : ?>
					<p style="line-height:19px; text-align:center; margin:0 0 30px 0; padding:0;">View in <a href="<?php echo $web_url; ?>" style="color:#<?php echo $color['text_secondary']; ?>; <?php echo $reset; ?>">Web Browser</a></p>
					<?php else : ?>
					<p style="line-height:19px; text-align:center; margin:8px 0 16px 0; padding:0;">View in <a href="<?php echo $web_url; ?>" style="color:#<?php echo $color['text_secondary']; ?>; <?php echo $reset; ?>">Web Browser</a> | <a href="<?php echo $unsubscribe_url; ?>" style="color:#<?php echo $color['text_secondary']; ?>; <?php echo $reset; ?>">Unsubscribe</a></p>
					<?php endif; ?>
				</td>
			</tr>

		</tbody>
	</table>

</body>

</html>