<?php
/**
 * This model creates and adds shortcodes to Wordpress.
 *
 * Note: The shortcodes parameter of the __construct method must contain an
 * associative array of 'name' => 'callback'.  If callbacks are methods within
 * a class they must be in the format of array( Class, 'method_name' );
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

class Shortcode {

	public $shortcode = array();

	public function __construct( $arr ) {

		if( !empty( $arr ) && is_array( $arr ) ) :

			$this->shortcode = $arr;
			$this->wp_hooks();
		
		endif;

	}

	protected function wp_hooks() {

		foreach( $this->shortcode as $name => $callback ) {
			add_shortcode( $name, $callback );
		}

	}

}