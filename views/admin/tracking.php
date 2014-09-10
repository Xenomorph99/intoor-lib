<?php
/**
 * Admin view that displays options for customizing tracking.
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

$p = Tracking::$table['prefix'] . '_';
$data = Database::get_results( Tracking::$table );

?>
<div class="wrap">

	<h2>Tracking Parameters</h2>
	<p><em>Definitions:</em> Query strings are one way of transfering data from one website to another.  Query strings are all the numbers, letters, and symbols that follows a question mark (?) at the end of a URL.  Parameters are sets of key/value pairs within query strings (ie. example.com/?key=value).  When a query string contains multiple parameters they are separated by the ampersand (&amp;) symbol (ie. example.com/?key1=value1&key2=value2).</p>
	<p><em>Instructions:</em> Below you have the ability to define the keys and default value pairs that are associated with tracking.  The parameters defined here will be collected and stored in the session tracking cookie when users visit the site.</p>
	<p><em>Note:</em> To delete a parameter simply erase the key and save your changes.</p>

	<form method="post" action="options-general.php?page=tracking">

		<table id="template" style="display:none;">
			<tr>
				<th scope="row">
					<input type="hidden" id="" name="<?php echo $p; ?>id[]" value="">
					<input type="text" id="" name="<?php echo $p; ?>param[]" value="" placeholder="Key">
				</th>
				<td><input type="text" id="" name="<?php echo $p; ?>value[]" value="" placeholder="Default"></td>
			</tr>
		</table><!--#template-->

		<table class="form-table">
			<tbody>

			<?php if( !empty( $data ) ) : ?>
			
				<?php foreach( $data as $row => $value ) : extract( $value ); ?>

				<tr>
					<th scope="row">
						<input type="hidden" id="" name="<?php echo $p; ?>id[]" value="<?php echo $id; ?>">
						<input type="text" id="" name="<?php echo $p; ?>param[]" value="<?php echo $param; ?>" placeholder="Key">
					</th>
					<td><input type="text" id="" name="<?php echo $p; ?>value[]" value="<?php echo $value; ?>" placeholder="Default"></td>
				</tr>

				<?php endforeach; ?>

			<?php else: ?>

				<tr>
					<th scope="row">
						<input type="hidden" id="" name="<?php echo $p; ?>id[]" value="">
						<input type="text" id="" name="<?php echo $p; ?>param[]" value="" placeholder="Key">
					</th>
					<td><input type="text" id="" name="<?php echo $p; ?>value[]" value="" placeholder="Default"></td>
				</tr>

			<?php endif; ?>

			</tbody>
		</table><!--.form-table-->

		<p class="submit">
			<input type="button" name="add-param" id="add-param" class="button" value="+ Add">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
		</p>

	</form>

</div><!--.wrap-->