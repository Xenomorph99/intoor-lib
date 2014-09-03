<?php
/**
 * This model adds additional functionality to categories including
 * additional form fields.
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

class Category_Form {

	public $defaults = array();
	public $args = array(
		'add_category_form_view' => NULL,	// Path to the add category custom form view
		'edit_category_form_view' => NULL,	// Path to the edit category custom form view
		'view_data' => array(),				// Array of data to be passed to the view
		'table' => array()					// Database table array
	);

	public function __construct( $args ) {

		$this->args = wp_parse_args( $args, $this->args );
		$this->args['id'] = $this->args['table']['name'];
		$this->args['table']['structure'] = array_merge( array( 'cat_id' => array( 'BIGINT(20)', false, NULL, 'hidden' ) ), $this->args['table']['structure'] );

		extract( $this->args );

		foreach( $table['structure'] as $name => $value ) {
			if( $name !== 'post_id' ) {
				if( get_option( $id . '_' . $name ) ) {
					$this->defaults[$name] = get_option( $id . '_' . $name );
				} else {
					$default_value = ( isset( $value[2] ) ) ? $value[2] : '';
					add_option( $id . '_' . $name, $default_value );
					$this->defaults[$name] = $default_value;
				}
			}
		}

		$this->setup_category_form();
		$this->wp_hooks();

	}

	protected function setup_category_form() {

		Database::install_table( $this->args['table'] );

	}

	protected function wp_hooks() {

		// Display custom form fields during the creation of a new category
		add_action( 'category_add_form_fields', array( &$this, 'setup_add_category_form_view' ), 10 );

		// Display populated custom form fields while editing a category
		add_action( 'category_edit_form_fields', array( &$this, 'setup_edit_category_form_view' ), 10, 2 );

		// Save custom form field data when category is created
		add_action( 'created_category', array( &$this, 'save_category_form' ), 10, 2 );

		// Save custom form field data when category is edited
		add_action( 'edited_category', array( &$this, 'save_category_form' ), 10, 2 );

		// Delete custom form field data when a category is deleted
		add_action( 'delete_category', array( &$this, 'delete_category_form_data' ) );

	}

	public function setup_add_category_form_view( $taxonomy ) {

		if( !empty( $this->args['add_category_form_view'] ) ) {
			include_once $this->args['add_category_form_view'];
		} else {
			$this->default_add_category_form_view();
		}

	}

	public function default_add_category_form_view() {

		extract( $this->args );

		foreach( $table['structure'] as $key => $value ) {
			echo ( $key !== 'cat_id' ) ? $this->add_category_form_field( $key, $value ) : '';
		}

	}

	public function add_category_form_field( $key, $arr ) {

		$f = '';
		$name = $this->args['table']['prefix'] . '_' . $key;
		$field_type = !empty( $arr[3] ) ? $arr[3] : '';
		$options = !empty( $arr[4] ) ? $arr[4] : array();
		$label = !empty( $arr[5] ) ? $arr[5] : $key;
		$id = !empty( $arr[6] ) ? $arr[6] : $this->args['id'] . '_' . $key;
		$desc = !empty( $arr[7] ) ? '<p class="description">' . $arr[7] . '</p>' : '';

		switch( $field_type ) {

			case 'text' :

				$f .= "<label for='$id'>$label</label>";
				$f .= "<input type='$field_type' id='$id' name='$name'>";

				break;
			case 'number' :

				$f .= "<label for='$id'>$label</label>";
				$f .= "<input type='$field_type' id='$id' name='$name'>";

				break;
			case 'textarea' :

				$f .= "<label for='$id'>$label</label>";
				$f .= "<textarea id='$id' name='$name'></textarea>";

				break;
			case 'select' :

				$f .= "<label for='$id'>$label</label>";
				$f .= "<select id='$id' name='$name'>";
				foreach( $options as $option_name => $option_value ) {
					$f .= "<option value='$option_name'>$option_value</option>";
				}
				$f .= "</select>";

				break;

		}

		return "<div class='form-field'>$f$desc</div>";

	}

	public function setup_edit_category_form_view( $tag, $taxonomy ) {

		if( !empty( $this->args['edit_category_form_view'] ) ) {
			include_once $this->args['edit_category_form_view'];
		} else {
			$this->default_edit_category_form_view( $tag );
		}

	}

	public function default_edit_category_form_view( $tag ) {

		extract( $this->args );

		foreach( $table['structure'] as $key => $value ) {
			echo ( $key !== 'cat_id' ) ? $this->edit_category_form_field( $key, $value ) : '';
		}

	}

	public function edit_category_form_field( $key, $arr ) {

		global $tag;

		$f = '';
		$name = $this->args['table']['prefix'] . '_' . $key;
		$field_type = !empty( $arr[3] ) ? $arr[3] : '';
		$options = !empty( $arr[4] ) ? $arr[4] : array();
		$label = !empty( $arr[5] ) ? $arr[5] : $key;
		$id = !empty( $arr[6] ) ? $arr[6] : $this->args['id'] . '_' . $key;
		$desc = !empty( $arr[7] ) ? '<p class="description">' . $arr[7] . '</p>' : '';

		// Retrieve data
		$data = Database::get_row( $this->args['table'], 'cat_id', $tag->term_id );
		$value = !empty( $data[$key] ) ? $data[$key] : '';

		switch( $field_type ) {

			case 'text' :

				$f .= "<th scope='row' valign='top'><label for='$id'>$label</label></th>";
				$f .= "<td><input type='$field_type' id='$id' name='$name' value='$value'>$desc</td>";

				break;
			case 'number' :

				$f .= "<th scope='row' valign='top'><label for='$id'>$label</label></th>";
				$f .= "<td><input type='$field_type' id='$id' name='$name' value='$value'>$desc</td>";

				break;
			case 'textarea' :

				$f .= "<th scope='row' valign='top'><label for='$id'>$label</label></th>";
				$f .= "<td><textarea id='$id' name='$name'>$value</textarea>$desc</td>";

				break;
			case 'select' :

				$f .= "<th scope='row' valign='top'><label for='$id'>$label</label></th>";
				$f .= "<td><select id='$id' name='$name'>";
				foreach( $options as $option_name => $option_value ) {
					$checked = ( $value == $option_name ) ? " selected='selected'" : "";
					$f .= "<option value='$option_name'$checked>$option_value</option>";
				}
				$f .= "</select>$desc</td>";

				break;

		}

		return "<tr class='form-field'>$f</tr>";

	}

	public $save_count = 0;
	public function save_category_form( $term_id ) {

		// Run this method only once
		if( $this->save_count < 1 ) :

			$prefix = $this->args['table']['prefix'] . '_';
			$data = Database::get_row( $this->args['table'], 'cat_id', $term_id );
			$new_row = ( !empty( $data['id'] ) ) ? false : true;

			foreach( $this->defaults as $name => $value ) {
				$data[$name] = isset( $_POST[$prefix.$name] ) ? $_POST[$prefix.$name] : $data[$name];
			}

			if( $new_row ) {
				$data['cat_id'] = $term_id;
				Database::insert_row( $this->args['table'], $data );
			} else {
				Database::update_row( $this->args['table'], 'cat_id', $term_id, $data );
			}

		endif;

		$this->save_count++;

	}

	public function delete_category_form_data( $term_id ) {

		Database::delete_row( $this->args['table'], 'cat_id', $term_id );

	}

}