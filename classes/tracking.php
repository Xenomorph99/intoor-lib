<?php
/**
 * This model manages site-wide tracking.
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

class Tracking {

	public $args = array(
		'ga' => true,					// Include Google Analytics tracking
		'query_params' => true			// Include query parameter tracking
	);

	public $ga_options = array(
		'live_url' => array( '', 'text', 'Live Site URL', NULL, 'example.com' ),
		'account_id' => array( '', 'text', 'Account ID', NULL, 'AA-00000000-0' ),
		'display_features' => array( '0', 'checkbox', 'Include Display Features' ),
		'enhanced_link_attribution' => array( '0', 'checkbox', 'Include Enhanced Link Attribution' )
	);

	public static $table = array(
		'name' => 'tracking',
		'prefix' => 'trk',
		'version' => '1.0',
		'structure' => array(
			'param' => array( 'VARCHAR(255)', false, NULL ),
			'value' => array( 'VARCHAR(255)', false, NULL )
		)
	);

	public function __construct( $args = array() ) {

		$this->args = wp_parse_args( $args, $this->args );

		$this->install_tables();
		$this->setup_admin_menus();
		$this->wp_hooks();

	}

	protected function install_tables() {

		if( $this->args['query_params'] ) {
			Database::install_table( static::$table );
		}

	}

	protected function setup_admin_menus() {

		$ga = array(
			'title' => 'Google Analytics',
			'menu_title' => 'Google Analytics',
			'defaults' => $this->ga_options
		);

		$tracking = array(
			'title' => 'Tracking',
			'menu_title' => 'Tracking',
			'view' => INTOOR_VIEWS_DIR . 'admin/tracking.php',
			'table' => static::$table
		);

		if( $this->args['ga'] ) {
			new Admin_Menu( $ga );
		}

		if( $this->args['query_params'] ) {
			new Admin_Menu( $tracking );
		}

	}

	protected function wp_hooks() {

		if( $this->args['ga'] ) {
			add_action( 'wp_head', array( &$this, 'register_google_analytics' ) );
		}

		if( $this->args['query_params'] ) {
			add_action( 'admin_init', array( &$this, 'save_admin_menu' ) );
		}

	}

	public function register_google_analytics() {

		include_once INTOOR_VIEWS_DIR . 'analytics.php';

	}

	public function save_admin_menu() {

		$data = array();
		$p = static::$table['prefix'] . '_';

		if( !empty( $_POST ) ) :

			for( $i = 1; $i < count( $_POST[$p.'id'] ); $i++ ) {

				$data['id'] = $_POST[$p.'id'][$i];
				$data['param'] = $_POST[$p.'param'][$i];
				$data['value'] = $_POST[$p.'value'][$i];

				if( !empty( $data['param'] ) ) {
					Database::save_data( static::$table, $data );
				} else {
					Database::delete_row( static::$table, 'id', $data['id'] );
				}

			}

		endif;

	}

}