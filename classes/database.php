<?php
/**
 * This model contains custom methods to interact with the Wordpress database.
 *
 * Required classes: Encryption
 *
 * @package     Интоор Library (intoor)
 * @author      Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright   2014 Hazard Media Group LLC
 * @license     MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link        https://github.com/Alekhen/intoor-lib
 * @version     Release: 1.2
 */

if( !defined( 'INTOOR_RESTRICT_ACCESS' ) || !INTOOR_RESTRICT_ACCESS ) { die( 'Unauthorized Access' ); }

class Database {

	public static function install_table( $table ) {

		if( !empty( $table['name'] ) && !empty( $table['version'] ) && !empty( $table['structure'] ) ) :

			global $wpdb;
			$table_name = $wpdb->prefix . $table['name'];
			$has_table = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table_name ) );

			if( !$has_table ) :

				add_option( $table['name'] . '_table_version', $table['version'] );
				$sql = static::create_table_sql( $table );
				require_once( ABSPATH . 'wp-admin/upgrade-functions.php' );
				dbDelta( $sql );
				return true;

			else :

				return static::upgrade_table( $table );
			
			endif;

		endif;

		return false;

	}

	protected static function create_table_sql( $table ) {

		global $wpdb;
		$sql = "CREATE TABLE " . $wpdb->prefix . $table['name'] . " (\n";
		$sql .= "id BIGINT(20) NOT NULL AUTO_INCREMENT,\n";

		foreach( $table['structure'] as $col => $args ) {
			$default = ( isset( $args['default'] ) && $args['sql'] !== 'LONGTEXT' ) ? " DEFAULT '" . $args['default'] . "'" : "";
			$sql .= $col . " " . $args['sql'] . " NOT NULL" . $default . ",\n";
		}

		$sql .= "UNIQUE KEY id (id) )";
		return $sql;

	}

	public static function upgrade_table( $table ) {

		if( !empty( $table['name'] ) && !empty( $table['version'] ) && !empty( $table['structure'] ) ) :

			$installed_version = get_option( $table['name'] . '_table_version' );

			if( $installed_version != $table['version'] ) :

				$sql = static::create_table_sql( $table );
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
				update_option( $table['name'] . '_table_version', $table['version'] );
				return true;

			endif;

		endif;

		return false;

	}

	public static function delete_table( $table_name ) {

		global $wpdb;
		$table_name = $wpdb->prefix . $table_name;
		return $wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS %s", $table_name ) );

	}

	public static function delete_column( $table_name, $column_name ) {

		global $wpdb;
		$table_name = $wpdb->prefix . $table_name;
		$has_column = $wpdb->get_var( $wpdb->prepare( "SHOW COLUMNS FROM %s LIKE '%s'", $table_name, $column_name ) );

		if( $has_column ) :

			return $wpdb->query( $wpdb->prepare( "ALTER TABLE %s DROP COLUMN %s", $table_name, $column_name ) );
		
		endif;

		return false;
		
	}

	public static function get_results( $table, $col_arr = NULL, $match_arr = NULL ) {

		global $wpdb, $post;
		$data = array();

		if( !empty( $table['name'] ) && !empty( $table['structure'] ) ) :

			$table_name = $wpdb->prefix . $table['name'];
			$key = !empty( $table['key'] ) ? $table['key'] : NULL;
			$columns = '*';
			$match = '';

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
					if( !is_array( $match_value ) ) {
						$match .= "$match_name = $match_value";
					} else {
						$match_value_count = 1;
						foreach( $match_value as $match_value_single ) {
							$match .= ( $match_value_count < count( $match_value ) ) ? "$match_name = $match_value_single OR " : "$match_name = $match_value_single";
							$match_value_count++;
						}
					}
				}
			}

			$db = ( isset( $match_arr ) )
				? $wpdb->get_results( $wpdb->prepare( "SELECT $columns FROM $table_name WHERE $match", array() ), ARRAY_A )
				: $wpdb->get_results( $wpdb->prepare( "SELECT $columns FROM $table_name", array() ), ARRAY_A );

			if( !empty( $db ) ) :

				$count = 0;
				foreach( $db as $row ) {
					$value_array = array();
					foreach( $row as $col => $col_value) {
						$value = ( $table['structure'][$col]['encrypt'] ) ? Encryption::decrypt( $col_value, $key ) : $col_value;
						$value = stripslashes( html_entity_decode( $value ) );
						$value_array[$col] = $value;
					}
					$data[$count] = $value_array;
					$count++;
				}

			else :

				$defaults = array();
				foreach( $table['structure'] as $col => $args ) {
					if( in_array( $col, $col_arr ) || empty( $col_arr ) ) {
						if( $col === 'post_id' ) {
							$defaults[$col] = $post->ID;
						} else {
							$defaults[$col] = !empty( $args['default'] ) ? $args['default'] : '';
						}
					}
				}
				$data[0] = $defaults;

			endif;

		endif;

		return $data;

	}

	public static function get_row( $table, $unique_key, $unique_value ) {

		global $wpdb;
		$data = array();

		if( !empty( $table['name'] ) && !empty( $table['structure'] ) ) :

			$table_name = $wpdb->prefix . $table['name'];
			$key = !empty( $table['key'] ) ? $table['key'] : NULL;
			$unique_value = ( $table['structure'][$unique_key]['encrypt'] ) ? Encryption::encrypt( $unique_value, $key ) : $unique_value;
			$db = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE $unique_key = '$unique_value'", array() ), ARRAY_A );

			if( !empty( $db ) ) :

				foreach( $db as $col => $col_value ) {
					$value = ( $table['structure'][$col]['encrypt'] ) ? Encryption::decrypt( $col_value, $key ) : $col_value;
					$value = stripslashes( html_entity_decode( $value ) );
					$data[$col] = $value;
				}

			else :

				foreach( $table['structure'] as $col => $args ) {
					$data[$col] = isset( $args['default'] ) ? $args['default'] : '';
				}

			endif;

		endif;

		return $data;

	}

	public static function get_posts( $post_type = 'post', $reverse = false ) {

		global $wpdb;
		$table_name = $wpdb->prefix . 'posts';
		$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE post_type = '$post_type'", array() ), ARRAY_A );
		$posts = array();
		
		foreach( $data as $row ) {
			$name = ( $reverse ) ? $row['post_title'] : $row['ID'];
			$value = ( $reverse ) ? $row['ID'] : $row['post_title'];
			if( $row['post_status'] !== 'auto-draft' ) {
				$posts[$name] = $value;
			}
		}

		return $posts;

	}

	public static function save_data( $table, $data ) {

		if( !empty( $data['id'] ) ) :

			return static::update_row( $table, 'id', $data['id'], $data );

		else :

			return static::insert_row( $table, $data );

		endif;

	}

	public static function insert_row( $table, $data ) {

		if( !empty( $table['name'] ) && !empty( $table['structure'] ) && isset( $data ) ) :

			global $wpdb;
			$table_name = $wpdb->prefix . $table['name'];
			$key = !empty( $table['key'] ) ? $table['key'] : NULL;
			$row = array();

			foreach( $data as $col => $col_value ) {
				$value = ( $table['structure'][$col]['encrypt'] ) ? Encryption::encrypt( $col_value, $key ) : $col_value;
				$row[$col] = htmlentities( $value );
			}

			$wpdb->insert( $table_name, $row );
			return true;

		endif;

		return false;

	}

	public static function update_row( $table, $unique_key, $unique_value, $data ) {

		if( !empty( $table['name'] ) && !empty( $table['structure'] ) && isset( $data ) ) :

			global $wpdb;
			$table_name = $wpdb->prefix . $table['name'];
			$key = !empty( $table['key'] ) ? $table['key'] : NULL;
			$unique_value = ( $table['structure'][$unique_key]['encrypt'] ) ? Encryption::encrypt( $unique_value, $key ) : $unique_value;
			$row = array();

			foreach( $data as $col => $col_value ) {
				$value = ( $table['structure'][$col]['encrypt'] ) ? Encryption::encrypt( $col_value, $key ) : $col_value;
				$row[$col] = htmlentities( $value );
			}

			$wpdb->update( $table_name, $row, array( $unique_key => $unique_value ) );
			return true;

		endif;

		return false;

	}

	public static function delete_row( $table, $unique_key, $unique_value ) {

		if( !empty( $table['name'] ) && !empty( $table['structure'] ) ) :

			global $wpdb;
			$table_name = $wpdb->prefix . $table['name'];
			$key = !empty( $table['key'] ) ? $table['key'] : NULL;
			$unique_value = ( $table['structure'][$unique_key]['encrypt'] ) ? Encryption::encrypt( $unique_value, $key ) : $unique_value;
			$has_row = $wpdb->get_var( $wpdb->prepare( "SELECT * FROM $table_name WHERE $unique_key = '%s'", $unique_value ) );

			if( $has_row ) :

				$wpdb->delete( $table_name, array( $unique_key => $unique_value ) );
				return true;

			endif;

		endif;

		return false;

	}

	public static function date_count( $table, $date_column, $interval = NULL ) {

		if( !empty( $table['name'] ) && !empty( $table['structure'] ) ) :

			global $wpdb;
			$table_name = $wpdb->prefix . $table['name'];

			switch( $interval ) {
				case 'day':
					$interval = 'DAY';
					break;
				case 'week':
					$interval = 'WEEK';
					break;
				case 'month':
					$interval = 'MONTH';
					break;
			}

			return !empty( $interval )
				? $wpdb->query( $wpdb->prepare( "SELECT * FROM $table_name WHERE $date_column > DATE_SUB(NOW(), INTERVAL 1 $interval);", array() ) )
				: $wpdb->query( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name", array() ) );

		endif;

		return false;

	}

}