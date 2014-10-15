<?php
/**
 * This model creates and adds custom admin menus to Wordpress.
 *
 * Required classes: Database, Functions
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

class Admin_Menu {

	public $args = [
		'type' => 'options_page',			// Defines the type of page to create ('options_page' = child of settings, 'menu_page' = root level menu item, 'submenu_page' = child of root level menu item)
		'title' => '',						// Admin menu title (will be converted and used as html ID attr)
		'menu_title' => '',					// Display name that appears in the sidebar
		'icon' => '',						// Icon displayed in the sidebar (ONLY if 'type' = 'menu_page')
		'menu_position' => 100,				// Location in the sidebar (ONLY if 'type' = 'menu_page') (5 - below Posts, 10 - below Media, 15 - below Links, 20 - below Pages, 25 - below comments, 60 - below first separator, 65 - below Plugins, 70 - below Users, 75 - below Tools, 80 - below Settings, 100 - below second separator)
		'parent' => 'options-general.php',	// ID of the parent underwhich to display the submenu (ONLY if 'type' = 'submenu_page')
		'capability' => 'manage_options',	// Required capability for the admin menu to be displayed to the user
		'view' => NULL,						// Path to the admin menu view (if set to null a default view will be created)
		'fields' => array(),				// Fields array for the admin menu (if 'table' argument is set 'fields' should remain an empty array and will be automatically filled)
		'array' => array(),					// Array of custom data to pass to the view
		'table' => NULL						// Table array (defined if Admin_Menu is created by a Meta_Box class)
	];

	public function __construct( $args ) {

		$this->args = wp_parse_args( $args, $this->args );
		$this->args['id'] = Functions::str_smash( $this->args['title'] );
		$this->args['menu_title'] = empty( $this->args['menu_title'] ) ? $this->args['title'] : $this->args['menu_title'];
		
		// Update the 'fields' argument with the table structure
		if( isset( $this->args['table'] ) ) {
			$this->args['fields'] = $this->args['table']['structure'];
		}

		$this->save_default_values();
		$this->wp_hooks();

	}

	protected function save_default_values() {

		$id = $this->args['id'];
		$fields = $this->args['fields'];

		if( !empty( $fields ) ) :

			foreach( $fields as $name => $value ) {
				if( $name !== 'id' && $name !== 'post_id' && !get_option( $id . '_' . $name ) ) {
					$default = isset( $value['default'] ) ? $value['default'] : '';
					add_option( $id . '_' . $name, $default );
				}
			}

		endif;

	}

	protected function wp_hooks() {

		// Create the admin menu
		add_action( 'admin_menu', array( &$this, 'setup_admin_menu' ) );

		// Update the admin menu options
		add_action( 'admin_init', array( &$this, 'save_admin_menu' ) );

	}

	public function setup_admin_menu() {

		extract( $this->args );

		switch( $type ) {

			case "options_page" :
				add_options_page( $title, $menu_title, $capability, $id, array( &$this, 'setup_admin_menu_view' ) );
				break;

			case "menu_page" :
				add_menu_page( $title, $menu_title, $capability, $id, array( &$this, 'setup_admin_menu_view' ), $icon, $menu_position );
				break;

			case "submenu_page" :
				add_submenu_page( $parent, $title, $menu_title, $capability, $id, array( &$this, 'setup_admin_menu_view' ) );
				break;

		}

	}

	public function setup_admin_menu_view() {

		$data = array();
		foreach( $this->args['fields'] as $name => $value ) {
			$default = isset( $value['default'] ) ? $value['default'] : '';
			$data[$name] = stripslashes( get_option( $this->args['id'] . '_' . $name, $default ) );
		}

		if( !empty( $this->args['view'] ) ) :

			$array = $this->args['array'];
			include_once $this->args['view'];

		else :

			echo $this->default_admin_menu_view( $data );

		endif;

	}

	public function default_admin_menu_view( $data ) {

		$id = $this->args['id'];
		$title = $this->args['title'];
		$action = ( $this->args['type'] == 'menu_page' || $this->args['type'] == 'submenu_page' ) ? "admin.php?page=$id" : "options-general.php?page=$id";

		// Title
		$s = "<div id='$id' class='wrap'>";
		$s .= "<h2>$title</h2>";
		$s .= "<form method='post' action='$action'>";

		// Form Fields
		$s .= "<table class='form-table meta-box-form-section'>";
		$s .= "<tbody>";
		foreach( $data as $name => $value ) {
			$s .= $this->field( $name, $value );
		}
		$s .= "</tbody>";
		$s .= "</table>";

		// Buttons
		$s .= "<p class='submit'>";
		$s .= "<input type='submit' name='submit' id='submit' class='button button-primary' value='Save Changes'>";
		$s .= "</p>";

		// Close
		$s .= "</form>";
		$s .= "</div>";

		return $s;

	}

	public function field( $name, $value ) {

		$field = $this->args['fields'][$name];
		$type = !empty( $field['type'] ) ? $field['type'] : '';
		$name = $this->args['id'] . '_' . $name;
		$options = !empty( $field['options'] ) ? $field['options'] : array();
		$class = 'admin-menu-form-field';
		$label = isset( $field['label'] ) ? $field['label'] : ucwords( str_replace( '_', ' ', $name ) );
		$id = !empty( $field['id'] ) ? $field['id'] : $this->args['id'] . '-' . str_replace( '_', '-', $name );
		$value = !empty( $value ) ? $value : '';
		$placeholder = !empty( $field['placeholder'] ) ? $field['placeholder'] : '';
		$field = "";

		switch( $type ) {
			case 'text' :

				$field .= "<tr>";
				$field .= "<th scope='row'><label for='$id' style='display:block; overflow:hidden;'>$label</label></th>";
				$field .= "<td><input type='$type' id='$id' name='$name' value='$value' placeholder='$placeholder'></td>";
				$field .= "</tr>";

				break;
			case 'number' :

				$field .= "<tr>";
				$field .= "<th scope='row'><label for='$id' style='display:block; overflow:hidden;'>$label</label></th>";
				$field .= "<td><input type='$type' id='$id' name='$name' value='$value' placeholder='$placeholder'></td>";
				$field .= "</tr>";

				break;
			case 'password' :

				$field .= "<tr>";
				$field .= "<th scope='row'><label for='$id' style='display:block; overflow:hidden;'>$label</label></th>";
				$field .= "<td><input type='$type' id='$id' name='$name' value='$value' placeholder='$placeholder'></td>";
				$field .= "</tr>";

				break;
			case 'email' :

				$field .= "<tr>";
				$field .= "<th scope='row'><label for='$id' style='display:block; overflow:hidden;'>$label</label></th>";
				$field .= "<td><input type='$type' id='$id' name='$name' value='$value' placeholder='$placeholder'></td>";
				$field .= "</tr>";

				break;
			case 'url' :

				$field .= "<tr>";
				$field .= "<th scope='row'><label for='$id' style='display:block; overflow:hidden;'>$label</label></th>";
				$field .= "<td><input type='$type' id='$id' name='$name' value='$value' placeholder='$placeholder'></td>";
				$field .= "</tr>";

				break;
			case 'tel' :

				$field .= "<tr>";
				$field .= "<th scope='row'><label for='$id' style='display:block; overflow:hidden;'>$label</label></th>";
				$field .= "<td><input type='$type' id='$id' name='$name' value='$value' placeholder='$placeholder'></td>";
				$field .= "</tr>";

				break;
			case 'textarea' :

				$field .= "<tr>";
				$field .= "<th scope='row'><label for='$id' style='display:block; overflow:hidden;'>$label</label></th>";
				$field .= "<td><textarea id='$id' name='$name' rows='10' cols='50'>$value</textarea>";
				$field .= "</tr>";

				break;
			case 'select' :

				$field .= "<tr>";
				$field .= "<th scope='row'><label for='$id' style='display:block; overflow:hidden;'>$label</label></th>";
				$field .= "<td><select id='$id' name='$name'>";
				foreach( $options as $opt_value => $opt_label ) {
					$selected = ( $opt_value === $value ) ? " selected='selected'" : "";
					$field .= "<option value='$opt_value'$selected>$opt_label</option>";
				}
				$field .= "</select></td>";
				$field .= "</tr>";

				break;
			case 'checkbox' :

				$checked = ( (boolean)$value ) ? " checked='checked'" : "";
				$field .= "<tr>";
				$field .= "<th scope='row'>$title</th>";
				$field .= "<td><fieldset>";
				$field .= "<legend class='screen-reader-text'>$title</legend>";
				$field .= "<label for='$id'><input type='hidden' id='hidden-$id' name='$name' value='$value'><input type='$type' id='$id'$checked>&nbsp;$label</label>";
				$field .= "</fieldset></td>";
				$field .= "</tr>";

				break;
			case 'radio' :

				$field .= "<tr>";
				$field .= "<th scope='row'>$title</th>";
				$field .= "<td><fieldset>";
				$field .= "<legend class='screen-reader-text'>$title</legend>";
				foreach( $options as $opt_value => $opt_label ) {
					$checked = ( $opt_value === $value ) ? " checked='checked'" : "";
					$field .= "<label title='$value'><input type='$type' name='$name' value='$option_value'$checked><span>$option_label</span></label><br>";
				}
				$field .= "</fieldset></td>";
				$field .= "</tr>";

				break;
			case 'hidden' :

				$field .= "<input type='$type' name='$name' value='$value'>";

				break;
		}

		return $field;

	}

	public function save_admin_menu() {

		$id = $this->args['id'];

		if( !empty( $this->args['fields'] ) ) :

			foreach( $this->args['fields'] as $name => $value ) {
				if( isset( $_POST[$id.'_'.$name] ) ) {
					update_option( $id . '_' . $name, $_POST[$id.'_'.$name] );
				}
			}

		endif;

	}

}