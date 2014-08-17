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
			'facebook_shares' => array( 'BIGINT(20)', false, '0' ),
			'twitter_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'twitter_shares' => array( 'BIGINT(20)', false, '0' ),
			'google_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'google_shares' => array( 'BIGINT(20)', false, '0' ),
			'pinterest_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'pinterest_shares' => array( 'BIGINT(20)', false, '0' ),
			'linkedin_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'linkedin_shares' => array( 'BIGINT(20)', false, '0' ),
			'reddit_link' => array( 'VARCHAR(255)', false, NULL, 'text' ),
			'reddit_shares' => array( 'BIGINT(20)', false, '0' ),
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

	public static function get_social_media_button( $key, $class = '' ) {

		return '<a class="' . $class . '" href="' . static::get_social_media_url( $key ) . '">' . static::get_social_media_icon( $key ) . '</a>';

	}

	public static function social_media_button( $key, $class = '' ) {

		echo static::get_social_media_button( $key, $class );

	}

	public static function get_social_media_share_url( $key, $post_id ) {

		$data = Database::get_row( static::$table, 'post_id', $post_id );
		$url = ( !empty( $data[$key.'_link'] ) ) ? static::$share_url[$key] . $data[$key.'_link'] : static::$share_url[$key] . get_permalink( $post_id );
		return $url;

	}

	public static function social_media_share_url( $key, $post_id ) {

		echo static::get_social_media_share_url( $key, $post_id );

	}

	public static function get_social_media_share_count( $key, $post_id ) {

		$data = Database::get_row( static::$table, 'post_id', $post_id );
		$count = ( !empty( $data[$key.'_shares'] ) ) ? $data[$key.'_shares'] : '0';
		return $count;

	}

	public static function social_media_share_count( $key, $post_id ) {

		echo static::get_social_media_share_count( $key, $post_id );

	}

	public static function get_social_media_share_button( $key, $post_id, $show_count = true, $icon_left = true ) {

		if( $icon_left ) {
			$cont = ( $show_count ) ? '<span class="social-media-share-button-icon">' . static::get_social_media_icon( $key ) . '</span><span class="social-media-share-button-count">' . static::get_social_media_share_count( $key, $post_id ) . '</span>' : static::get_social_media_icon( $key );
		} else {
			$cont = ( $show_count ) ? '<span class="social-media-share-button-count">' . static::get_social_media_share_count( $key, $post_id ) . '</span><span class="social-media-share-button-icon">' . static::get_social_media_icon( $key ) . '</span>' : static::get_social_media_icon( $key );
		}
		return '<a href="' . static::get_social_media_share_url( $key ) . '">' . $cont . '</a>';

	}

	public static function social_media_share_button( $key, $post_id, $show_count = true, $icon_left = true ) {

		echo static::get_social_media_share_button( $key, $post_id, $show_count, $icon_left );

	}

	public static function get_social_media_share_buttons( $key_arr, $post_id, $show_count = true, $icon_left = true ) {

		$data = Database::get_row( static::$table, 'post_id', $post_id );
		$s = '<ul class="social-media-share-buttons">';
		foreach( $key_arr as $key ) {
			$url = ( !empty( $data[$key.'_link'] ) ) ? static::$share_url[$key] . $data[$key.'_link'] : static::$share_url[$key] . get_permalink( $post_id );
			$count = ( !empty( $data[$key.'_shares'] ) ) ? $data[$key.'_shares'] : '0';
			if( $icon_left ) {
				$cont = ( $show_count ) ? '<span class="social-media-share-button-icon">' . static::get_social_media_icon( $key ) . '</span><span class="social-media-share-button-count">' . $count . '</span>' : static::get_social_media_icon( $key );
			} else {
				$cont = ( $show_count ) ? '<span class="social-media-share-button-count">' . $count . '</span><span class="social-media-share-button-icon">' . static::get_social_media_icon( $key ) . '</span>' : static::get_social_media_icon( $key );
			}
			$s .= '<li class="social-media-share-button"><a href="' . $url . '">' . $cont . '</a></li>';
		}
		$s .= '</ul>';
		return $s;

	}

	public static function social_media_share_buttons( $key_arr, $post_id, $show_count = true, $icon_left = true ) {

		echo static::get_social_media_share_buttons( $key_arr, $post_id, $show_count, $icon_left );

	}
 
}