<?php
/**
 * This model controls the interaction with social networks.
 *
 * Required classes: Database
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

class Social {

	public $args = array(
		'meta_box' => true,					// Include the social share custom meta box
		'post_type' => array( 'post' ),		// Type of screen(s) on which to display the social share custom meta box (post, page, etc)
		'inflate' => false,					// Artificially inflate initial 'share' count
		'infl_range' => 'mid',				// Range of inflated numbers to be generated 'low' = 0-10, 'mid' = 10-50, 'high' = 50-100, 'ultra' = 100-500, 'custom'
		'infl_min' => 10,					// Custom inflation range min number
		'infl_max' => 50					// Custom inflation range max number
	);

	public $settings = array(
		// key => array( default_value, field_type, label, options, placeholder )
		'facebook' => array( '', 'url', 'Facebook Link', NULL, 'http://facebook.com' ),
		'twitter' => array( '', 'url', 'Twitter Link', NULL, 'http://twitter.com' ),
		'google' => array( '', 'url', 'Google+ Link', NULL, 'http://plus.google.com' ),
		'pinterest' => array( '', 'url', 'Pinterest Link', NULL, 'http://pinterest.com' ),
		'instagram' => array( '', 'url', 'Instagram Link', NULL, 'http://instagram.com' ),
		'youtube' => array( '', 'url', 'YouTube Link', NULL, 'http://youtube.com' ),
		'linkedin' => array( '', 'url', 'LinkedIn Link', NULL, 'http://linkedin.com' ),
		'tumblr' => array( '', 'url', 'Tumblr Link', NULL, 'http://tumblr.com' ),
		'vine' => array( '', 'url', 'Vine Link', NULL, 'http://vine.co' ),
		'vimeo' => array( '', 'url', 'Vimeo Link', NULL, 'http://vimeo.com' ),
		'soundcloud' => array( '', 'url', 'SoundCloud Link', NULL, 'http://soundcloud.com' ),
		'flickr' => array( '', 'url', 'Flickr Link', NULL, 'http://flickr.com' ),
		'github' => array( '', 'url', 'GitHub Link', NULL, 'http://github.com' ),
		'behance' => array( '', 'url', 'Behance Link', NULL, 'http://behance.net' ),
		'dribbble' => array( '', 'url', 'Dribbble Link', NULL, 'http://dribbble.com' ),
		'deviantart' => array( '', 'url', 'DeviantART Link', NULL, 'http://deviantart.com' ),
		'yelp' => array( '', 'url', 'Yelp Link', NULL, 'http://yelp.com' ),
		'foursquare' => array( '', 'url', 'Foursquare Link', NULL, 'http://foursquare.com' ),
		'meetup' => array( '', 'url', 'Meetup Link', NULL, 'http://meetup.com' ),
		'myspace' => array( '', 'url', 'Myspace Link', NULL, 'http://myspace.com' ),
		'reddit' => array( '', 'url', 'Reddit Link', NULL, 'http://reddit.com' ),
		'weibo' => array( '', 'url', 'Weibo Link', NULL, 'http://weibo.com' ),
		'renren' => array( '', 'url', 'Renren Link', NULL, 'http://renren.com' )
	);

	public static $table = array(
		'name' => 'social',
		'prefix' => 'soc',
		'version' => '1.0',
		'structure' => array(
			// key => array( db_column_type, encrypted, default_val, form_field_type, options_array( val => display_name ), form_field_label )
			'post_id' => array( 'BIGINT(20)', false, NULL, 'hidden' ),
			'facebook_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'facebook_shares' => array( 'BIGINT(20)', false, '0' ),
			'facebook_infl' => array( 'TINYINT(3)', false, '0' ),
			'twitter_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'twitter_shares' => array( 'BIGINT(20)', false, '0' ),
			'twitter_infl' => array( 'TINYINT(3)', false, '0' ),
			'google_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'google_shares' => array( 'BIGINT(20)', false, '0' ),
			'google_infl' => array( 'TINYINT(3)', false, '0' ),
			'pinterest_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'pinterest_shares' => array( 'BIGINT(20)', false, '0' ),
			'pinterest_infl' => array( 'TINYINT(3)', false, '0' ),
			'linkedin_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'linkedin_shares' => array( 'BIGINT(20)', false, '0' ),
			'linkedin_infl' => array( 'TINYINT(3)', false, '0' ),
			'reddit_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'reddit_shares' => array( 'BIGINT(20)', false, '0' ),
			'reddit_infl' => array( 'TINYINT(3)', false, '0' )
		)
	);

	public static $share_url = array(
		'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=',
		'twitter' => 'https://twitter.com/intent/tweet?url=',
		'google' => 'https://plus.google.com/share?url=',
		'pinterest' => 'https://pinterest.com/pin/create/button/?url=',
		'linkedin' => 'https://www.linkedin.com/shareArticle?mini=true&url=',
		'reddit' => 'http://www.reddit.com/submit?url='
	);

	public function __construct( $args ) {

		$this->args = wp_parse_args( $args, $this->args );
		foreach( $this->settings as $key => $val ) {
			$val[2] = '<span class="social-icon" style="width:30px; height:30px; line-height:30px; display:inline-block; overflow:hidden; float:left;">' . file_get_contents( INTOOR_IMAGES_DIR . 'social/icon_' . $key . '.svg' ) . '</span><span style="line-height:30px; margin-left:12px; display:inline-block; float:left;">' . $val[2] . '</span>'; 
			$this->settings[$key] = $val;
		}

		$this->setup_social_media();
		$this->setup_admin_menus();
		$this->register_meta_boxes();
		$this->wp_hooks();

	}

	protected function setup_social_media() {

		if( !get_option( 'social_inflated' ) ) {
			add_option( 'social_inflated', $this->args['inflate'] );
		}

		Database::install_table( static::$table );

	}

	protected function setup_admin_menus() {

		$social = array(
			'title' => 'Social Media Settings',
			'menu_title' => 'Social Media',
			'defaults' => $this->settings
		);

		new Admin_Menu( $social );

	}

	protected function register_meta_boxes() {

		if( $this->args['meta_box'] ) :

			$social = array(
				'title' => 'Social Sharing Overrides',
				'post_type' => $this->args['post_type'],
				'context' => 'side',
				'priority' => 'core',
				'array' => array( 'inflate' => $this->args['inflate'] ),
				'table' => static::$table
			);

			new Meta_Box( $social );

		endif;

	}

	protected function wp_hooks() {

		// Setup inflation
		add_action( 'save_post', array( &$this, 'set_infl' ) );

	}

	public function set_infl() {

		global $post;

		if( $this->args['inflate'] ) :

			$data = Database::get_row( static::$table, 'post_id', $post->ID );

			if( !empty( $data['id'] ) ) :

				$data['facebook_infl'] = ( empty( $data['facebook_infl'] ) ) ? $this->generate_infl_num() : $data['facebook_infl'];
				$data['twitter_infl'] = ( empty( $data['twitter_infl'] ) ) ? $this->generate_infl_num() : $data['twitter_infl'];
				$data['google_infl'] = ( empty( $data['google_infl'] ) ) ? $this->generate_infl_num() : $data['google_infl'];
				$data['pinterest_infl'] = ( empty( $data['pinterest_infl'] ) ) ? $this->generate_infl_num() : $data['pinterest_infl'];
				$data['linkedin_infl'] = ( empty( $data['linkedin_infl'] ) ) ? $this->generate_infl_num() : $data['linkedin_infl'];
				$data['reddit_infl'] = ( empty( $data['reddit_infl'] ) ) ? $this->generate_infl_num() : $data['reddit_infl'];

				Database::save_data( static::$table, $data );

			endif;

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

	public static function get_api_url() {

		return get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/api/social.php';

	}

	public static function api_url() {

		echo static::get_api_url();

	}

	public static function get_social_media_icon( $key ) {

		return file_get_contents( INTOOR_IMAGES_DIR . 'social/icon_' . $key . '.svg' );

	}

	public static function social_media_icon( $key ) {

		echo static::get_social_media_icon( $key );

	}

	public static function get_social_media_url( $key ) {

		return get_option( 'social_media_settings_' . $key );

	}

	public static function social_media_url( $key ) {

		echo static::get_social_media_url( $key );

	}

	public static function get_social_media_button( $key, $class = '' ) {

		return '<a class="' . $class . '" href="' . static::get_social_media_url( $key ) . '" rel="nofollow">' . static::get_social_media_icon( $key ) . '</a>';

	}

	public static function social_media_button( $key, $class = '' ) {

		echo static::get_social_media_button( $key, $class );

	}

	public static function get_social_media_share_url( $key, $post_id ) {

		global $post;
		$post_id = ( !empty( $post_id ) ) ? $post_id : $post->ID;
		$data = Database::get_row( static::$table, 'post_id', $post_id );
		$url = ( !empty( $data[$key.'_link'] ) ) ? static::$share_url[$key] . $data[$key.'_link'] : static::$share_url[$key] . get_permalink( $post_id );
		return $url;

	}

	public static function social_media_share_url( $key, $post_id ) {

		echo static::get_social_media_share_url( $key, $post_id );

	}

	public static function get_social_media_share_count( $key, $post_id ) {

		global $post;
		$post_id = ( !empty( $post_id ) ) ? $post_id : $post->ID;
		$data = Database::get_row( static::$table, 'post_id', $post_id );
		$count = ( !empty( $data[$key.'_shares'] ) ) ? (int)$data[$key.'_shares'] + (int)$data[$key.'_infl'] : $data[$key.'_infl'];
		return $count;

	}

	public static function social_media_share_count( $key, $post_id ) {

		echo static::get_social_media_share_count( $key, $post_id );

	}

	public static function get_social_media_share_button( $key, $post_id, $show_count = true, $icon_left = true ) {

		global $post;
		$post_id = ( !empty( $post_id ) ) ? $post_id : $post->ID;
		if( $icon_left ) {
			$cont = ( $show_count ) ? '<span class="social-media-share-button-icon">' . static::get_social_media_icon( $key ) . '</span><span class="social-media-share-button-count">' . static::get_social_media_share_count( $key, $post_id ) . '</span>' : static::get_social_media_icon( $key );
		} else {
			$cont = ( $show_count ) ? '<span class="social-media-share-button-count">' . static::get_social_media_share_count( $key, $post_id ) . '</span><span class="social-media-share-button-icon">' . static::get_social_media_icon( $key ) . '</span>' : static::get_social_media_icon( $key );
		}
		$api = get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/api/social.php';
		return '<a class="share-counter" href="' . static::get_social_media_share_url( $key ) . '" rel="nofollow" target="_blank" data-api="' . $api . '" data-id="' . $post_id . '" data-key="' . $key . '">' . $cont . '</a>';

	}

	public static function social_media_share_button( $key, $post_id, $show_count = true, $icon_left = true ) {

		echo static::get_social_media_share_button( $key, $post_id, $show_count, $icon_left );

	}

	public static function get_social_media_share_buttons( $key_arr, $post_id, $show_count = true, $icon_left = true ) {

		global $post;
		$post_id = ( !empty( $post_id ) ) ? $post_id : $post->ID;
		$data = Database::get_row( static::$table, 'post_id', $post_id );
		$s = '<ul class="social-media-share-buttons">';
		foreach( $key_arr as $key ) {
			$url = ( !empty( $data[$key.'_link'] ) ) ? static::$share_url[$key] . $data[$key.'_link'] : static::$share_url[$key] . get_permalink( $post_id );
			$count = ( !empty( $data[$key.'_shares'] ) ) ? (int)$data[$key.'_shares'] + (int)$data[$key.'_infl'] : $data[$key.'_infl'];
			if( $icon_left ) {
				$cont = ( $show_count ) ? '<span class="social-media-share-button-icon">' . static::get_social_media_icon( $key ) . '</span><span class="social-media-share-button-count">' . $count . '</span>' : static::get_social_media_icon( $key );
			} else {
				$cont = ( $show_count ) ? '<span class="social-media-share-button-count">' . $count . '</span><span class="social-media-share-button-icon">' . static::get_social_media_icon( $key ) . '</span>' : static::get_social_media_icon( $key );
			}
			$api = get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/api/social.php';
			$s .= '<li class="social-media-share-button"><a class="share-counter share-link-disabled" href="' . $url . '" rel="nofollow" target="_blank" data-api="' . $api . '" data-id="' . $post_id . '" data-key="' . $key . '">' . $cont . '</a></li>';
		}
		$s .= '</ul>';
		return $s;

	}

	public static function social_media_share_buttons( $key_arr, $post_id, $show_count = true, $icon_left = true ) {

		echo static::get_social_media_share_buttons( $key_arr, $post_id, $show_count, $icon_left );

	}

	public static function run_api_action( $action, $post_id, $key ) {

		$resp = array();

		switch( $action ) {

			case 'share' :
				$resp = static::add_share( $post_id, $key );
				break;

			default :
				$resp['status'] = 'error';
				$resp['desc'] = 'invalid-action';
				$resp['message'] = 'Defined API action cannot be performed.';

		}

		return $resp;

	}

	public static function add_share( $post_id, $key ) {

		$resp = array();

		// Scrub out invalid post_id's
		if( preg_match( '/^[0-9]+$/', $post_id ) ) :

			$networks = array();
			foreach( static::$share_url as $name => $value ) {
				array_push( $networks, $name );
			}

			// Scrub out invalid keys
			if( in_array( $key, $networks ) ) :

				$data = Database::get_row( static::$table, 'post_id', $post_id );
				$data[$key.'_shares'] = (int)$data[$key.'_shares'] + 1;

				if( !empty( $data['post_id'] ) ) :

					Database::save_data( static::$table, $data );
					$resp['status'] = 'success';
					$resp['desc'] = 'submitted';
					$resp['message'] = 'Thanks for sharing the page!';

				else:

					$resp['status'] = 'error';
					$resp['desc'] = 'page-not-found';
					$resp['message'] = 'The page you shared cannot be found.';

				endif;

			else :

				$resp['status'] = 'error';
				$resp['desc'] = 'not-supported';
				$resp['message'] = 'The submitted key is not supported.';

			endif;

		else :

			$resp['status'] = 'error';
			$resp['desc'] = 'invalid-format';
			$resp['message'] = 'The submitted post ID does not match the required format.';

		endif;

		return $resp;

	}
 
}