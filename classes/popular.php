<?php
/**
 * This model tracks views and likes on posts. (Views are defined as page loads)
 *
 * Required classes: Database, Functions, Social
 *
 * Future:
 * - Wordpress admin page to display the top ranking pages - both views and likes
 * - A session cookie to prevent multiple likes/views (integrate nonce)
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

class Popular {

	public $args = array(
		'post_type' => array( 'post' ),		// Type of screen(s) on which to track views & likes (post, page, custom_post_type)
		'inflate' => false,					// Artificailly inflate initial 'like' count
		'infl_range' => 'mid',				// Range of inflated numbers to be generated 'low' = 0-10, 'mid' = 10-50, 'high' = 50-100, 'ultra' = 100-500, 'custom'
		'infl_min' => 10,					// Custom inflation range min number
		'infl_max' => 50					// Custom inflation range max number
	);

	public static $table = array(
		'name' => 'popular',
		'prefix' => 'pop',
		'version' => '1.3',
		'structure' => array(
			// key => array( db_column_type, encrypted, default_val, form_field_type, options_array( val => display_name ), form_field_label )
			'post_id' => array( 'BIGINT(20)', false, NULL, 'hidden' ),
			'views' => array( 'BIGINT(20)', false, '0', 'hidden' ),
			'likes' => array( 'BIGINT(20)', false, '0', 'hidden' ),
			'infl' => array( 'TINYINT(3)', false, '0', 'hidden' )
		)
	);

	public function __construct( $args ) {

		$this->args = Functions::merge_array( $args, $this->args );

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
			'post_type' => $this->args['post_type'],
			'context' => 'side',
			'priority' => 'core',
			'view' => INTOOR_VIEWS_DIR . 'meta-box/popular.php',
			'array' => array( 'inflate' => $this->args['inflate'] ),
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

	public function get_data() {

		global $post;
		extract( $this->args );

		if( in_array( $post->post_type, $post_type ) ) :

			$data = Database::get_row( static::$table, 'post_id', $post->ID );

			if( empty( $data['post_id'] ) ) :

				$data['post_id'] = $post->ID;
				$data['infl'] = ( $this->args['inflate'] ) ? $this->generate_infl_num() : $data['infl'];

			endif;

			return Database::save_data( static::$table, $data );

		endif;

	}

	protected function generate_infl_num() {

		switch( $this->args['infl_range'] ) {

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
				$num = rand( $this->args['infl_min'], $this->args['infl_max'] );
				break;

			default :
				$num = 1;
				break;

		}

		return $num;

	}

	protected function set_tracking_variable() {

		global $post;
		$post_types = $this->args['post_type'];

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
				$resp['message'] = 'Defined API action cannot be performed.';
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

	public static function likes( $infl = true ) {

		echo static::get_likes( $infl );

	}

	public static function get_popular( $custom_args = array() ) {

		$args = array(
			'count' => 10,						// Number of posts to retrieve
			'post_type' => array( 'post' ),		// This is where you would also include custom post types
			'category' => 0,					// Filter by categories (only one post type allowed if filtering by categories)
			'include_views' => true,			// Include views when assessing popularity
			'include_likes' => true,			// Include likes when assessing popularity
			'include_shares' => true,			// Include social media shares when assessing popularity
			'random' => false,					// Randomize the returned posts
			'offset' => 0						// Offset the posts returned - maybe you want top 10-20 not 1-10
		);

		$args = Functions::merge_array( $custom_args, $args );
		extract( $args );

		// Database variables
		global $wpdb;
		$p = $wpdb->prefix;
		$popular_table = $p . static::$table['name'];
		$social_table = $p . Social::$table['name'];
		$posts_table = $p . 'posts';
		$term_table = $p . 'term_relationships';

		// MySQL variables
		$sql = "";
		$n = "\n";
		$share_sum = "($social_table.facebook_shares + $social_table.twitter_shares + $social_table.google_shares + $social_table.linkedin_shares + $social_table.pinterest_shares + $social_table.reddit_shares) AS shares";
		$select_post_type = ( !empty( $post_type ) ) ? ", $posts_table.post_type" : "";
		$select_category = ( !empty( $category ) ) ? ", $term_table.term_taxonomy_id AS cat_id" : "";

		// Filter views, likes, and shares
		if( $include_shares ) : // 'Social' class is required in order to include shares

			if( $include_likes ) :
				if( $include_views ) {
					$select = "$popular_table.views, $popular_table.likes, $share_sum"; // shares, likes, views
					$order_by = "ORDER BY shares DESC, likes DESC";
				} else {
					$select = "$popular_table.likes, $share_sum";
					$order_by = "ORDER BY shares DESC, likes DESC"; // shares, likes
				}
			else :
				if( $include_views ) {
					$select = "$popular_table.views, $share_sum";
					$order_by = "ORDER BY shares DESC, views DESC"; // shares, views
				} else {
					$select = "$share_sum";
					$order_by = "ORDER BY shares DESC"; // shares
				}
			endif;

		else :

			if( $include_likes ) :
				if( $include_views ) {
					$select = "$popular_table.views, $popular_table.likes"; // likes, views
					$order_by = "ORDER BY likes DESC, views DESC";
				} else {
					$select = "$popular_table.likes"; // likes
					$order_by = "ORDER BY likes DESC";
				}
			else :
				$select = "$popular_table.views"; // views
				$order_by = "ORDER BY views DESC";
			endif;

		endif;

		// Filter post types and categories
		if( !empty( $post_type ) ) :

			if( !empty( $category ) ) :
				// filter by post_type and category
				$where = "WHERE $posts_table.post_type = '" . $post_type[0] . "' AND $term_table.term_taxonomy_id = $category";
			else :
				// filter by post_type
				$where = "WHERE ";
				$i = 1;
				foreach( $post_type as $type ) {
					if( $i < count( $post_type ) ) {
						$where .= "$posts_table.post_type = '$type' OR ";
					} else {
						$where .= "$posts_table.post_type = '$type'";
					}
					$i++;
				}
			endif;

		endif;
		
		// Build MySQL Statement
		$sql = "SELECT $popular_table.post_id, " . $select . $select_post_type . $select_category . $n;
		$sql .= "FROM $popular_table" . $n;
		$sql .= ( $include_shares ) ? "LEFT JOIN $social_table ON $social_table.post_id = $popular_table.post_id" . $n : "";
		$sql .= ( !empty( $post_type ) ) ? "LEFT JOIN $posts_table ON $posts_table.ID = $popular_table.post_id" . $n : "";
		$sql .= ( !empty( $category ) ) ? "LEFT JOIN $term_table ON $term_table.object_id = $popular_table.post_id" . $n : "";
		$sql .= ( !empty( $where ) ) ? $where . $n : "";
		$sql .= $order_by;

		// Retrieve data from database
		$data = $wpdb->get_results( $wpdb->prepare( $sql, array() ), ARRAY_A );

		// Build and return response
		$popular = array();
		foreach($data as $row) {
			array_push( $popular, $row['post_id'] );
		}
		$popular = ( $random ) ? shuffle( $popular ) : $popular;
		return array_slice( $popular, $offset, ( $count + $offset ) );

	}

	public static function popular( $custom_args = array() ) {

		echo static::get_popular( $custom_args );

	}

}