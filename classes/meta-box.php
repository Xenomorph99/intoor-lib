<?php
/**
 * This model creates and adds custom meta boxes to Wordpress admin pages.
 *
 * Required models: Database, Functions, Admin_Menu
 *
 * Future:
 * - Create ability to save/retrieve data from wordpress options table instead of creating a new table
 *
 * @package     Интоор Library (intoor)
 * @author      Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright   2014 Hazard Media Group LLC
 * @license     MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link        https://github.com/Alekhen/intoor-lib
 * @version     Release: 1.2
 */

if( !defined( 'INTOOR_RESTRICT_ACCESS' ) || !INTOOR_RESTRICT_ACCESS ) { die( 'Unauthorized Access' ); }

class Meta_Box {

	public $args = [
		'title' => '',                  // Meta box title (will be converted and used as html ID attr)
		'callback' => 'none',           // Callback function
		'post_type' => array( 'post' ), // Type of screen(s) on which to show the meta box
		'ref_post_id' => true,          // Reference the custom meta box database table by post_id and include post_id column
		'context' => 'advanced',        // Where on the screen the meta box should be shown (normal, advanced, side)
		'priority' => 'default',        // Priority within the context where the meta box will be shown (high, core, default, low)
		'callback_args' => NULL,        // Arguments to pass into the callback function
		'view' => NULL,                 // Path to the meta box view
		'recursive' => false,           // Defines whether a meta box can have multiple database rows for a single table
		'admin_menu' => false,          // Include an admin menu page to control default values, etc. (true, false)
		'admin_view' => NULL,           // Path to the admin menu view (if set to null a default view will be created)
		'array' => array(),             // Array of additional data to be passed to a view
		'table' => array(),             // Database table array
	];

	public function __construct( $args) {

		$this->args = wp_parse_args( $args, $this->args );
		$this->args['meta_box_id'] = Functions::str_smash( $this->args['title'] );
		$this->args['table']['structure'] = ( $this->args['ref_post_id'] ) ? array_merge( array( 'post_id' => [ 'sql' => 'BIGINT(20)', 'type' => 'hidden' ] ), $this->args['table']['structure'] ) : $this->args['table']['structure'];

		if( $this->args['admin_menu'] ) {
			$this->setup_admin_menu();
		}

		$this->install_table();
		$this->wp_hooks();

	}

	protected function setup_admin_menu() {

		new Admin_Menu( array( 'title' => $this->args['title'], 'view' => $this->args['admin_view'], 'table' => $this->args['table'] ) );

	}

	protected function install_table() {

		Database::install_table( $this->args['table'] );

	}

	protected function wp_hooks() {

		// Setup the custom meta box
		add_action( 'add_meta_boxes', array( &$this, 'setup_meta_box' ) );

		// Save the meta box form field values to the database
		add_action( 'save_post', array( &$this, 'save_meta_box' ) );

		// Remove data on post delete
		add_action( 'before_delete_post', array( &$this, 'delete_post_data' ) );

	}

	public function setup_meta_box() {

		extract( $this->args );

		foreach( $post_type as $type ) {
			add_meta_box( $meta_box_id, $title, array( &$this, 'setup_meta_box_view' ), $type, $context, $priority, $callback_args );
		}

	}

	public function setup_meta_box_view() {

		$data = Database::get_results( $this->args['table'], null, array( 'post_id' => (string)get_the_ID() ) );

		// Include the specified view or create a standard default meta box view
		if( !empty( $this->args['view'] ) ) :

			$array = $this->args['array'];
			$prefix = $this->args['table']['prefix'] . '_';
			include_once $this->args['view'];

		else :

			$this->default_meta_box_view( $data );

		endif;

	}

	public function default_meta_box_view( $data ) {

		if( $this->args['recursive'] ) {

			echo $this->create_hidden_defaults();
			echo $this->create_meta_box_structure( $data );
			echo $this->create_recursive_buttons();

		} elseif( $this->args['admin_menu'] ) {

			echo $this->create_hidden_defaults();
			echo $this->create_meta_box_structure( $data );
			echo $this->create_reset_button();

		} else {

			echo $this->create_meta_box_structure( $data );

		}

	}

	public function create_meta_box_structure( $data ) {
		
		$prefix = $this->args['table']['prefix'] . "_";
		$s = "";

		for( $i = 0; $i < count( $data ); $i++ ) {

			extract( $data[$i] );

			$row_id = ( !empty( $id ) ) ? $id : "";
			$s .= "<div class='meta-box-form-section'>";
			$s .= "<input type='hidden' class='meta-box-section-id' name='{$prefix}id[]' value='$row_id'>";

			foreach( $data[$i] as $column => $value ) {
				if( $column !== 'id' ) {
					$s .= $this->field( $i, $prefix, $column, $value );
				}
			}

			$s .= "</div>";

		}

		return $s;

	}

	public function create_hidden_defaults() {

		$prefix = $this->args['table']['prefix'] . '_';
		$s = "<div class='meta-box-form-defaults' style='display:none; visibility:hidden;'>";
		$s .= "<input type='hidden' class='meta-box-section-id' name='{$prefix}id[]' value='0'>";

		foreach( $this->args['table']['structure'] as $column => $value ) {
			if( $column !== 'id' ) {
				$s .= $this->field( NULL, $prefix, $column, $value['default'] );
			}
		}

		$s .= "</div>";

		return $s;

	}

	public function create_reset_button() {

		$s = "<div class='meta-box-buttons'>";
		$s .= "<button class='button meta-box-restore-defaults'>Reset</button>";
		$s .= "</div>";
		return $s;

	}

	public function create_recursive_buttons() {

		$s = "<div class='meta-box-buttons'>";
		$s .= "<button class='button meta-box-add-form-section'>+</button>";
		$s .= "<button class='button meta-box-remove-form-section'>-</button>";
		$s .= ( $this->args['admin_menu'] ) ? "<button class='button meta-box-restore-defaults'>Reset</button>" : "";
		$s .= "</div>";
		return $s;

	}

	public function field( $i, $prefix, $column, $value ) {

		$col = $this->args['table']['structure'][$column];
		$type = !empty( $col['type'] ) ? $col['type'] : '';
		$name = $prefix . $column . "[]";
		$options = !empty( $col['options'] ) ? $col['options'] : array();
		$class = 'meta-box-form-field';
		$label = isset( $col['label'] ) ? $col['label'] : ucwords( str_replace( '_', ' ', $column ) );
		$id = !empty( $col['id'] ) ? $col['id'] : $this->args['meta_box_id'] . '-' . str_replace( '_', '-', $column );
		$value = !empty( $value ) ? $value : '';
		$placeholder = !empty( $col['placeholder'] ) ? $col['placeholder'] : '';
		$field = "";

		switch( $type ) {
			case 'text' :

				$field .= "<span class='field-type-$type'>";
				$field .= "<label for='$id'>$label:</label><br>";
				$field .= "<input type='$type' class='$class' id='$id' name='$name' value='$value' placeholder='$placeholder'>";
				$field .= "</span><br>";

				break;
			case 'number' :
				
				$field .= "<span class='field-type-$type'>";
				$field .= "<label for='$id'>$label:</label><br>";
				$field .= "<input type='$type' class='$class' id='$id' name='$name' value='$value' placeholder='$placeholder'>";
				$field .= "</span><br>";

				break;
			case 'password' :
				
				$field .= "<span class='field-type-$type'>";
				$field .= "<label for='$id'>$label:</label><br>";
				$field .= "<input type='$type' class='$class' id='$id' name='$name' value='$value' placeholder='$placeholder'>";
				$field .= "</span><br>";

				break;
			case 'email' :
				
				$field .= "<span class='field-type-$type'>";
				$field .= "<label for='$id'>$label:</label><br>";
				$field .= "<input type='$type' class='$class' id='$id' name='$name' value='$value' placeholder='$placeholder'>";
				$field .= "</span><br>";

				break;
			case 'url' :
				
				$field .= "<span class='field-type-$type'>";
				$field .= "<label for='$id'>$label:</label><br>";
				$field .= "<input type='$type' class='$class' id='$id' name='$name' value='$value' placeholder='$placeholder'>";
				$field .= "</span><br>";

				break;
			case 'tel' :
				
				$field .= "<span class='field-type-$type'>";
				$field .= "<label for='$id'>$label:</label><br>";
				$field .= "<input type='$type' class='$class' id='$id' name='$name' value='$value' placeholder='$placeholder'>";
				$field .= "</span><br>";

				break;
			case 'textarea' :

				$field .= "<span class='field-type-$type'>";
				$field .= "<label for='$id'>$label:</label><br>";
				$field .= "<textarea class='$class' id='$id' name='$name'>$value</textarea>";
				$field .= "</span><br>";

				break;
			case 'select' :

				$field .= "<span class='field-type-$type'>";
				$field .= "<label for='$id'>$label:&nbsp;&nbsp;</label>";
				$field .= "<select class='$class' id='$id' name='$name'>";
				$field .= "<option value=''>Select:</option>";
				foreach( $options as $opt_value => $opt_label ) {
					$selected = ( $opt_value == $value ) ? " selected='selected'" : "";
					$field .= "<option value='$opt_value'$selected>$opt_label</option>";
				}
				$field .= "</select>";
				$field .= "</span><br>";

				break;

			case 'checkbox' :

				$checked = ( (boolean)$value ) ? " checked='checked'" : "";
				$field .= "<span class='field-type-$type'>";
				$field .= "<input type='hidden' id='hidden-$id' name='$name' value='$value'>";
				$field .= "<input type='$type' class='$class' id='$id'$checked>";
				$field .= "<label for='$id'>$label</label>";
				$field .= "</span><br>";

				break;
			case 'radio' :

				$field .= "<span class='field-type-$type'>";
				foreach( $options as $radio_value => $radio_label ) {
					$radio_id = $id . '-' . Functions::str_smash( $radio_value );
					$checked = ( $radio_value == $value ) ? " checked='checked'" : "";
					$field .= "<input type='$type' class='$class' id='$radio_id' name='$name' value='$radio_value'$checked>";
					$field .= "<label for='$radio_id'>$radio_label</label>";
				}
				$field .= "</span><br>";

				break;
			case 'hidden' :

				$field .= "<input type='$type' class='$class' name='$name' value='$value'>";

				break;
			case 'checkbox-list' :

				$field .= "<div class='categorydiv meta-box-checkbox-container hide-if-no-js'>";
				$field .= "<ul class='category-tabs'><li class='tabs'>$label</li></ul>";
				$field .= "<input type='hidden' class='checkbox-container-controller' name='$name' value='$value'>";
				$field .= "<div class='tabs-panel'>";
				$field .= "<ul class='categorychecklist form-no-clear'>";
				$values = !empty( $value ) ? explode( ',', $value ) : array();
				foreach( $options as $opt_value => $opt_label ) {
					$checked = ( in_array( $opt_value, $values ) ) ? " checked='checked'" : '';
					$field .= "<li><label class='selectit'><input type='checkbox' class='contained-checkbox' value='$opt_value'$checked>$opt_label</label></li>";
				}
				$field .= "</ul>";
				$field .= "</div>";
				$field .= "</div>";

				break;

		}

		return $field;

	}

	public $save_count = 0;
	public function save_meta_box() {

		// Run this method only once
		if( $this->save_count < 1 ) :

			$prefix = $this->args['table']['prefix'] . '_';
			$total = isset( $_POST[$prefix.'id'] ) ? count( $_POST[$prefix.'id'] ) : 1;
			$num = 0;

			if( $this->args['recursive'] ) {
				$num = 1;
			}
			if( $this->args['admin_menu'] ) {
				$num = 1;
			}

			// Loop through the posted data
			for( $i = $num; $i < $total; $i++ ) {

				// Delete data
				if( isset( $_POST[$prefix.'id'] ) && $_POST[$prefix.'id'][$i] < 0 ) :

					$row_id = str_replace( '-', '', $_POST[$prefix.'id'][$i] );
					Database::delete_row( $this->args['table'], 'id', $row_id );

				// Save data
				else :

					$data = array();
					foreach( $this->args['table']['structure'] as $column => $value ) {
						$data[$column] = isset( $_POST[$prefix.$column][$i] ) ? $_POST[$prefix.$column][$i] : '';
					}

					if( isset( $_POST[$prefix.'id'] ) ) {
						$row_id = $_POST[$prefix.'id'][$i];
						if( !empty( $row_id ) ) {
							Database::update_row( $this->args['table'], 'id', $row_id, $data );
						} else {
							Database::insert_row( $this->args['table'], $data );
						}
					}
				
				endif;

			}

		endif;

		$this->save_count++;

	}

	public function delete_post_data() {

		global $post;

		if( in_array( $post->post_type, $this->args['post_type'] ) ) :

			Database::delete_row( $this->args['table'], 'post_id', $post->ID );
		
		endif;

	}

}