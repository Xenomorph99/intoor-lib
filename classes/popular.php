<?php
/**
 * This model tracks views and likes on posts. (Views are defined as page loads)
 *
 * Required classes: Database, Social
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

	public $args = [
		'post_type' => array( 'post' ),		// Type of screen(s) on which to track views & likes (post, page, custom_post_type)
		'track_views' => true,				// Track page views
		'inflate' => false,					// Artificailly inflate initial 'like' count
		'infl_range' => 'mid',				// Range of inflated numbers to be generated 'low' = 0-10, 'mid' = 10-50, 'high' = 50-100, 'ultra' = 100-500, 'custom'
		'infl_min' => 10,					// Custom inflation range min number
		'infl_max' => 50					// Custom inflation range max number
	];

	public static $table = [
		'name' => 'popular',
		'prefix' => 'pop',
		'version' => '1.0',
		'structure' => [
			'post_id' => [
				'sql' => 'BIGINT(20)',
				'type' => 'hidden'
			],
			'views' => [
				'sql' => 'BIGINT(20)',
				'type' => 'hidden',
				'default' => '0'
			],
			'likes' => [
				'sql' => 'BIGINT(20)',
				'type' => 'hidden',
				'default' => '0'
			],
			'infl' => [
				'sql' => 'TINYINT(3)',
				'type' => 'hidden',
				'default' => '0'
			]
		]
	];

	public function __construct( $args ) {

		$this->args = wp_parse_args( $args, $this->args );

		$this->setup_popular_tracking();
		$this->register_meta_boxes();
		$this->wp_hooks();

	}

	protected function setup_popular_tracking() {

		Database::install_table( static::$table );
		API::new_key( 'popular' );

	}

	public function register_meta_boxes() {

		$popular = [
			'title' => 'Popularity',
			'post_type' => $this->args['post_type'],
			'context' => 'side',
			'priority' => 'core',
			'view' => INTOOR_VIEWS_DIR . 'meta-box/popular.php',
			'array' => array( 'inflate' => $this->args['inflate'] ),
			'table' => static::$table
		];

		new Meta_Box( $popular );

	}

	protected function wp_hooks() {

		// Check for and save database rows (generate inflation numbers)
		add_action( 'save_post', array( &$this, 'create_row' ) );

		// Track page views
		add_action( 'shutdown', array( &$this, 'track_page_view' ) );

		// Delete popular data associated with deleted posts
		add_action( 'before_delete_post', array( &$this, 'delete_popular' ) );

	}

	public function create_row() {

		global $post;
		extract( $this->args );

		if( !empty( $post ) && in_array( $post->post_type, $post_type ) ) :

			$data = Database::get_row( static::$table, 'post_id', $post->ID );
			if( empty( $data['post_id'] ) ) {
				$data['post_id'] = $post->ID;
				$data['infl'] = Functions::numgen( $this->args['infl_range'], $this->args['infl_min'], $this->args['infl_max'] );
				Database::save_data( static::$table, $data );
			}

		endif;

	}

	public function track_page_view() {

		global $post;
		extract( $this->args );

		if( !WP_DEBUG && !is_admin() && !empty( $post ) && !empty( $post_type ) && ( is_single() || is_page() ) ) :

			if( $track_views && in_array( $post->post_type, $post_type ) ) :

				$data = Database::get_row( static::$table, 'post_id', $post->ID );
				if( !empty( $data['post_id'] ) ) {
					$data['views'] = (int)$data['views'] + 1;
					Database::save_data( static::$table, $data );
				}
			
			endif;

		endif;

	}

	public function delete_popular() {
		
		if( isset( $_GET ) && !empty( $_GET['post'] ) ) :

			if( is_array( $_GET['post'] ) ) :

				foreach( $_GET['post'] as $post_id ) {
					Database::delete_row( static::$table, 'post_id', $post_id );
				}

			elseif( is_string( $_GET['post'] ) ) :

				Database::delete_row( static::$table, 'post_id', $_GET['post'] );

			endif;

		endif;

	}

	public static function run_api_action( $action, $arr = array() ) {

		$resp = array();

		$resp['status'] = 'error';
		$resp['type'] = 'invalid-action';
		$resp['message'] = 'Defined API action cannot be performed';

		switch( $action ) {

			case 'like':
				$resp = static::add_page_like( $arr['post_id'] );
				break;

		}

		return $resp;

	}

	protected static function add_page_like( $post_id ) {

		$resp = array();

		$resp['status'] = 'error';
		$resp['type'] = 'invalid-format';
		$resp['message'] = 'The submitted post ID does not match the required format';

		// Scrub out invalid post_id's
		if( preg_match( '/^[0-9]+$/', $post_id ) ) :

			$data = Database::get_row( static::$table, 'post_id', $post_id );
			$data['likes'] = (int)$data['likes'] + 1;

			if( Database::save_data( static::$table, $data ) ) :

				$resp['status'] = 'success';
				$resp['type'] = 'success';
				$resp['message'] = 'The like was successfully recorded';

			else :

				$resp['status'] = 'error';
				$resp['type'] = 'database-error';
				$resp['message'] = 'An error occured connecting to the database. Try again later.';

			endif;

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

		$args = [
			'count' => 10,						// Number of posts to retrieve
			'post_type' => array( 'post' ),		// This is where you would also include custom post types
			'category' => 0,					// Filter by categories (only one post type allowed if filtering by categories)
			'include_views' => true,			// Include views when assessing popularity
			'include_likes' => true,			// Include likes when assessing popularity
			'include_shares' => true,			// Include social media shares when assessing popularity
			'random' => false,					// Randomize the returned posts
			'offset' => 0,						// Offset the posts returned - maybe you want top 10-20 not 1-10
			'inflated' => false					// Include inflated numbers
		];

		$args = wp_parse_args( $custom_args, $args );
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
		$share_sum = ( $inflated )
			? "($social_table.facebook_shares + $social_table.facebook_infl + $social_table.twitter_shares + $social_table.twitter_infl + $social_table.google_shares + $social_table.google_infl + $social_table.linkedin_shares + $social_table.linkedin_infl + $social_table.pinterest_shares + $social_table.pinterest_infl + $social_table.reddit_shares + $social_table.reddit_infl) AS shares"
			: "($social_table.facebook_shares + $social_table.twitter_shares + $social_table.google_shares + $social_table.linkedin_shares + $social_table.pinterest_shares + $social_table.reddit_shares) AS shares";
		$select_category = ( !empty( $category ) ) ? ", $term_table.term_taxonomy_id AS cat_id" : "";

		// Filter views, likes, and shares
		if( $include_shares ) : // 'Social' class is required in order to include shares

			if( $include_likes ) :
				if( $include_views ) {
					$select = ( $inflated ) // shares, likes, views
						? "$popular_table.views, ($popular_table.likes + $popular_table.infl) AS likes, $share_sum"
						: "$popular_table.views, $popular_table.likes, $share_sum";
					$order_by = "ORDER BY shares DESC, likes DESC";
				} else {
					$select = ( $inflated ) // shares, likes
						? "($popular_table.likes + $popular_table.infl) AS likes, $share_sum"
						: "$popular_table.likes, $share_sum";
					$order_by = "ORDER BY shares DESC, likes DESC";
				}
			else :
				if( $include_views ) { // shares, views
					$select = "$popular_table.views, $share_sum";
					$order_by = "ORDER BY shares DESC, views DESC";
				} else {
					$select = "$share_sum"; // shares
					$order_by = "ORDER BY shares DESC";
				}
			endif;

		else :

			if( $include_likes ) :
				if( $include_views ) {
					$select = ( $inflated ) // likes, views
						? "$popular_table.views, ($popular_table.likes + $popular_table.infl) AS likes"
						: "$popular_table.views, $popular_table.likes";
					$order_by = "ORDER BY likes DESC, views DESC";
				} else {
					$select = ( $inflated ) // likes
						? "($popular_table.likes + $popular_table.infl) AS likes"
						: "$popular_table.likes";
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
				$where = "WHERE $posts_table.post_status = 'publish' AND $posts_table.post_type = '" . $post_type[0] . "' AND $term_table.term_taxonomy_id = $category";
			else :
				// filter by post_type
				$where = "WHERE $posts_table.post_status = 'publish' AND ";
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
		$sql = "SELECT $popular_table.post_id, " . $select . $select_category . $n;
		$sql .= "FROM $popular_table" . $n;
		$sql .= ( $include_shares ) ? "LEFT JOIN $social_table ON $social_table.post_id = $popular_table.post_id" . $n : "";
		$sql .= ( !empty( $post_type ) ) ? "LEFT JOIN $posts_table ON $posts_table.ID = $popular_table.post_id" . $n : "";
		$sql .= ( !empty( $category ) ) ? "LEFT JOIN $term_table ON $term_table.object_id = $popular_table.post_id" . $n : "";
		$sql .= ( !empty( $where ) ) ? $where . $n : "WHERE $posts_table.post_status = 'publish'";
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

}