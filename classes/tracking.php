<?php
/**
 * This model manages site-wide tracking.
 *
 * Note: The session_start() PHP function must appear BEFORE the <html> tag in the header
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
		
		session_start();

		$this->install_tables();
		$this->setup_admin_menus();
		$this->setup_session_params();
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

	protected function setup_session_params() {

		$params = static::get_params();

		foreach( $params as $key => $default ) {
			$_SESSION[$key] = ( isset( $_GET[$key] ) ) ? $_GET[$key] : $default;
		}

	}

	public static function get_params() {

		$params = array();
		$data = Database::get_results( static::$table );
		foreach( $data as $row => $arr ) {
			$params[$arr['param']] = $arr['value'];
		}
		return $params;

	}

	public static function get_query_string() {

		$s = '';
		$count = 0;

		foreach( $_SESSION as $key => $value ) {
			$s .= ( $count == 0 ) ? "?$key=$value" : "&$key=$value";
			$count++;
		}

		return $s;

	}

	public static function query_string() {

		echo static::get_query_string();

	}

	public static function append_params( $url = '' ) {

		$new_url = '';

		if( !empty( $url ) ) :

			if( strpos( $url, '?' ) ) :

				$s = '';
				$params = $_SESSION;
				$url_arr = explode( '?', $url );
				$query_arr = explode( '&', $url_arr[1] );
				$arr = array();
				foreach( $query_arr as $param ) {
					$param = explode( '=', $param );
					$arr[$param[0]] = $param[1];
				}
				$params = $arr + $params;
				$count = 0;
				foreach( $params as $key => $value ) {
					$s .= ( $count == 0 ) ? "?$key=$value" : "&$key=$value";
					$count++;
				}
				$new_url = $url_arr[0] . $s;

			else :

				$new_url = $url . static::get_query_string();

			endif;

		endif;

		return $new_url;

	}

}