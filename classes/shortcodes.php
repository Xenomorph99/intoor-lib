<?php
/**
 * This model adds shortcodes to the Wordpress admin.
 *
 * NOTE: The shortcodes parameter of the __construct method must contain an
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

class Shortcodes {

	public $shortcodes = array();

	public function __construct( $arr ) {

		// See note above for setting up $arr parameter
		if( !empty( $arr ) && is_array( $arr ) ) :

			$this->shortcodes = $arr;
			$this->wp_hooks();
		
		endif;

	}

	protected function wp_hooks() {

		foreach( $this->shortcodes as $name => $callback ) {

			add_shortcode( $name, $callback );

		}

	}

}