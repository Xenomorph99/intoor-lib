<?php
/**
 * This model controls the interaction with social networks.
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
		'vine' => array( '', 'url', 'Vine Link', NULL, 'http://vine.com' ),
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
			'infl' => array( 'TINYINT(3)', false, '0' ),
			'facebook_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'facebook_shares' => array( 'VARCHAR(255)', false, NULL ),
			'twitter_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'twitter_shares' => array( 'VARCHAR(255)', false, NULL ),
			'google_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'google_shares' => array( 'VARCHAR(255)', false, NULL ),
			'pinterest_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'pinterest_shares' => array( 'VARCHAR(255)', false, NULL ),
			'linkedin_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'linkedin_shares' => array( 'VARCHAR(255)', false, NULL ),
			'reddit_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'reddit_shares' => array( 'VARCHAR(255)', false, NULL ),
		)
	);

	public function __construct( $args ) {

		$this->args = Functions::merge_array( $args, $this->args );
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
				'title' => 'Social Sharing',
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

		// Do nothing

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

	public static function get_social_media_share_url( $post_id, $key ) {

		$data = Database::get_row( static::$table, 'post_id', $post_id );
		return $data[$key.'_link'];

	}

	public static function social_media_share_url( $post_id, $key ) {

		echo static::get_social_media_share_url( $post_id, $key );

	}

	public static function get_social_media_share_count( $post_id, $key ) {

		$data = Database::get_row( static::$table, 'post_id', $post_id );
		return $data[$key.'_count'];

	}

	public static function social_media_share_count( $post_id, $key ) {

		echo static::get_social_media_share_count( $post_id, $key );

	}

	public static function get_social_media_button( $key ) {

		return '<a href="' . get_social_media_url( $key ) . '">' . get_social_media_icon( $key ) . '</a>';

	}

	public static function social_media_button( $key ) {

		echo static::get_social_media_button( $key );

	}

	public static function get_social_media_share_button( $key, $post_id = NULL, $show_count = true, $icon_left = true ) {

		if( $icon_left ) {
			$cont = ( $show_count ) ? '<span class="social-media-share-button-icon">' . get_social_media_icon( $key ) . '</span><span class="social-media-share-button-count">' . get_social_media_share_count( $post_id, $key ) . '</span>' : get_social_media_icon( $key );
		} else {
			$cont = ( $show_count ) ? '<span class="social-media-share-button-count">' . get_social_media_share_count( $post_id, $key ) . '</span><span class="social-media-share-button-icon">' . get_social_media_icon( $key ) . '</span>' : get_social_media_icon( $key );
		}
		return '<a href="' . get_social_media_share_url( $key ) . '">' . $cont . '</a>';

	}

	public static function social_media_share_button( $key, $post_id = NULL, $show_count = true, $icon_left = true ) {

		echo static::get_social_media_share_button( $key, $post_id, $show_count, $icon_left );

	}

	public static function get_social_media_share_buttons( $key_arr, $post_id = NULL, $show_count = true, $icon_left = true ) {

		$s = '<ul class="social-media-share-buttons">';
		foreach( $key_arr as $key ) {
			$s .= '<li class="social-media-share-button">' . static::get_social_media_share_button( $key, $post_id, $show_count, $icon_left ) . '</li>';
		}
		$s .= '</ul>';
		return $s;

	}

	public static function social_media_share_buttons( $key_arr, $post_id = NULL, $show_count = true, $icon_left = true ) {

		echo static::get_social_media_share_buttons( $key_arr, $post_id, $show_count, $icon_left );

	}
 
}