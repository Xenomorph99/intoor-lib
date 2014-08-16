<?php
/**
 * This model creates and adds custom meta boxes to Wordpress admin pages.
 *
 * Required models: Database, Functions, Admin_Menu
 *
 * Future:
 * - Create ability to save/retrieve data from wordpress options table instead of creating a new table
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

class Meta_Box {

	public $data = array();
	public $defaults = array();
	public $settings = array(
		'title' => '',					// Meta box title (will be converted and used as html ID attr)
		'callback' => 'none',			// Callback function
		'post_type' => array( 'post' ),	// Type of screen(s) on which to show the meta box
		'context' => 'advanced',		// Where on the screen the meta box should be shown (normal, advanced, side)
		'priority' => 'default',		// Priority within the context where the meta box will be shown (high, core, default, low)
		'callback_args' => NULL,		// Arguments to pass into the callback function
		'view' => NULL,					// Path to the meta box view
		'recursive' => false,			// Defines whether a meta box can have multiple database rows for a single table
		'admin_menu' => false,			// Include an admin menu page to control default values, etc. (true, false)
		'admin_view' => NULL,			// Path to the admin menu view (if set to null a default view will be created)
		'array' => array(),				// Array of additional data to be passed to a view
		'table' => array()				// Database table array
	);

	public function __construct( $arr ) {

		$this->settings = Functions::merge_array( $arr, $this->settings );
		$this->settings['meta_box_id'] = Functions::str_smash( $this->settings['title'] );

		foreach( $this->settings['table']['structure'] as $name => $value ) {
			if( $name !== 'post_id' ) {
				if( get_option( $this->settings['meta_box_id'] . '_' . $name ) ) {
					$this->defaults[$name] = get_option( $this->settings['meta_box_id'] . '_' . $name );
				} else {
					$default_value = ( isset( $value[2] ) ) ? $value[2] : '';
					add_option( $this->settings['meta_box_id'] . '_' . $name, $default_value );
					$this->defaults[$name] = $default_value;
				}
			}
		}

		if( $this->settings['admin_menu'] ) {
			$this->setup_admin_menu();
		}

		$this->install_tables();
		$this->wp_hooks();

	}

	protected function setup_admin_menu() {

		extract( $this->settings );

		$admin_menu = array(
			'title' => $title,
			'view' => $admin_view,
			'defaults' => $this->defaults,
			'table' => $table
		);

		new Admin_Menu( $admin_menu );

	}

	protected function install_tables() {

		Database::install_table( $this->settings['table'] );

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

		extract( $this->settings );

		foreach( $post_type as $type ) {
			add_meta_box( $meta_box_id, $title, array( &$this, 'setup_meta_box_view' ), $type, $context, $priority, $callback_args );
		}

	}

	public function setup_meta_box_view() {

		$this->defaults['post_id'] = (string) get_the_ID();
		$data = Database::get_results( $this->settings['table'], null, array( 'post_id' => (string) get_the_ID() ) );
		$this->data = ( !empty( $data ) ) ? $data : array( $this->defaults );

		if( !empty( $this->settings['view'] ) ) {
			extract( $this->settings );
			$prefix = $table['prefix'] . '_';
			include_once $this->settings['view'];
		} else {
			$this->default_meta_box_view();
		}

	}

	public function default_meta_box_view() {

		extract( $this->settings );
		$prefix = $table['prefix'] . '_';

		if( $recursive ) {

			echo $this->hidden_defaults();
			echo $this->meta_box_structure();
			echo $this->recursive_buttons();

		} elseif( $admin_menu ) {

			echo $this->hidden_defaults();
			echo $this->meta_box_structure();
			echo $this->reset_button();

		} else {

			echo $this->meta_box_structure();

		}

	}

	public function meta_box_structure() {

		extract( $this->settings );
		$prefix = $table['prefix'] . '_';
		$s = '';

		for( $i = 0; $i < count( $this->data ); $i++ ) {
			extract( $this->data[$i] );
			$row_id = ( !empty( $id ) ) ? $id : '';
			$s .= '<div class="meta-box-form-section">';
			$s .= '<input type="hidden" class="meta-box-section-id" name="' . $prefix . 'id[]" value="' . $row_id . '">';
			foreach( $this->data[$i] as $data_name => $data_value ) {
				if( $data_name !== 'id' ) {
					$s .= $this->field( $i, $data_name );
				}
			}
			$s .= '</div>';
		}

		return $s;

	}

	public function hidden_defaults() {

		extract( $this->settings );
		$prefix = $table['prefix'] . '_';

		$s = '<div class="meta-box-form-defaults" style="display:none; visibility:hidden;">';
		$s .= '<input type="hidden" class="meta-box-section-id" name="' . $prefix . 'id[]" value="0">';
		foreach( $this->defaults as $default_name => $default_value ) {
			if( $default_name !== 'id' ) {
				$s .= $this->field( NULL, $default_name, NULL, NULL, NULL, true );
			}
		}
		$s .= '</div>';
		return $s;

	}

	public function reset_button() {

		$s = '<div class="meta-box-buttons">';
		$s .= '<button class="button meta-box-restore-defaults">Reset</button>';
		$s .= '</div>';
		return $s;

	}

	public function recursive_buttons() {

		$s = '<div class="meta-box-buttons">';
		$s .= '<button class="button meta-box-add-form-section">+</button>';
		$s .= '<button class="button meta-box-remove-form-section">-</button>';
		$s .= ( $this->settings['admin_menu'] ) ? '<button class="button meta-box-restore-defaults">Reset</button>' : '';
		$s .= '</div>';
		return $s;

	}

	public function field( $i, $key, $label = NULL, $id = NULL, $value = NULL, $is_hidden = false ) {

		// Define variables
		$arr = $this->settings['table']['structure'][$key];
		$prefix = ( isset( $this->settings['table']['prefix'] ) ) ? $this->settings['table']['prefix'] . '_' : '';

		$field_type = ( !empty( $arr[3] ) ) ? $arr[3] : '';
		$name = $prefix . $key . "[]";
		$options = ( !empty( $arr[4] ) ) ? $arr[4] : array();
		$class = 'meta-box-form-field';

		if( isset( $value ) ) {
			$value = $value;
		} else {
			$data_value = ( isset( $this->data[$i][$key] ) ) ? $this->data[$i][$key] : $this->defaults[$key];
			$value = get_option( $this->settings['id'] . '_' . $key, $data_value );
		}

		if( isset( $label ) ) {
			$label = $label;
		} else {
			if( isset( $arr[5] ) ) {
				$label = $arr[5];
			} else {
				$label = str_replace( '_', ' ', $key );
				$label = ucwords( $label );
			}
		}

		if( isset( $id ) ) {
			$id = $id;
		} else {
			$id = ( isset( $arr[6] ) ) ? $arr[6] : $this->settings['meta_box_id'] . '-' . $key;
		}

		$id = ( !$is_hidden ) ? $id : '';

		// Build & return string
		$field = '';
		switch( $field_type ) {
			case 'text' :

				$field .= "<span class='field-type-$field_type'>";
				$field .= "<label for='$id'>$label:</label><br>";
				$field .= '<input type="' . $field_type . '" class="' . $class . '" id="' . $id . '" name="' . $name . '" value="' . $value . '">';
				$field .= "</span><br>";

				break;
			case 'number' :
				
				$field .= "<span class='field-type-$field_type'>";
				$field .= "<label for='$id'>$label:</label><br>";
				$field .= '<input type="' . $field_type . '" class="' . $class . '" id="' . $id . '" name="' . $name . '" value="' . $value . '">';
				$field .= "</span><br>";

				break;
			case 'password' :
				
				$field .= "<span class='field-type-$field_type'>";
				$field .= "<label for='$id'>$label:</label><br>";
				$field .= '<input type="' . $field_type . '" class="' . $class . '" id="' . $id . '" name="' . $name . '" value="' . $value . '">';
				$field .= "</span><br>";

				break;
			case 'email' :
				
				$field .= "<span class='field-type-$field_type'>";
				$field .= "<label for='$id'>$label:</label><br>";
				$field .= '<input type="' . $field_type . '" class="' . $class . '" id="' . $id . '" name="' . $name . '" value="' . $value . '">';
				$field .= "</span><br>";

				break;
			case 'url' :
				
				$field .= "<span class='field-type-$field_type'>";
				$field .= "<label for='$id'>$label:</label><br>";
				$field .= '<input type="' . $field_type . '" class="' . $class . '" id="' . $id . '" name="' . $name . '" value="' . $value . '">';
				$field .= "</span><br>";

				break;
			case 'tel' :
				
				$field .= "<span class='field-type-$field_type'>";
				$field .= "<label for='$id'>$label:</label><br>";
				$field .= '<input type="' . $field_type . '" class="' . $class . '" id="' . $id . '" name="' . $name . '" value="' . $value . '">';
				$field .= "</span><br>";

				break;
			case 'textarea' :

				$field .= "<span class='field-type-$field_type'>";
				$field .= "<label for='$id'>$label:</label><br>";
				$field .= '<textarea class="' . $class . '" id="' . $id . '" name="' . $name . '">' . $value . '</textarea>';
				$field .= "</span><br>";

				break;
			case 'select' :

				$field .= "<span class='field-type-$field_type'>";
				$field .= "<label for='$id'>$label:&nbsp;&nbsp;</label>";
				$field .= '<select class="' . $class . '" id="' . $id . '" name="' . $name . '">';
				$field .= '<option value="">Select:</option>';
				foreach( $options as $option_value => $option_label ) {
					$selected = ( $option_value == $value ) ? " selected='selected'" : "";
					$field .= '<option value="' . $option_value . '"' . $selected . '>' . $option_label . '</option>';
				}
				$field .= '</select>';
				$field .= '</span><br>';

				break;

			case 'checkbox' :

				$checked = ( $value ) ? " checked='checked'" : '';
				$field .= "<span class='field-type-$field_type'>";
				$field .= '<input type="hidden" id="hidden-' . $id . '" name="' . $name . '" value="' . $value . '">';
				$field .= '<input type="' . $field_type . '" class="' . $class . '" id="' . $id . '"' . $checked . '>';
				$field .= "<label for='$id'>$label</label>";
				$field .= "</span><br>";

				break;
			case 'radio' :

				$field .= "<span class='field-type-$field_type'>";
				foreach( $options as $radio_value => $radio_label ) {
					$radio_id = $id . '-' . Functions::str_smash( $radio_value );
					$checked = ( $radio_value == $value ) ? " checked='checked'" : '';
					$field .= '<input type="' . $field_type . '" class="' . $class . '" id="' . $radio_id . '" name="' . $name . '" value="' . $radio_value . '"' . $checked . '>';
					$field .= "<label for='$radio_id'>$radio_label</label>";
				}
				$field .= "</span><br>";

				break;
			case 'hidden' :

				$field .= '<input type="' . $field_type . '" class="' . $class . '" name="' . $name . '" value="' . $value . '">';

				break;
			case 'checkbox-list' :

				$field .= "<div class='categorydiv meta-box-checkbox-container hide-if-no-js'>";
				$field .= "<ul class='category-tabs'><li class='tabs'>" . $label . "</li></ul>";
				$field .= '<input type="hidden" class="checkbox-container-controller" name="' . $name . '" value="' . $value . '">';
				$field .= "<div class='tabs-panel'>";
				$field .= "<ul class='categorychecklist form-no-clear'>";
				$values = ( !empty( $value ) ) ? explode( ',', $value ) : array();
				foreach( $options as $option_value => $option_label ) {
					$checked = ( in_array( $option_value, $values ) ) ? " checked='checked'" : '';
					$field .= '<li><label class="selectit"><input type="checkbox" class="contained-checkbox" value="' . $option_value . '"' . $checked . '>' . $option_label . '</label></li>';
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
		if( $this->save_count < 1 ) {

			// Define variables
			$prefix = $this->settings['table']['prefix'] . '_';
			$num = 0;
			$total = ( isset( $_POST[$prefix.'id'] ) ) ? count( $_POST[$prefix.'id'] ) : 1;

			if( $this->settings['recursive'] ) {
				$num = 1;
			}
			if( $this->settings['admin_menu'] ) {
				$num = 1;
			}

			// Loop through the posted data
			for( $i = $num; $i < $total; $i++ ) {

				// Delete data
				if( isset( $_POST[$prefix.'id'] ) && $_POST[$prefix.'id'][$i] < 0 ) {

					$row_id = str_replace( '-', '', $_POST[$prefix.'id'][$i] );
					Database::delete_row( $this->settings['table']['name'], 'id', $row_id );

				// Save data
				} else {

					$data = array();
					foreach( $this->settings['table']['structure'] as $name => $value ) {
						$data[$name] = ( isset( $_POST[$prefix.$name][$i] ) ) ? $_POST[$prefix.$name][$i] : '';
					}

					if( isset( $_POST[$prefix.'id'] ) ) {
						$row_id = $_POST[$prefix.'id'][$i];
						if( !empty( $row_id ) ) {
							Database::update_row( $this->settings['table'], 'id', $row_id, $data );
						} else {
							Database::insert_row( $this->settings['table'], $data );
						}
					}
				}

			}

		}

		$this->save_count++;

	}

	public function delete_post_data() {

		global $post;

		if( in_array( $post->post_type, $this->settings['post_type'] ) ) {
			Database::delete_row( $this->settings['table'], 'post_id', $post->ID );
		}

	}

}