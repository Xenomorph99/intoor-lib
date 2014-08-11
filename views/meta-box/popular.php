<?php
/**
 * Custom meta box to display popularity (likes & views).
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor
 * @version		Release: 1.0
 */

$inflated = $array['inflate'];

if( !empty( $data[0] ) ) : extract( $data[0] ); ?>

	<p>Views: <strong style="font-size: 1.2em;"><?php echo $views; ?></strong></p>

	<?php if( $inflated ) : ?>
		<p>Likes: <strong style="font-size: 1.2em;"><?php echo $likes; ?></strong> <small>(+<?php echo $infl; ?> = <?php echo ( $likes + $infl ); ?>)</small></p>
	<?php else : ?>
		<p>Likes: <strong style="font-size: 1.2em;"><?php echo $likes; ?></strong></p>
	<?php endif; ?>

<?php else : ?>

	<p>No Data</p>

<?php endif;