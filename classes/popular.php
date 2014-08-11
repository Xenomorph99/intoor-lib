<?php
/**
 * This model tracks views and likes on posts. (Views are defined as page loads)
 *
 * Required classes: Database, Functions
 *
 * Future:
 * - Add custom meta box to display views and likes
 * - Wordpress admin page to display the top ranking pages - both views and likes
 * - A session cookie to prevent multiple likes/views
 * - Integrate nonce
 * - get_popular() for only a specific post_type
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor
 * @version		Release: 1.0
 */

class Popular {

	public $settings = array(
		'post_type' => array( 'post' ),		// Type of screen(s) on which to track views & likes (post, page)
		'inflate' => false,					// Artificailly inflate initial 'like' count
		'infl_range' => 'mid',				// Range of inflated numbers to be generated 'low' = 0-10, 'mid' = 10-50, 'high' = 50-100, 'ultra' = 100-500, 'custom'
		'infl_min' => 10,					// Custom inflation range min number
		'infl_max' => 50					// Custom inflation range max number
	);

	public static $table = array(
		'name' => 'popular',
		'prefix' => 'pop',
		'version' => '1.0',
		'structure' => array(
			// key => array( db_column_type, encrypted, default_val, form_field_type, options_array( val => display_name ), form_field_label )
			'post_id' => array( 'BIGINT(20)', false, NULL, 'hidden' ),
			'views' => array( 'BIGINT(20)', false, '0', 'hidden' ),
			'likes' => array( 'BIGINT(20)', false, '0', 'hidden' ),
			'infl' => array( 'TINYINT(3)', false, '0', 'hidden' )
		)
	);

	public function __construct( $args ) {

		$this->settings = Functions::merge_array( $args, $this->settings );

		$this->setup_popular_tracking();
		$this->register_meta_boxes();
		$this->wp_hooks();

	}

	protected function setup_popular_tracking() {

		Database::install_table( static::$table );

	}

	public function register_meta_boxes() {

		$popular = array(
			'title' => 'Popularity',
			'post_type' => $this->settings['post_type'],
			'context' => 'side',
			'priority' => 'core',
			'view' => VIEWS_DIR . 'meta-box/popular.php',
			'array' => array( 'inflate' => $this->settings['inflate'] ),
			'table' => static::$table
		);

		new Meta_Box( $popular );

	}

	protected function wp_hooks() {

		// Check for and save database rows (generate inflation numbers)
		add_action( 'save_post', array( &$this, 'get_data' ) );

		// Track page views
		add_action( 'shutdown', array( &$this, 'track_page_view' ) );

		// Delete popular data associated with deleted posts
		add_action( 'before_delete_post', array( &$this, 'delete_popular' ) );

	}

	public function get_data( $save_data = true ) {

		global $post;
		$data = Database::get_row( static::$table, 'post_id', $post->ID );

		if( empty( $data['post_id'] ) ) :

			$data['post_id'] = $post->ID;
			$data['infl'] = ( $this->settings['inflate'] ) ? $this->generate_infl_num() : $data['infl'];
			if( $save_data ) {
				Database::save_data( static::$table, $data );
			}

		endif;

		return $data;

	}

	protected function generate_infl_num() {

		switch( $this->settings['infl_range'] ) {

			case 'low' :
				$num = rand( 0, 10 );
				break;

			case 'mid' :
				$num = rand( 10, 50 );
				break;

			case 'high' :
				$num = rand( 50, 100 );
				break;

			case 'ultra' :
				$num = rand( 100, 500 );
				break;

			case 'custom' :
				$num = rand( $this->settings['infl_min'], $this->settings['infl'] );
				break;

			default :
				$num = 1;
				break;

		}

		return $num;

	}

	protected function set_tracking_variable() {

		global $post;
		$post_types = $this->settings['post_type'];

		if( !WP_DEBUG && !is_admin() && !empty( $post_types ) ) {
			foreach( $post_types as $post_type ) {
				$this->track = ( $post_type == $post->post_type && ( is_single() || is_page() ) ) ? true : $this->track;
			}
		}

	}

	public function track_page_view() {

		$this->set_tracking_variable();

		if( $this->track ) {
			$data = $this->get_data( false );
			$data['views'] = (int)$data['views'] + 1;
			Database::save_data( static::$table, $data );
		}

	}

	public function delete_popular( $post_id ) {

		global $post;
		$status = Database::delete_row( static::$table, 'post_id', $post->ID );

	}

	public static function run_api_action( $action, $post_id ) {

		$resp = array();

		switch( $action ) {

			case 'like' :
				$resp = static::add_page_like( $post_id );
				break;

			default :
				$resp['status'] = 'error';
				$resp['desc'] = 'invalid-action';
				$resp['message'] = 'Defined API action cannot be performed';
				break;

		}

		return $resp;

	}

	protected static function add_page_like( $post_id ) {

		$resp = array();

		// Scrub out invalid post_id's
		if( preg_match( '/^[0-9]+$/', $post_id ) ) :

			$data = Database::get_row( static::$table, 'post_id', $post_id );
			$data['likes'] = (int)$data['likes'] + 1;

			if( !empty( $data['post_id'] ) ) :

				Database::save_data( static::$table, $data );
				$resp['status'] = 'success';
				$resp['desc'] = 'submitted';
				$resp['message'] = 'Thanks for liking the page!';
			
			else :

				$resp['status'] = 'error';
				$resp['desc'] = 'page-not-found';
				$resp['message'] = 'The page you liked cannot be found.';
			
			endif;

		else :

			$resp['status'] = 'error';
			$resp['desc'] = 'invalid-format';
			$resp['message'] = 'The submitted post ID does not match the required format.';

		endif;

		return $resp;

	}

	public static function get_likes( $infl = true ) {

		global $post;
		$data = Database::get_row( static::$table, 'post_id', $post->ID );
		$likes = ( $infl && !empty( $data['post_id'] ) ) ? (int)$data['likes'] + (int)$data['infl'] : (int)$data['likes'];
		return $likes;

	}

	public static function get_popular( $count = 10, $inc_views = true, $inc_likes = true, $random = false ) {

		// Get data
		$data = Database::get_results( static::$table, array( 'post_id', 'views', 'likes' ) );
		$row_count = count( $data );

		// Sort data into array
		$views = array();
		$likes = array();
		foreach( $data as $key => $val ) {
			$views[$val['post_id']] = $val['views'];
			$likes[$val['post_id']] = $val['likes'];
		}

		// Sort the arrays (greatest to least)
		arsort( $views );
		arsort( $likes );

		// Save list of post ID's 
		$views_list = array();
		$likes_list = array();
		foreach( $views as $key => $val ) {
			array_push( $views_list, $key );
		}
		foreach( $likes as $key => $val ) {
			array_push( $likes_list, $key );
		}

		// Create final merged list of post ID's to return
		$popular = array();
		if( $inc_views && $inc_likes ) :

			for( $i = 0; $i < ( $count * 2 ); $i++ ) {
				if( !in_array( $likes_list[$i], $popular ) && $i < count( $likes_list ) ) {
					array_push( $popular, $likes_list[$i] );
				}
				if( !in_array( $views_list[$i], $popular ) && $i < count( $views_list ) ) {
					array_push( $popular, $views_list[$i] );
				}
			}
			$popular = array_slice( $popular, 0, $count );

		elseif( $inc_views ) :

			$popular = array_slice( $views_list, 0, $count );

		elseif( $inc_likes ) :

			$popular = array_slice( $likes_list, 0, $count );

		endif;

		if( $random ) {
			shuffle( $popular );
		}

		return $popular;

	}

}