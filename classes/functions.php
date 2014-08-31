<?php
/**
 * This model contains custom functions that perform a variety of operations
 * throughout the Intoor library.
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

class Functions {

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

}