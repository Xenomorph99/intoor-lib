<?php
/**
 * Admin view that displays the mailing list stats.
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

?>
<div class="wrap">

	<h2>Mailing List Stats</h2>

	<table class="form-table">
		<tbody>

			<?php foreach( $defaults as $name => $value ) : ?>
			<tr>
				<th scope="row"><?php echo $title = ( !empty( $value[2] ) ) ? $value[2] : ucwords( $name ); ?></th>
				<td style="font-size:1.2em;"><?php echo $stat = get_option( $id . '_' . $name, $value[0] ) ?></td>
			</tr>
			<?php endforeach; ?>

		</tbody>
	</table>

</div><!--.wrap-->