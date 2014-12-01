<?php
/**
 * Admin view that displays the mailing list stats.
 *
 * @package     Интоор Library (intoor)
 * @author      Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright   2014 Hazard Media Group LLC
 * @license     MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link        https://github.com/Alekhen/intoor-lib
 * @version     Release: 1.2
 */

if( !defined( 'INTOOR_RESTRICT_ACCESS' ) || !INTOOR_RESTRICT_ACCESS ) { die( 'Unauthorized Access' ); }

extract( $this->args );

$data = Database::get_results( $table, array( 'status' ) );

$active_count = 0;
$inactive_count = 0;
$deleted_count = 0;
$total_count = 0;
$month_average = 0;
$month_count = 0;
$month_unsub_count = 0;
$week_average = 0;
$week_count = 0;
$week_unsub_count = 0;
$day_average = 0;
$day_count = 0;
$day_unsub_count = 0;

foreach( $data as $row => $val ) {
	$total_count++;
	switch( $val['status'] ) {
		case 'active':
			$active_count++;
			break;
		case 'trash':
			$inactive_count++;
			break;
		case 'deleted':
			$deleted_count++;
			break;
	}
}

?>
<div class="wrap">

	<h2>Mailing List Stats</h2>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">Total Subscribers</th>
				<td style="font-size:1.2em;"><?= $total_count; ?></td>
			</tr>
			<tr>
				<th scope="row">Active Subscribers</th>
				<td style="font-size:1.2em;"><?= $active_count; ?></td>
			</tr>
			<tr>
				<th scope="row">Inactive Subscribers</th>
				<td style="font-size:1.2em;"><?= $inactive_count; ?></td>
			</tr>
			<tr>
				<th scope="row">Total Unsubscribed</th>
				<td style="font-size:1.2em;"><?= $deleted_count; ?></td>
			</tr>
		</tbody>
	</table>
<?php /*
	<br>
	<hr>

	<h3><em>This Month</em></h3>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">Average Monthly Subscribers</th>
				<td style="font-size:1.2em;"><?= $month_average; ?></td>
			</tr>
			<tr>
				<th scope="row">New Subscribers</th>
				<td style="font-size:1.2em;"><?= $month_count; ?></td>
			</tr>
			<tr>
				<th scope="row">Unsubscribers</th>
				<td style="font-size:1.2em;"><?= $month_unsub_count; ?></td>
			</tr>
		</tbody>
	</table>

	<br>
	<hr>

	<h3><em>This Week</em></h3>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">Average Weekly Subscribers</th>
				<td style="font-size:1.2em;"><?= $week_average; ?></td>
			</tr>
			<tr>
				<th scope="row">New Subscribers</th>
				<td style="font-size:1.2em;"><?= $week_count; ?></td>
			</tr>
			<tr>
				<th scope="row">Unsubscribers</th>
				<td style="font-size:1.2em;"><?= $week_unsub_count; ?></td>
			</tr>
		</tbody>
	</table>

	<br>
	<hr>

	<h3><em>Today</em></h3>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">Average Daily Subscribers</th>
				<td style="font-size:1.2em;"><?= $day_average; ?></td>
			</tr>
			<tr>
				<th scope="row">New Subscribers</th>
				<td style="font-size:1.2em;"><?= $day_count; ?></td>
			</tr>
			<tr>
				<th scope="row">Unsubscribers</th>
				<td style="font-size:1.2em;"><?= $day_unsub_count; ?></td>
			</tr>
		</tbody>
	</table> */ ?>

</div><!--.wrap-->