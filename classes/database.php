<?php
/**
 * This model contains custom methods to interact with the Wordpress database.
 *
 * Required classes: Encryption
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor
 * @version		Release: 1.0
 */

class Database {

	public static function install_table( $arr ) {

		if( !empty( $arr['name'] ) && !empty( $arr['version'] ) && !empty( $arr['structure'] ) ) {

			global $wpdb;
			$table = $wpdb->prefix . $arr['name'];
			$has_table = $wpdb->get_var( "SHOW TABLES LIKE '$table'" );

			if( !$has_table ) {
				add_option( $arr['name'] . '_table_version', $arr['version'] );
				$sql = static::create_table_sql( $arr );
				require_once( ABSPATH . 'wp-admin/upgrade-functions.php' );
				dbDelta( $sql );
				return true;
			} else {
				return static::upgrade_table( $arr );
			}

		}

		return false;

	}

	public static function delete_table( $table_name ) {

		global $wpdb;
		$table = $wpdb->prefix . $table_name;
		$has_table = $wpdb->get_var( "SHOW TABLES LIKE '$table'" );

		if( $has_table ) {
			$wpdb->query( "DROP TABLE IF EXISTS $table" );
			return true;
		}

		return false;

	}

	public static function upgrade_table( $arr ) {

		if( !empty( $arr['name'] ) && !empty( $arr['version'] ) && !empty( $arr['structure'] ) ) {

			global $wpdb;
			$table = $wpdb->prefix . $arr['name'];
			$installed_version = get_option( $arr['name'] . '_table_version' );

			if( $installed_version != $arr['version'] ) {
				$sql = static::create_table_sql( $arr );
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
				update_option( $arr['name'] . '_table_version', $arr['version'] );
				return true;
			}

		}

		return false;

	}

	public static function delete_column( $table_name, $column_name ) {

		global $wpdb;
		$table = $wpdb->prefix . $table_name;
		$has_column = $wpdb->get_var( "SHOW COLUMNS FROM $table LIKE '$column_name'" );

		if( $has_column ) {
			$wpdb->query( "ALTER TABLE $table DROP COLUMN $column_name" );
			return true;
		}

		return false;
		
	}

	protected static function create_table_sql( $arr ) {

		global $wpdb;
		$sql = "CREATE TABLE " . $wpdb->prefix . $arr['name'] . " (\n";
		$sql .= "id BIGINT(20) NOT NULL AUTO_INCREMENT,\n";

		foreach( $arr['structure'] as $name => $value ) {
			$default = ( isset( $value[2] ) && $value[0] !== 'LONGTEXT' ) ? " DEFAULT '" . $value[2] . "'" : "";
			$sql .= $name . " " . $value[0] . " NOT NULL" . $default . ",\n";
		}

		$sql .= "UNIQUE KEY id (id) )";
		return $sql;

	}

	public static function get_results( $arr, $col_arr = NULL, $match_arr = NULL ) {

		if( !empty( $arr['name'] ) && !empty( $arr['structure'] ) ) {

			global $wpdb;
			$table = $wpdb->prefix . $arr['name'];
			$columns = '*';
			$match = '';
			$data = array();

			if( isset( $col_arr ) ) {
				$columns = '';
				$max = count( $col_arr );
				$count = 1;
				foreach( $col_arr as $col ) {
					if( $max > 1 ) {
						if( $count == $max ) {
							$columns .= $col;
						} else {
							$columns .= $col . ',';
						}
						$count++;
					} else {
						$columns = $col;
					}
				}
			}

			if( isset( $match_arr ) ) {
				foreach( $match_arr as $match_name => $match_value ) {
					$match .= $match_name . ' = ' . $match_value;
				}
			}

			$db = ( isset( $match_arr ) )
				? $wpdb->get_results( $wpdb->prepare( "SELECT $columns FROM $table WHERE $match", array() ), ARRAY_A )
				: $wpdb->get_results( $wpdb->prepare( "SELECT $columns FROM $table", array() ), ARRAY_A );

			if( !empty( $db ) ) {
				$count = 0;
				foreach( $db as $row ) {
					$value_array = array();
					foreach( $row as $col => $col_value) {
						$value = ( $arr['structure'][$col][1] ) ? Encryption::decrypt( $col_value ) : html_entity_decode( $col_value );
						$value = stripslashes( $value );
						$value_array[$col] = $value;
					}
					$data[$count] = $value_array;
					$count++;
				}
			}

			return $data;

		}

		return array();

	}

	public static function get_row( $arr, $unique_key, $unique_value, $encrypted = false ) {

		if( !empty( $arr['name'] ) && !empty( $arr['structure'] ) ) {

			global $wpdb;
			$table = $wpdb->prefix . $arr['name'];
			$data = array();
			$unique_value = ( $encrypted ) ? Encryption::encrypt( $unique_value ) : $unique_value;
			$db = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE $unique_key = '$unique_value'", array() ), ARRAY_A );

			if( !empty( $db ) ) {
				foreach( $db as $col => $col_value ) {
					$value = ( $arr['structure'][$col][1] ) ? Encryption::decrypt( $col_value ) : html_entity_decode( $col_value );
					$value = stripslashes( $value );
					$data[$col] = $value;
				}
			} else {
				foreach( $arr['structure'] as $col => $col_value ) {
					$data[$col] = ( isset( $col_value[2] ) ) ? $col_value[2] : '';
				}
			}

			return $data;

		}

		return array();

	}

	public static function save_data( $arr, $data ) {

		if( !empty( $data['id'] ) ) {

			return static::update_row( $arr, 'id', $data['id'], $data );

		} else {

			return static::insert_row( $arr, $data );

		}

	}

	public static function insert_row( $arr, $data ) {

		if( !empty( $arr['name'] ) && !empty( $arr['structure'] ) && isset( $data ) ) {

			global $wpdb;
			$table = $wpdb->prefix . $arr['name'];
			$row = array();

			foreach( $data as $col => $col_value ) {
				$value = ( $arr['structure'][$col][1] ) ? Encryption::encrypt( $col_value ) : htmlentities( $col_value );
				$row[$col] = $value;
			}

			$wpdb->insert( $table, $row );
			return true;

		}

		return false;

	}

	public static function update_row( $arr, $unique_key, $unique_value, $data, $encrypted = false ) {

		if( !empty( $arr['name'] ) && !empty( $arr['structure'] ) && isset( $data ) ) {

			global $wpdb;
			$table = $wpdb->prefix . $arr['name'];
			$unique_value = ( $encrypted ) ? Database::encrypt( $unique_value ) : $unique_value;
			$row = array();

			foreach( $data as $col => $col_value ) {
				$value = ( $arr['structure'][$col][1] ) ? Encryption::encrypt( $col_value ) : htmlentities( $col_value );
				$row[$col] = $value;
			}

			$wpdb->update( $table, $row, array( $unique_key => $unique_value ) );
			return true;

		}

		return false;

	}

	public static function delete_row( $arr, $unique_key, $unique_value, $encrypted = false ) {

		global $wpdb;
		$table = $wpdb->prefix . $arr['name'];
		$unique_value = ( $encrypted ) ? Encryption::encrypt( $unique_value ) : $unique_value;
		$has_row = $wpdb->get_var( "SELECT * FROM $table WHERE $unique_key = '$unique_value'" );

		if( $has_row ) {
			$wpdb->delete( $table, array( $unique_key => $unique_value ) );
			return true;
		}

		return false;

	}

}