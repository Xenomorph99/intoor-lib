<?php
/**
 * This model adds additional functionality to categories including
 * additional form fields.
 *
 * @package     Интоор Library (intoor)
 * @author      Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright   2014 Hazard Media Group LLC
 * @license     MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link        https://github.com/Alekhen/intoor-lib
 * @version     Release: 1.2
 */

if( !defined( 'INTOOR_RESTRICT_ACCESS' ) || !INTOOR_RESTRICT_ACCESS ) { die( 'Unauthorized Access' ); }

class Category_Form {

	public $args = [
		'add_category_form_view' => NULL,	// Path to the add category custom form view
		'edit_category_form_view' => NULL,	// Path to the edit category custom form view
		'view_data' => array(),				// Array of data to be passed to the view
		'table' => array()					// Database table array
	];

	public function __construct( $args ) {

		$this->args = wp_parse_args( $args, $this->args );
		$this->args['id'] = $this->args['table']['name'];
		$this->args['table']['structure'] = array_merge( array( 'cat_id' => [ 'sql' => 'BIGINT(20)', 'type' => 'hidden' ] ), $this->args['table']['structure'] );

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

		foreach( $this->args['table']['structure'] as $name => $args ) {
			echo ( $name !== 'cat_id' ) ? $this->add_category_form_field( $name, $args ) : '';
		}

	}

	public function add_category_form_field( $name, $args ) {

		extract( $args );

		$type = !empty( $type ) ? $type : '';
		$options = !empty( $options ) ? $options : array();
		$label = !empty( $label ) ? $label : ucwords( str_replace( '_', ' ', $name ) );
		$id = !empty( $id ) ? $id : $this->args['id'] . '_' . $name;
		$value = !empty( $default ) ? $default : '';
		$placeholder = !empty( $placeholder ) ? $placeholder : '';
		$desc = !empty( $desc ) ? '<p class="description">' . $desc . '</p>' : '';
		$s = '';

		switch( $type ) {

			case 'text' :

				$s .= '<label for="' . $id . '">' . $label . '</label>';
				$s .= '<input type="' . $type . '" id="' . $id . '" name="' . $name . '" value="' . $value . '" placeholder="' . $placeholder . '">';

				break;
			case 'number' :

				$s .= '<label for="' . $id . '">' . $label . '</label>';
				$s .= '<input type="' . $type . '" id="' . $id . '" name="' . $name . '" value="' . $value . '" placeholder="' . $placeholder . '">';

				break;
			case 'textarea' :

				$s .= '<label for="' . $id . '">' . $label . '</label>';
				$s .= '<textarea id="' . $id . '" name="' . $name . '" value="' . $value . '" placeholder="' . $placeholder . '"></textarea>';

				break;
			case 'select' :

				$s .= '<label for="' . $id . '">' . $label . '</label>';
				$s .= '<select id="' . $id . '" name="' . $name . '">';
				foreach( $options as $opt_name => $opt_value ) {
					$selected = ( $opt_name == $value ) ? ' selected="selected"' : '';
					$s .= '<option value="' . $opt_name . '"' . $selected . '>' . $opt_value . '</option>';
				}
				$s .= '</select>';

				break;

		}

		return '<div class="form-field">' . $s . $desc . '</div>';

	}

	public function setup_edit_category_form_view( $tag, $taxonomy ) {

		if( !empty( $this->args['edit_category_form_view'] ) ) {
			include_once $this->args['edit_category_form_view'];
		} else {
			$this->default_edit_category_form_view( $tag );
		}

	}

	public function default_edit_category_form_view( $tag ) {

		foreach( $this->args['table']['structure'] as $name => $args ) {
			echo ( $name !== 'cat_id' ) ? $this->edit_category_form_field( $name, $args ) : '';
		}

	}

	public function edit_category_form_field( $name, $args ) {

		global $tag;
		extract( $args );

		$type = !empty( $type ) ? $type : '';
		$options = !empty( $options ) ? $options : array();
		$label = !empty( $label ) ? $label : ucwords( str_replace( '_', ' ', $name ) );
		$id = !empty( $id ) ? $id : $this->args['id'] . '_' . $name;
		$placeholder = !empty( $placeholder ) ? $placeholder : '';
		$desc = !empty( $desc ) ? '<p class="description">' . $desc . '</p>' : '';
		$data = Database::get_row( $this->args['table'], 'cat_id', $tag->term_id );
		$value = !empty( $data[$name] ) ? html_entity_decode( $data[$name] ) : '';
		$s = '';

		switch( $type ) {

			case 'text' :

				$s .= '<th scope="row" valign="top"><label for="' . $id . '">' . $label . '</label></th>';
				$s .= '<td><input type="' . $type . '" id="' . $id . '" name="' . $name . '" value="' . $value . '" placeholder="' . $placeholder . '">' . $desc . '</td>';

				break;
			case 'number' :

				$s .= '<th scope="row" valign="top"><label for="' . $id . '>' . $label . '</label></th>';
				$s .= '<td><input type="' . $type . '" id="' . $id . '" name="' . $name . '" value="' . $value . '" placeholder="' . $placeholder . '">' . $desc . '</td>';

				break;
			case 'textarea' :

				$s .= '<th scope="row" valign="top"><label for="' . $id . '">' . $label . '</label></th>';
				$s .= '<td><textarea id="' . $id . '" name="' . $name . '" placeholder="' . $placeholder . '">' . $value . '</textarea>' . $desc . '</td>';

				break;
			case 'select' :

				$s .= '<th scope="row" valign="top"><label for="' . $id . '">' . $label . '</label></th>';
				$s .= '<td><select id="' . $id . '" name="' . $name . '">';
				foreach( $options as $opt_name => $opt_value ) {
					$selected = ( $opt_name == $value ) ? ' selected="selected"' : '';
					$s .= '<option value="' . $opt_name . '"' . $selected . '>' . $opt_value . '</option>';
				}
				$s .= '</select>' . $desc . '</td>';

				break;

		}

		return '<tr class="form-field">' . $s . '</tr>';

	}

	public $save_count = 0;
	public function save_category_form( $term_id ) {

		// Run this method only once
		if( $this->save_count < 1 ) :

			$id = $this->args['id'];
			$data = Database::get_row( $this->args['table'], 'cat_id', $term_id );

			foreach( $this->args['table']['structure'] as $name => $args ) {
				$data[$name] = isset( $_POST[$name] ) ? $_POST[$name] : $data[$name];
			}

			if( empty( $data['cat_id'] ) ) {
				$data['cat_id'] = $term_id;
				Database::insert_row( $this->args['table'], $data );
			} else {
				Database::update_row( $this->args['table'], 'cat_id', $term_id, $data );
			}

			$this->save_count++;

		endif;

	}

	public function delete_category_form_data( $term_id ) {

		Database::delete_row( $this->args['table'], 'cat_id', $term_id );

	}

}