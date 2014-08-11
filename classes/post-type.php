<?php
/**
 * This model is used to add custom post types to Wordpress.
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor
 * @version		Release: 1.0
 */

class Post_Type {

	public $settings = array(
		'post_type' => '',							// Name of the custom post type (no spaces, lowercase)
		'name_singular' => '',						// Singular display name of the post type
		'name_plural' => '',						// Plural display name of the post type
		'namespace' => 'wp',						// Namespace declaration
		'heirarchial' => false,						// Create a hierarchy allowing parent and child posts
		'supports' => array( 'title', 'editor' ),	// Allowed options: title, editor, author, thumbnail, excerpt, trackbacks, custom-fields, comments, revisions, page-attributes, post-formats
		'taxonomies' => array(),					// Registered taxonomies
		'public' => true,							// Outwardly displayed for users or only for the WP admin
		'show_ui' => true,							// Display a user interface for this post type
		'show_in_menu' => true,						// Display the post type in the WP admin menu
		'menu_icon' => null,						// Icon font icon - defaults to the post icon
		'menu_position' => null,					// Location in the WP admin menu (5 - below Posts, 10 - below Media, 15 - below Links, 20 - below Pages, 25 - below comments, 60 - below first separator, 65 - below Plugins, 70 - below Users, 75 - below Tools, 80 - below Settings, 100 - below second separator)
		'show_in_nav_menus' => true,				// Allow the post type to be shown in nav menus
		'publicly_queryable' => true,				// Front end can query this post type
		'exclude_from_search' => false,				// Should the custom post type show up in front end searches
		'has_archive' => true,						// Enables archive for the custom post type
		'query_var' => true,						// Sets the query variable for the custom post type (true - uses post type, false, custom string)
		'can_export' => true,						// Custom post type can be exported
		'rewrite' => true,							// Custom post type posts can be rewritten
		'capability_type' => 'post'					// Set the functionality of the custom post type similar to: post, page
	);

	public function __construct( $arr = null ) {

		if( !empty( $arr ) ) {
			foreach( $arr as $name => $value ) {
				$this->settings[$name] = $value;
			}
			$this->wp_hooks();
		}

	}

	protected function wp_hooks() {

		// Register the custom post type
		add_action( 'init', array( &$this, 'register_custom_post_type' ) );

		// Add the custom post type's archive page to WP nav menus
		add_action('admin_head-nav-menus.php', array( &$this, 'wpclean_add_metabox_menu_posttype_archive' ) );

	}

	public function register_custom_post_type() {

		extract( $this->settings );

		$labels = array(
			'name' => _x( $name_plural, $namespace ),
			'singular_name' => _x( $name_singular, $namespace ),
			'add_new' => _x( "Add New", $namespace ),
			'add_new_item' => _x( "Add New $name_singular", $namespace ),
			'edit_item' => _x( "Edit $name_singular", $namespace ),
			'new_item' => _x( "New $name_singular", $namespace ),
			'view_item' => _x( "View $name_singular", $namespace ),
			'search_items' => _x( "Search $name_plural", $namespace ),
			'not_found' => _x( "No $name_plural Found", $namespace ),
			'not_found_in_trash' => _x( "No $name_plural found in Trash", $namespace ),
			'parent_item_colon' => _x( "Parent $name_singular:", $namespace ),
			'menu_name' => _x( $name_plural, $namespace )
		);

		$args = array(
			'labels' => $labels,
			'heirarchial' => $heirarchial,
			'supports' => $supports,
			'taxonomies' => $taxonomies,
			'public' => $public,
			'show_ui' => $show_ui,
			'show_in_menu' => $show_in_menu,
			'menu_icon' => $menu_icon,
			'menu_position' => $menu_position,
			'show_in_nav_menus' => $show_in_nav_menus,
			'publicly_queryable' => $publicly_queryable,
			'exclude_from_search' => $exclude_from_search,
			'has_archive' => $has_archive,
			'query_var' => $query_var,
			'can_export' => $can_export,
			'rewrite' => $rewrite,
			'capability_type' => $capability_type
		);

		register_post_type( $post_type, $args );
		flush_rewrite_rules( false );

	}

	public function wpclean_add_metabox_menu_posttype_archive() {

		add_meta_box( 'wpclean-metabox-nav-menu-posttype', 'Custom Post Type Archives', array( &$this, 'wpclean_metabox_menu_posttype_archive' ), 'nav-menus', 'side', 'default' );

	}

	public function wpclean_metabox_menu_posttype_archive() {
		
		$post_types = get_post_types(array('show_in_nav_menus' => true, 'has_archive' => true), 'object');

		if( $post_types ) :

			$items = array();
			$loop_index = 999999;

			foreach( $post_types as $post_type ) {
				$item = new stdClass();
				$loop_index++;
				$item->object_id = $loop_index;
				$item->db_id = 0;
				$item->object = 'post_type_' . $post_type->query_var;
				$item->menu_item_parent = 0;
				$item->type = 'custom';
				$item->title = $post_type->labels->name;
				$item->url = get_post_type_archive_link( $post_type->query_var );
				$item->target = '';
				$item->attr_title = '';
				$item->classes = array();
				$item->xfn = '';
				$items[] = $item;
			}

			$walker = new Walker_Nav_Menu_Checklist( array() );

			echo '<div id="posttype-archive" class="posttypediv">';
			echo '<div id="tabs-panel-posttype-archive" class="tabs-panel tabs-panel-active">';
			echo '<ul id="posttype-archive-checklist" class="categorychecklist form-no-clear">';
			echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $items ), 0, ( object ) array( 'walker' => $walker ) );
			echo '</ul>';
			echo '</div>';
			echo '</div>';

			echo '<p class="button-controls">';
			echo '<span class="add-to-menu">';
			echo '<input type="submit"' . disabled( 1, 0 ) . ' class="button-secondary submit-add-to-menu right" value="' . __( 'Add to Menu', 'andromedamedia' ) . '" name="add-posttype-archive-menu-item" id="submit-posttype-archive" />';
			echo '<span class="spinner"></span>';
			echo '</span>';
			echo '</p>';

		endif;
	}

}