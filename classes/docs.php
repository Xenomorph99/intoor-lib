<?php
/**
 * This model manages code documentation.
 *
 * Required models: Admin_Menu
 *
 * @package     Интоор Library (intoor)
 * @author      Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright   2014 Hazard Media Group LLC
 * @license     MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link        https://github.com/Alekhen/intoor-lib
 * @version     Release: 1.2
 */

if( !defined( 'INTOOR_RESTRICT_ACCESS' ) || !INTOOR_RESTRICT_ACCESS ) { die( 'Unauthorized Access' ); }

class Docs {

	public $args = array(
		'docs_dir' => 'docs',     // Location within the theme directory that contains the documentation files
	);

	public function __construct( $args ) {

		$this->setup();

	}

	protected function setup() {

		$args = array(
			'type' => 'menu_page',
			'title' => 'Documentation',
			'menu_title' => 'Docs',
			'icon' => 'dashicons-book',
			'menu_position' => 75,
			'view' => INTOOR_VIEWS_DIR . 'admin/docs.php',
			'array' => $this->get_docs()
		);

		new Admin_Menu( $args );

	}

	public function get_docs() {

		return scandir( get_template_directory_uri() . '/' . $this->args['docs_dir'] );

	}

}