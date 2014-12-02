<?php
/**
 * Admin view that displays the mailing list.
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
$data = Database::get_results( $table, array( 'id', 'email', 'status', 'create_date', 'create_time' ) );
$default_view = 'active';
$current_view = ( empty( $_GET['view'] ) ) ? $default_view : $_GET['view'];

$views = [
	'active' => [
		'url' => admin_url() . 'admin.php?page=' . $id,
		'item_count' => 0,
		'empty_message' => 'No Active Emails to Display',
		'actions' => [
			'trash' => 'Move to Trash'
		]
	],
	'trash' => [
		'url' => admin_url() . 'admin.php?page=' . $id . '&view=trash',
		'item_count' => 0,
		'empty_message' => 'No Emails in the Trash',
		'actions' => [
			'active' => 'Restore',
			'delete' => 'Delete Permanently'
		]
	]
];

// Update item_count for each view
foreach( $data as $row => $val ) {
	if( !empty( $val['id'] ) ) {
		$views[$val['status']]['item_count'] = $views[$val['status']]['item_count'] + 1;
	}
}

$table_cols = [
	'num' => '#',
	'email' => 'Email Address',
	'date' => 'Date'
];

?>
<div class="wrap">

	<h2>Mailing List</h2>

	<ul class="subsubsub">
		<?php
			$i = count( $views );
			foreach( $views as $view => $view_data ) {
				if( $view !== 'deleted' ) :

					extract( $view_data );
					$title = ucwords( $view );
					$current = ( $current_view == $view ) ? ' class="current"' : '';
					$spacer = '';

					echo "<li class='$view'><a href='$url'$current>$title<span class='count'>($item_count)</span></a>$spacer</li>";

				endif;
			}
		?>
	</ul>

	<form id="mailing-list-form" action method="GET">

		<input type="hidden" name="page" value="mailing_list">
		<input type="hidden" name="v" value="<?php echo $current_view; ?>">

		<p class="search-box">
			<button type="submit" name="export" id="mailing-list-export-btn" class="button" value="1">Export CSV</button>
		</p><!--.search-box-->

		<div class="tablenav top">
			<div class="alignleft actions bulkactions">
				<select name="action1">
					<?php
						echo "<option value selected='selected'>Bulk Actions</option>";
						foreach( $views[$current_view]['actions'] as $opt_val => $opt_label ) {
							echo "<option value='$opt_val'>$opt_label</option>";
						}
					?>
				</select>
				<input type="submit" id="doaction" class="button action" value="Apply">
			</div><!--.actions-->
		</div><!--.tablenav-->

		<table class="wp-list-table widefat fixed">

			<thead>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column">
						<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
						<input id="cb-select-all-1" type="checkbox">
					</th>
					<?php
						foreach( $table_cols as $col_id => $col_title ) {
							echo "<th scope='col' id='list-$col_id' class='manage-column column-name'>";
							echo "<span>$col_title</span>";
							echo "</th>";
						}
					?>
				</tr>
			</thead>

			<tfoot>
				<tr>
					<th scope="col" class="manage-column column-cb check-column">
						<label class="screen-reader-text" for="cb-select-all-2">Select All</label>
						<input id="cb-select-all-2" type="checkbox">
					</th>
					<?php
						foreach( $table_cols as $col_id => $col_title ) {
							echo "<th scope='col' class='column-name'>";
							echo "<span>$col_title</span>";
							echo "</th>";
						}
					?>
				</tr>
			</tfoot>

			<tbody id="email-list">
				<?php
					extract( $views[$current_view] );
					if( $item_count ) :
						foreach( array_reverse( $data ) as $row_count => $row ) {
							extract( $row );
							if( $status == $current_view ) {
								echo "<tr id='list-item-$id' class='list-item-$id' valign='top'>";
								echo "<th scope='row' class='check-column'>";
								echo "<label class='screen-reader-text' for='cb-select-$id'>Select Mailing List Item</label>";
								echo "<input id='cb-select-$id' type='checkbox' name='ckd[]' value='$id'>";
								echo "</th>";
								echo "<td>$item_count</td>";
								echo "<td><strong>$email</strong></td>";
								echo "<td>" . date( 'm/d/Y', strtotime( $create_date ) ) . " @" . date( 'H:i:s', strtotime( $create_time ) ) . "</td>";
								echo "</tr>";
								$item_count--;
							}
						}
					else :
						echo '<tr><td colspan="' . ( count( $table_cols ) + 1 ) . '">' . $empty_message . '</td></tr>';
					endif;
				?>
			</tbody>

		</table>

		<div class="tablenav bottom">
			<div class="allignleft actions bulkactions">
				<select name="action2">
					<?php
						echo "<option value selected='selected'>Bulk Actions</option>";
						foreach( $views[$current_view]['actions'] as $opt_val => $opt_label ) {
							echo "<option value='$opt_val'>$opt_label</option>";
						}
					?>
				</select>
				<input type="submit" id="doaction2" class="button action" value="Apply">
			</div><!--.actions-->
		</div><!--.tablenav-->

	</form><!--#mailing-list-form-->

</div><!--.wrap-->