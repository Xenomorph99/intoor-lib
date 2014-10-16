<?php
/**
 * This model controls all interactions with social media networks as
 * well as tracks sharing.
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

	public $args = [
		'meta_box' => true,					// Include the social share custom meta box
		'post_type' => array( 'post' ),		// Type of screen(s) on which to display the social share custom meta box (post, page, etc)
		'inflate' => false,					// Artificially inflate initial 'share' count
		'infl_range' => 'mid',				// Range of inflated numbers to be generated 'low' = 0-10, 'mid' = 10-50, 'high' = 50-100, 'ultra' = 100-500, 'custom'
		'infl_min' => 10,					// Custom inflation range min number
		'infl_max' => 50					// Custom inflation range max number
	];

	public $settings = [
		'facebook' => [
			'type' => 'url',
			'label' => 'Facebook Link',
			'placeholder' => 'http://facebook.com'
		],
		'twitter' => [
			'type' => 'url',
			'label' => 'Twitter Link',
			'placeholder' => 'http://twitter.com'
		],
		'google' => [
			'type' => 'url',
			'label' => 'Google+ Link',
			'placeholder' => 'http://plus.google.com'
		],
		'pinterest' => [
			'type' => 'url',
			'label' => 'Pinterest Link',
			'placeholder' => 'http://pinterest.com'
		],
		'instagram' => [
			'type' => 'url',
			'label' => 'Instagram Link',
			'placeholder' => 'http://instagram.com'
		],
		'youtube' => [
			'type' => 'url',
			'label' => 'YouTube Link',
			'placeholder' => 'http://youtube.com'
		],
		'linkedin' => [
			'type' => 'url',
			'label' => 'LinkedIn Link',
			'placeholder' => 'http://linkedin.com'
		],
		'tumblr' => [
			'type' => 'url',
			'label' => 'Tumblr Link',
			'placeholder' => 'http://tumblr.com'
		],
		'vine' => [
			'type' => 'url',
			'label' => 'Vine Link',
			'placeholder' => 'http://vine.co'
		],
		'vimeo' => [
			'type' => 'url',
			'label' => 'Vimeo Link',
			'placeholder' => 'http://vimeo.com'
		],
		'soundcloud' => [
			'type' => 'url',
			'label' => 'SoundCloud Link',
			'placeholder' => 'http://soundcloud.com'
		],
		'flickr' => [
			'type' => 'url',
			'label' => 'Flickr Link',
			'placeholder' => 'http://flickr.com'
		],
		'github' => [
			'type' => 'url',
			'label' => 'GitHub Link',
			'placeholder' => 'http://github.com'
		],
		'behance' => [
			'type' => 'url',
			'label' => 'Behance Link',
			'placeholder' => 'http://behance.net'
		],
		'dribbble' => [
			'type' => 'url',
			'label' => 'Dribbble Link',
			'placeholder' => 'http://dribbble.com'
		],
		'deviantart' => [
			'type' => 'url',
			'label' => 'DeviantART Link',
			'placeholder' => 'http://deviantart.com'
		],
		'yelp' => [
			'type' => 'url',
			'label' => 'Yelp Link',
			'placeholder' => 'http://yelp.com'
		],
		'foursquare' => [
			'type' => 'url',
			'label' => 'Foursquare Link',
			'placeholder' => 'http://foursquare.com'
		],
		'meetup' => [
			'type' => 'url',
			'label' => 'Meetup Link',
			'placeholder' => 'http://meetup.com'
		],
		'myspace' => [
			'type' => 'url',
			'label' => 'Myspace Link',
			'placeholder' => 'http://myspace.com'
		],
		'reddit' => [
			'type' => 'url',
			'label' => 'Reddit Link',
			'placeholder' => 'http://reddit.com'
		],
		'weibo' => [
			'type' => 'url',
			'label' => 'Weibo Link',
			'placeholder' => 'http://weibo.com'
		],
		'renren' => [
			'type' => 'url',
			'label' => 'Renren Link',
			'placeholder' => 'http://renren.com'
		]
	];

	public static $table = [
		'name' => 'social',
		'prefix' => 'soc',
		'version' => '1.0',
		'structure' => [
			'facebook_shares' => [
				'sql' => 'BIGINT(20)',
				'default' => '0'
			],
			'facebook_infl' => [
				'sql' => 'TINYINT(3)',
				'default' => '0'
			],
			'twitter_shares' => [
				'sql' => 'BIGINT(20)',
				'default' => '0'
			],
			'twitter_infl' => [
				'sql' => 'TINYINT(3)',
				'default' => '0'
			],
			'google_shares' => [
				'sql' => 'BIGINT(20)',
				'default' => '0'
			],
			'google_infl' => [
				'sql' => 'TINYINT(3)',
				'default' => '0'
			],
			'pinterest_shares' => [
				'sql' => 'BIGINT(20)',
				'default' => '0'
			],
			'pinterest_infl' => [
				'sql' => 'TINYINT(3)',
				'default' => '0'
			],
			'linkedin_shares' => [
				'sql' => 'BIGINT(20)',
				'default' => '0'
			],
			'linkedin_infl' => [
				'sql' => 'TINYINT(3)',
				'default' => '0'
			],
			'reddit_shares' => [
				'sql' => 'BIGINT(20)',
				'default' => '0'
			],
			'reddit_infl' => [
				'sql' => 'TINYINT(3)',
				'default' => '0'
			]
		]
	];

	public static $share_url = [
		'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=',
		'twitter' => 'https://twitter.com/intent/tweet?url=',
		'google' => 'https://plus.google.com/share?url=',
		'pinterest' => 'https://pinterest.com/pin/create/button/?url=',
		'linkedin' => 'https://www.linkedin.com/shareArticle?mini=true&url=',
		'reddit' => 'http://www.reddit.com/submit?url='
	];

	public function __construct( $args ) {

		$this->args = wp_parse_args( $args, $this->args );

		// Add network icon to labels
		foreach( $this->settings as $name => $value ) {
			$value['label'] = '<span class="social-icon" style="width:30px; height:30px; line-height:30px; display:inline-block; overflow:hidden; float:left;">' . file_get_contents( INTOOR_IMAGES_DIR . 'social/icon_' . $name . '.svg' ) . '</span><span style="line-height:30px; margin-left:12px; display:inline-block; float:left;">' . $value['label'] . '</span>'; 
			$this->settings[$name] = $value;
		}

		$this->setup_social_media();
		$this->setup_admin_menus();
		$this->wp_hooks();

	}

	protected function setup_social_media() {

		Database::install_table( static::$table, true );
		API::new_key( 'social_sharing' );

	}

	protected function setup_admin_menus() {

		$social = [
			'title' => 'Social Media Settings',
			'menu_title' => 'Social Media',
			'fields' => $this->settings
		];

		new Admin_Menu( $social );

	}

	protected function wp_hooks() {

		// Setup inflation
		add_action( 'save_post', array( &$this, 'setup_inflation' ) );

	}

	public function setup_inflation() {

		global $post;

		if( $this->args['inflate'] ) :

			$data = Database::get_row( static::$table, 'post_id', $post->ID );

			if( !empty( $data['id'] ) ) :

				$data['facebook_infl'] = !empty( $data['facebook_infl'] ) ? $data['facebook_infl'] : Functions::numgen( $this->args['infl_range'], $this->args['infl_min'], $this->args['infl_max'] );
				$data['twitter_infl'] = !empty( $data['twitter_infl'] ) ? $data['twitter_infl'] : Functions::numgen( $this->args['infl_range'], $this->args['infl_min'], $this->args['infl_max'] );
				$data['google_infl'] = !empty( $data['google_infl'] ) ? $data['google_infl'] : Functions::numgen( $this->args['infl_range'], $this->args['infl_min'], $this->args['infl_max'] );
				$data['pinterest_infl'] = !empty( $data['pinterest_infl'] ) ? $data['pinterest_infl'] : Functions::numgen( $this->args['infl_range'], $this->args['infl_min'], $this->args['infl_max'] );
				$data['linkedin_infl'] = !empty( $data['linkedin_infl'] ) ? $data['linkedin_infl'] : Functions::numgen( $this->args['infl_range'], $this->args['infl_min'], $this->args['infl_max'] );
				$data['reddit_infl'] = !empty( $data['reddit_infl'] ) ? $data['reddit_infl'] : Functions::numgen( $this->args['infl_range'], $this->args['infl_min'], $this->args['infl_max'] );

				Database::save_data( static::$table, $data );

			endif;

		endif;

	}

	public static function get_api_url() {

		return get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/api/social.php';

	}

	public static function api_url() {

		echo static::get_api_url();

	}

	public static function get_social_media_icon( $name ) {

		return file_get_contents( INTOOR_IMAGES_DIR . 'social/icon_' . $name . '.svg' );

	}

	public static function social_media_icon( $name ) {

		echo static::get_social_media_icon( $name );

	}

	public static function get_social_media_url( $name ) {

		return get_option( 'social_media_settings_' . $name );

	}

	public static function social_media_url( $name ) {

		echo static::get_social_media_url( $name );

	}

	public static function get_social_media_button( $name, $class = '' ) {

		return '<a class="' . $class . '" href="' . static::get_social_media_url( $name ) . '" rel="nofollow">' . static::get_social_media_icon( $name ) . '</a>';

	}

	public static function social_media_button( $name, $class = '' ) {

		echo static::get_social_media_button( $name, $class );

	}

	public static function get_social_media_share_url( $name, $post_id ) {

		global $post;
		$post_id = ( !empty( $post_id ) ) ? $post_id : $post->ID;
		$data = Database::get_row( static::$table, 'post_id', $post_id );
		$url = ( !empty( $data[$name.'_link'] ) ) ? static::$share_url[$name] . $data[$name.'_link'] : static::$share_url[$name] . get_permalink( $post_id );
		return $url;

	}

	public static function social_media_share_url( $name, $post_id ) {

		echo static::get_social_media_share_url( $name, $post_id );

	}

	public static function get_social_media_share_count( $name, $post_id ) {

		global $post;
		$post_id = ( !empty( $post_id ) ) ? $post_id : $post->ID;
		$data = Database::get_row( static::$table, 'post_id', $post_id );
		$count = ( !empty( $data[$name.'_shares'] ) ) ? (int)$data[$name.'_shares'] + (int)$data[$name.'_infl'] : $data[$name.'_infl'];
		return $count;

	}

	public static function social_media_share_count( $name, $post_id ) {

		echo static::get_social_media_share_count( $name, $post_id );

	}

	public static function get_social_media_share_button( $name, $post_id, $show_count = true, $icon_left = true ) {

		global $post;
		$post_id = ( !empty( $post_id ) ) ? $post_id : $post->ID;
		if( $icon_left ) {
			$cont = ( $show_count ) ? '<span class="social-media-share-button-icon">' . static::get_social_media_icon( $name ) . '</span><span class="social-media-share-button-count">' . static::get_social_media_share_count( $name, $post_id ) . '</span>' : static::get_social_media_icon( $name );
		} else {
			$cont = ( $show_count ) ? '<span class="social-media-share-button-count">' . static::get_social_media_share_count( $name, $post_id ) . '</span><span class="social-media-share-button-icon">' . static::get_social_media_icon( $name ) . '</span>' : static::get_social_media_icon( $name );
		}
		$api = get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/api/social.php';
		return '<a class="share-counter" href="' . static::get_social_media_share_url( $name ) . '" rel="nofollow" target="_blank" data-api="' . $api . '" data-id="' . $post_id . '" data-key="' . $name . '">' . $cont . '</a>';

	}

	public static function social_media_share_button( $name, $post_id, $show_count = true, $icon_left = true ) {

		echo static::get_social_media_share_button( $name, $post_id, $show_count, $icon_left );

	}

	public static function get_social_media_share_buttons( $name_arr, $post_id, $show_count = true, $icon_left = true ) {

		global $post;
		$post_id = ( !empty( $post_id ) ) ? $post_id : $post->ID;
		$data = Database::get_row( static::$table, 'post_id', $post_id );
		$s = '<ul class="social-media-share-buttons">';
		foreach( $name_arr as $name ) {
			$url = ( !empty( $data[$name.'_link'] ) ) ? static::$share_url[$name] . $data[$name.'_link'] : static::$share_url[$name] . get_permalink( $post_id );
			$count = ( !empty( $data[$name.'_shares'] ) ) ? (int)$data[$name.'_shares'] + (int)$data[$name.'_infl'] : $data[$name.'_infl'];
			if( $icon_left ) {
				$cont = ( $show_count ) ? '<span class="social-media-share-button-icon">' . static::get_social_media_icon( $name ) . '</span><span class="social-media-share-button-count">' . $count . '</span>' : static::get_social_media_icon( $name );
			} else {
				$cont = ( $show_count ) ? '<span class="social-media-share-button-count">' . $count . '</span><span class="social-media-share-button-icon">' . static::get_social_media_icon( $name ) . '</span>' : static::get_social_media_icon( $name );
			}
			$api = get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/api/social.php';
			$s .= '<li class="social-media-share-button"><a class="share-counter share-link-disabled" href="' . $url . '" rel="nofollow" target="_blank" data-api="' . $api . '" data-id="' . $post_id . '" data-key="' . $name . '">' . $cont . '</a></li>';
		}
		$s .= '</ul>';
		return $s;

	}

	public static function social_media_share_buttons( $name_arr, $post_id, $show_count = true, $icon_left = true ) {

		echo static::get_social_media_share_buttons( $name_arr, $post_id, $show_count, $icon_left );

	}

	public static function run_api_action( $action, $arr = array() ) {

		$resp = array();

		$resp['status'] = 'error';
		$resp['type'] = 'invalid-action';
		$resp['message'] = 'Defined API action cannot be performed.';

		switch( $action ) {

			case 'share':
				$resp = static::add_share( $arr['post_id'], $arr['name'] );
				break;

		}

		return $resp;

	}

	public static function add_share( $post_id, $name ) {

		$name = strtolower( $name );
		$networks = array();
		$resp = array();

		foreach( static::$share_url as $name => $value ) {
			array_push( $networks, $name );
		}

		$resp['status'] = 'error';
		$resp['type'] = 'invalid-format';
		$resp['message'] = 'The submitted post ID does not match the required format.';

		// Scrub out invalid post_id's
		if( preg_match( '/^[0-9]+$/', $post_id ) ) :

			$resp['status'] = 'error';
			$resp['type'] = 'invalid-network';
			$resp['message'] = 'The submitted network name is not supported';

			// Scrub out invalid network names
			if( in_array( $name, $networks ) ) :

				$data = Database::get_row( static::$table, 'post_id', $post_id );
				$data[$name.'_shares'] = (int)$data[$name.'_shares'] + 1;

				if( Database::save_data( static::$table, $data ) ) :

					$resp['status'] = 'success';
					$resp['type'] = 'success';
					$resp['message'] = 'The share was successfully recorded';

				else:

					$resp['status'] = 'error';
					$resp['type'] = 'database-error';
					$resp['message'] = 'An error occured connecting to the database. Try again later.';

				endif;

			endif;

		endif;

		return $resp;

	}
 
}