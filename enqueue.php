<?php
/**
 * Enqueue required JavaScript and CSS files
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

// Include required JavaScript files
function intoor_enqueue_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'intoor_lib', get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/js/min/intoor-lib-min.js', array( 'jquery' ), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'intoor_enqueue_scripts' );

// Include required JavaScript files in Wordpress admin
function intoor_enqueue_admin_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'intoor_admin', get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/js/min/intoor-admin-min.js', array( 'jquery' ), '1.0' );
}
add_action( 'admin_enqueue_scripts', 'intoor_enqueue_admin_scripts' );