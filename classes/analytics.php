<?php
/**
 * This model controls all functionality associated with Google
 * Analytics.
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

class Analytics {

	public $settings = array(
		'live_url' => array( '', 'text', 'Live Site URL', NULL, 'example.com' ),
		'account_id' => array( '', 'text', 'Account ID', NULL, 'AA-00000000-0' ),
		'display_features' => array( '0', 'checkbox', 'Include Display Features' ),
		'enhanced_link_attribution' => array( '0', 'checkbox', 'Include Enhanced Link Attribution' )
	);

	public function __construct() {

		$this->setup_admin_menus();
		$this->wp_hooks();

	}

	protected function setup_admin_menus() {

		$args = array(
			'title' => 'Google Analytics',
			'menu_title' => 'Google Analytics',
			'defaults' => $this->settings
		);

		new Admin_Menu( $args );

	}

	protected function wp_hooks() {

		// Add Google Analytics
		add_action( 'wp_head', array( &$this, 'register_analytics' ) );

	}

	public function register_analytics() {

		include_once INTOOR_VIEWS_DIR . 'analytics.php';

	}

}