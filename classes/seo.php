<?php
/**
 * This model manages meta tags in the page header
 *
 * @package     Интоор Library (intoor)
 * @author      Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright   2014 Hazard Media Group LLC
 * @license     MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link        https://github.com/Alekhen/intoor-lib
 * @version     Release: 1.2
 */

if( !defined( 'INTOOR_RESTRICT_ACCESS' ) || !INTOOR_RESTRICT_ACCESS ) { die( 'Unauthorized Access' ); }

class SEO {

	public $args = [
		'post_type' => array( 'post', 'page' ),  // Type of screen(s) on which to show the meta box
	];

	public static $table = [
		'name' => 'seo',
		'prefix' => 'seo',
		'version' => '1.0',
		'structure' => [
			'title' => [
				'sql' => 'VARCHAR(255)',
				'type' => 'text',
				'label' => 'Title Meta Tag'
			],
			'description' => [
				'sql' => 'LONGTEXT',
				'type' => 'textarea',
				'label' => 'Description Meta Tag'
			],
			'og_title' => [
				'sql' => 'VARCHAR(255)',
				'type' => 'text',
				'label' => 'og:Title'
			],
			'og_image' => [
				'sql' => 'VARCHAR(255)',
				'type' => 'text',
				'label' => 'og:Image'
			],
			'og_description' => [
				'sql' => 'LONGTEXT',
				'type' => 'textarea',
				'label' => 'og:Description'
			],
			'twitter_title' => [
				'sql' => 'VARCHAR(255)',
				'type' => 'text',
				'label' => 'twitter:Title'
			],
			'twitter_image' => [
				'sql' => 'VARCHAR(255)',
				'type' => 'text',
				'label' => 'twitter:Image'
			],
			'twitter_description' => [
				'sql' => 'LONGTEXT',
				'type' => 'textarea',
				'label' => 'twitter:Description'
			]
		]
	];

	public function __construct( $args = NULL ) {

		$this->args = !empty( $args ) ? wp_parse_args( $args, $this->args ) : $this->args;

		$this->setup_meta_box();

	}

	protected function setup_meta_box() {

		$seo = [
			'title' => 'SEO',
			'post_type' => $this->args['post_type'],
			'table' => static::$table
		];

		new Meta_Box( $seo );

	}

	public static function get_title( $data = array() ) {

		global $post;

		$data = !empty( $data ) ? $data : Database::get_row( static::$table, 'post_id', $post->ID );
		$title = !empty( $data['title'] ) ? $data['title'] : $post->post_title;

		return '<title>' . $title . ' | ' . get_bloginfo( 'blogname' ) . '</title>';

	}

	public static function title( $data = array() ) {

		echo static::get_title( $data );

	}

	public static function get_description( $data = array() ) {

		global $post;

		$data = !empty( $data ) ? $data : Database::get_row( static::$table, 'post_id', $post->ID );
		$description = !empty( $data['description'] ) ? $data['description'] : $post->post_excerpt;

		return !empty( $description ) ? '<meta name="description" content="' . $description . '">' : '';

	}

	public static function description( $data = array() ) {

		echo static::get_description( $data );

	}

	public static function get_og_title( $data = array() ) {

		global $post;

		$data = !empty( $data ) ? $data : Database::get_row( static::$table, 'post_id', $post->ID );
		$title = !empty( $data['og_title'] ) ? $data['og_title'] : $data['title'];
		$title = !empty( $title ) ? $title : $post->post_title;

		return !empty( $title ) ? '<meta property="og:title" content="' . $title . '">': '';

	}

	public static function og_title( $data = array() ) {

		echo static::get_og_title( $data );

	}

	public static function get_og_image( $data = array() ) {

		global $post;

		$data = !empty( $data ) ? $data : Database::get_row( static::$table, 'post_id', $post->ID );
		$image = !empty( $data['og_image'] ) ? $data['og_image'] : '';

		if( empty( $image ) && has_post_thumbnail( $post->ID ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) );
			$image = $image[0];
		}

		return !empty( $image ) ? '<meta property="og:image" content="' . $image . '">' : '';

	}

	public static function og_image( $data = array() ) {

		echo static::get_og_image( $data );

	}

	public static function get_og_description( $data = array() ) {

		global $post;

		$data = !empty( $data ) ? $data : Database::get_row( static::$table, 'post_id', $post->ID );
		$description = !empty( $data['og_description'] ) ? $data['og_description'] : '';
		$description = !empty( $description ) ? $description : $post->post_excerpt;

		return !empty( $description ) ? '<meta property="og:description" content="' . $description . '">' : '';

	}

	public static function og_description( $data = array() ) {

		echo static::get_og_description( $data );

	}

	public static function get_og_type() {

		return '<meta property="og:type" content="article">';

	}

	public static function og_type() {

		echo static::get_og_type();

	}

	public static function get_og_url() {

		global $post;

		return '<meta property="og:url" content="' . get_permalink( $post->ID ) . '">';

	}

	public static function og_url() {

		echo static::get_og_url();

	}

	public static function get_twitter_title( $data = array() ) {

		global $post;

		$data = !empty( $data ) ? $data : Database::get_row( static::$table, 'post_id', $post->ID );
		$title = !empty( $data['twitter_title'] ) ? $data['twitter_title'] : $data['title'];
		$title = !empty( $title ) ? $title : $post->post_title;

		return !empty( $title ) ? '<meta name="twitter:title" content="' . $title . '">': '';

	}

	public static function twitter_title( $data = array() ) {

		echo static::get_twitter_title( $data );

	}

	public static function get_twitter_image( $data = array() ) {

		global $post;

		$data = !empty( $data ) ? $data : Database::get_row( static::$table, 'post_id', $post->ID );
		$image = !empty( $data['twitter_image'] ) ? $data['twitter_image'] : '';

		if( empty( $image ) && has_post_thumbnail( $post->ID ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) );
			$image = $image[0];
		}

		return !empty( $image ) ? '<meta name="twitter:image" content="' . $image . '">' : '';

	}

	public static function twitter_image( $data = array() ) {

		echo static::get_twitter_image( $data );

	}

	public static function get_twitter_description( $data = array() ) {

		global $post;

		$data = !empty( $data ) ? $data : Database::get_row( static::$table, 'post_id', $post->ID );
		$description = !empty( $data['twitter_description'] ) ? $data['twitter_description'] : '';
		$description = !empty( $description ) ? $description : $post->post_excerpt;

		return !empty( $description ) ? '<meta name="twitter:description" content="' . $description . '">' : '';

	}

	public static function twitter_description( $data = array() ) {

		echo static::get_twitter_description( $data );

	}

	public static function get_twitter_card() {

		return '<meta name="twitter:card" content="summary">';

	}

	public static function twitter_card() {

		echo static::get_twitter_card();

	}

	public static function get_twitter_url() {

		global $post;

		return '<meta name="twitter:url" content="' . get_permalink( $post->ID ) . '">';

	}

	public static function twitter_url() {

		echo static::get_twitter_url();

	}

	public static function meta_tags( $data = array() ) {

		$n = "\n";
		$s = static::get_title( $data ) . $n;
		$s .= static::get_description( $data ) . $n;
		$s .= static::get_og_title( $data ) . $n;
		$s .= static::get_og_image( $data ) . $n;
		$s .= static::get_og_description( $data ) . $n;
		$s .= static::get_og_type() . $n;
		$s .= static::get_og_url() . $n;
		$s .= static::get_twitter_card() . $n;
		$s .= static::get_twitter_url() . $n;
		$s .= static::get_twitter_title( $data ) . $n;
		$s .= static::get_twitter_image( $data ) . $n;
		$s .= static::get_twitter_description( $data );

		return $s;

	}

}