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
						<tfoot style="<?= $reset ?>" role="rowgroup">
							<tr style="height:60px; <?= $reset ?>">
								<td style="width:20px; display:block; <?= $reset; ?>">&nbsp;</td>
								<td style="<?= $reset ?>">

									<table style="border-collapse:collapse; width:560px; margin:0 auto; padding:0; line-height:0; border:none;" cellpadding="0" cellspacing="0">
										<tbody style="<?= $reset ?>" role="rowgroup">
											<tr style="<?= $reset ?>">
												<td style="<?= $reset ?>">&nbsp;</td>

												<?php foreach( $social as $network => $url ) : ?>
												<td style="width:26px; margin:0; padding:0 6px;">
													<a href="<?= $url ?>" style="margin:0 12px; width:26px; height:26px; display:block; <?= $reset ?>" role="link" target="_blank">
														<img src="<?= get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/images/email/social/' . $network . '.png' ?>" alt="<?= $network ?>" width="26" height="26" role="img" style="<?= $reset ?>" ondragstart="return false;">
													</a>
												</td>
												<?php endforeach; ?>

												<td style="<?= $reset ?>">&nbsp;</td>
											</tr>
										</tbody>
									</table>

								</td>
								<td style="width:20px; display:block; <?= $reset ?>">&nbsp;</td>
							</tr>
						</tfoot>

					</table>

				</td>
			</tr>

			<tr style="<?= $reset ?>">
				<td style="width:600px; <?= $reset ?>">
					<p style="line-height: 19px; text-align:center; margin:10px 0 16px 0; padding:0;">Copyright &copy; <?= date('Y') ?>,&nbsp;<a href="<?= home_url() ?>" role="link" style="color:#<?= $color['text_secondary'] ?>; text-decoration:none; <?= $reset ?>"><?php bloginfo( 'blogname' ); ?></a>. All Rights Reserved.</p>
					<?php if( $template_name == 'unsubscribe' ) : ?>
					<p style="line-height:19px; text-align:center; margin:0 0 30px 0; padding:0;">View in <a href="<?= $web_url ?>" role="link" style="color:#<?= $color['text_secondary'] ?>; <?= $reset ?>">Web Browser</a></p>
					<?php else : ?>
					<p style="line-height:19px; text-align:center; margin:8px 0 16px 0; padding:0;">View in <a href="<?= $web_url ?>" role="link" style="color:#<?= $color['text_secondary'] ?>; <?= $reset ?>">Web Browser</a> | <a href="<?= $unsubscribe_url ?>" role="link" style="color:#<?= $color['text_secondary'] ?>; <?= $reset ?>">Unsubscribe</a></p>
					<?php endif; ?>
				</td>
			</tr>

		</tbody>
	</table>

</body>

</html>