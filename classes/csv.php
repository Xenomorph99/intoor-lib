<?php
/**
 * This model manages the creation of CSV files from data arrays.
 *
 * @package     Интоор Library (intoor)
 * @author      Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright   2014 Hazard Media Group LLC
 * @license     MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link        https://github.com/Alekhen/intoor-lib
 * @version     Release: 1.2
 */

if( !defined( 'INTOOR_RESTRICT_ACCESS' ) || !INTOOR_RESTRICT_ACCESS ) { die( 'Unauthorized Access' ); }

class CSV {

	public $args = [
		'column_names' => true,     // Include column names at the top of each column
		'delimiter' => ',',         // Delimitation character to appear between columns
		'filename' => '',           // Name of the CSV file to be created
		'data' => '',               // Data from which the CSV will be generated
	];

	public function __construct( $args ) {

		$this->args = wp_parse_args( $args, $this->args );
		$this->generate_csv();

	}

	protected function generate_csv() {

		extract( $this->args );

		$csv = fopen( 'php://memory', 'w' );
		$col_names = array();
		$count = 0;

		// Generate CSV lines
		foreach( $data as $line ) {
			if( $column_names && $count < 1 ) {
				foreach( $line as $col_name => $col_value ) {
					array_push( $col_names, $col_name );
				}
				fputcsv( $csv, $col_names, $delimiter );
			}
			fputcsv( $csv, $line, $delimiter );
			$count++;
		}

		// Rewind the CSV file
		fseek( $csv, 0 );

		// Set CSV file headers
		header( 'Content-Type: application/csv' );
		header( 'Content-Disposition: attachement; filename="' . $filename . '.csv"' );
		header( 'Content-Cache: no-cache, must-revalidate' );
		header( 'Pragma: no-cache' );

		// Send the generated CSV to the browser
		fpassthru( $csv );

		exit();

	}

}