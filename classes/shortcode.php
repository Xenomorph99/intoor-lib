<?php
/**
 * This model creates and adds shortcodes to Wordpress.
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

class Shortcode {

	public $args = array(
		'name' => '',			// Name of the shortcode
		'callback' => ''		// If the call back is a method contained within a class this must be an array.  ie. array( 'class', 'method' )
	);

	public function __construct( $args ) {

		$this->args = wp_parse_args( $args, $this->args );
		$this->wp_hooks();

	}

	protected function wp_hooks() {

		add_shortcode( $this->args['name'], $this->args['callback'] );

	}

}