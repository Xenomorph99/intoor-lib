<?php
/**
 * This model contains custom functions that perform a variety of operations
 * throughout the Intoor library.
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor
 * @version		Release: 1.0
 */

class Functions {

	/**
	 * Take the values of a new array and replace corresponding
	 * values of an old array with the updated values ignoring any
	 * values in the new array that do not correspond directly
	 * with values in the old array.
	 */
	public static function merge_array( $new_array, $old_array ) {

		foreach( $new_array as $name => $value ) {
			$old_array[$name] = $value;
		}

		return $old_array;

	}

	/** 
	 * Convert strings like "Hello World" to "hello_world".
	 */
	public static function str_smash( $string, $symbol = '_' ) {

		$s = strtolower( $string );
		$s = preg_replace("/[^a-z0-9_\s-]/", "", $s);
		$s = preg_replace("/[\s-]+/", " ", $s);
		$s = preg_replace("/[\s_]/", $symbol, $s);

		return $s;

	}

	/**
	 * Creates an associative array of image names and URL paths
	 * withing a specified image directory.
	 */
	public static function image_options( $dir, $blank_default = true, $reverse = false ) {

		$a = ( $blank_default ) ? array( '' => 'Select:' ) : array();
		$images = scandir( IMAGES_PATH . $dir );

		foreach( $images as $img ) {
			if( !is_dir( $img ) ) {
				$name = explode( '.', $img );
				$name = $name[0];
				$url = get_template_directory_uri() . "/images/$dir/$img";
				if( $reverse ) {
					$a[$name] = $url;
				} else {
					$a[$url] = $name;
				}
			}
		}

		return $a;

	}

	/**
	 * Returns the URL for an image in a different image directory
	 * with the same name.
	 */
	public static function image_from_other_dir( $img_path, $dir_name ) {

		$img_array = explode( '/', $img_path );
		$name = count( $img_array ) - 1;
		$img_name = $img_array[$name];
		return get_template_directory_uri() . "/images/$dir_name/$img_name";

	}

	/**
	 *
	 */
	public static function post_data_array( $post_type, $reverse = false ) {

		global $wpdb;
		$table = $wpdb->prefix . 'posts';
		$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE post_type = '$post_type'", array() ), ARRAY_A );
		$a = array();
		
		foreach( $data as $row ) {
			$name = $row['post_title'];
			$val = $row['ID'];
			if( $reverse ) {
				$a[$name] = $val;
			} else {
				$a[$val] = $name;
			}
		}

		return $a;

	}

}