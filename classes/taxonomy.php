<?php
/**
 * This model is used to add custom taxonomies.
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

class Taxonomy {

	public $args = array(
		'name_plural' => '',
		'name_singular' => '',
		'slug' => '',
		'post_type' => array( 'post' ),
		'public' => true,
		'show_ui' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud' => true,
		'meta_box_cb' => null,
		'show_admin_column' => false,
		'hierarchical' => false,
		'update_count_callback' => '',
		'capabilities' => array(),
		'sort' => false
	);

	public function __construct( $args ) {

		$this->args = wp_parse_args( $args, $this->args );
		$this->args['id'] = Functions::str_smash( $this->args['name_plural'] );

		$this->wp_hooks();

	}

	protected function wp_hooks() {

		add_action( 'init', array( &$this, 'register_custom_taxonomy' ) );

	}

	public function register_custom_taxonomy() {

		extract( $this->args );

		$labels = array(
			'name' => _x( $name_plural, 'taxonomy general name' ),
			'singular_name' => _x( $name_singular, 'taxonomy singular name' ),
			'search_items' => __( "Search $name_plural" ),
			'popular_items' => __( "Popular $name_plural" ),
			'all_items' => __( "All $name_plural" ),
			'parent_item' => __( "Parent $name_singular" ),
			'parent_item_colon' => __( "Parent $name_singular:" ),
			'edit_item' => __( "Edit $name_singular" ),
			'update_item' => __( "Update $name_singular" ),
			'view_item' => __( "View $name_singular" ),
			'add_new_item' => __( "Add New $name_singular" ),
			'new_item_name' => __( "New $name_singular Name" ),
			'separate_items_with_commas' => __( "Separate $name_plural with commas" ),
			'add_or_remove_items' => __( "Add or remove $name_plural" ),
			'choose_from_most_used' => __( "Choose from the most used $name_plural" ),
			'not_found' => __( "No $name_plural found." ),
			'menu_name' => __( $name_plural ),
		);

		$arr = array(
			'labels' => $labels,
			'rewrite' => array( 'slug' => $slug, 'hierarchical' => $hierarchical ),
			'public' => $public,
			'show_ui' => $show_ui,
			'show_in_nav_menus' => $show_in_nav_menus,
			'show_tagcloud' => $show_tagcloud,
			'meta_box_cb' => $meta_box_cb,
			'show_admin_column' => $show_admin_column,
			'hierarchical' => $hierarchical,
			'update_count_callback' => $update_count_callback,
			'capabilities' => $capabilities,
			'sort' => $sort
		);

		register_taxonomy( $id, $post_type, $arr );

	}

}