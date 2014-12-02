<?php
/**
 * This model manages all functionality associated with the subscriber
 * mailing list.
 *
 * Required models: Database
 *
 * Future:
 * - Add support for more social media links
 * - Setup cron job to manage monthly/weekly newsletter distribution of latest posts
 * - Add MailChimp & other 3rd party integrations
 * - Add ability to send out email to members of the subscription list
 * - Shortcode and function capability to pull a subscribe form into any view
 * - Extract into Wordpress plugin format
 *
 * @package     Интоор Library (intoor)
 * @author      Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright   2014 Hazard Media Group LLC
 * @license     MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link        https://github.com/Alekhen/intoor-lib
 * @version     Release: 1.2
 */

if( !defined( 'INTOOR_RESTRICT_ACCESS' ) || !INTOOR_RESTRICT_ACCESS ) { die( 'Unauthorized Access' ); }

class Mailing_List {

	public static $settings = [
		'ajax' => [
			'type' => 'checkbox',
			'label' => 'Enabel AJAX Form Submit',
			'default' => '1'
		],
		'sender' => [
			'type' => 'text',
			'label' => 'Send Mail From',
			'placeholder' => 'no-reply@example.com'
		],
		'logo' => [
			'type' => 'url',
			'label' => 'Email Logo <small style="font-weight:normal;"><em>(height: 90px)</em></small>',
			'placeholder' => 'url'
		],
		'logo_width' => [
			'type' => 'number',
			'label' => 'Email Logo Width <small style="font-weight:normal;"><em>(px)</em></small>',
			'default' => '250'
		],
		'subscribe_banner' => [
			'type' => 'url',
			'label' => 'Subscribe Banner <small style="font-weight:normal;"><em>(width: 600px)</em></small>',
			'placeholder' => 'url'
		],
		'subscribe_banner_height' => [
			'type' => 'number',
			'label' => 'Subscribe Banner Height <small style="font-weight:normal;"><em>(px)</em></small>',
			'default' => '200'
		],
		'unsubscribe_banner' => [
			'type' => 'url',
			'label' => 'Unsubscribe Banner <small style="font-weight:normal;"><em>(width: 600px)</em></small>',
			'placeholder' => 'url'
		],
		'unsubscribe_banner_height' => [
			'type' => 'number',
			'label' => 'Unsubscribe Banner Height <small style="font-weight:normal;"><em>(px)</em></small>',
			'default' => '200'
		],
		'color_body' => [
			'type' => 'text',
			'label' => 'Body Color #',
			'placeholder' => '000000',
			'default' => 'f0f0f0'
		],
		'color_container' => [
			'type' => 'text',
			'label' => 'Container Color #',
			'placeholder' => '000000',
			'default' => 'ffffff'
		],
		'color_banner' => [
			'type' => 'text',
			'label' => 'Banner Background Color #',
			'placeholder' => '000000',
			'default' => 'bdbdbd'
		],
		'color_text_heading' => [
			'type' => 'text',
			'label' => 'Primary Text Color #',
			'placeholder' => '000000',
			'default' => '58595b'
		],
		'color_text_primary' => [
			'type' => 'text',
			'label' => 'Primary Text Color #',
			'placeholder' => '000000',
			'default' => '58595b'
		],
		'color_text_secondary' => [
			'type' => 'text',
			'label' => 'Secondary Text Color #',
			'placeholder' => '000000',
			'default' => 'bcbec0'
		],
		'color_text_link' => [
			'type' => 'text',
			'label' => 'Text Link Color #',
			'placeholder' => '000000',
			'default' => 'ff3c00'
		],
		'facebook' => [
			'type' => 'checkbox',
			'label' => 'Facebook Link',
			'default' => '0'
		],
		'twitter' => [
			'type' => 'checkbox',
			'label' => 'Twitter Link',
			'default' => '0'
		],
		'pinterest' => [
			'type' => 'checkbox',
			'label' => 'Pinterest Link',
			'default' => '0'
		],
		'instagram' => [
			'type' => 'checkbox',
			'label' => 'Instagram Link',
			'default' => '0'
		],
		'linkedin' => [
			'type' => 'checkbox',
			'label' => 'LinkedIn Link',
			'default' => '0'
		],
		'google' => [
			'type' => 'checkbox',
			'label' => 'Google+ Link',
			'default' => '0'
		],
		'youtube' => [
			'type' => 'checkbox',
			'label' => 'YouTube Link',
			'default' => '0'
		],
		'tumblr' => [
			'type' => 'checkbox',
			'label' => 'Tumblr Link',
			'default' => '0'
		]
	];

	public static $table = [
		'name' => 'mailing_list',
		'prefix' => 'ml',
		'version' => '1.0',
		'key' => INTOOR_MAIL_KEY,
		'structure' => [
			'email' => [
				'sql' => 'VARCHAR(255)',
				'encrypt' => true
			],
			'status' => [
				'sql' => 'VARCHAR(255)',
				'default' => 'active'
			],
			'create_date' => [
				'sql' => 'DATE'
			],
			'create_time' => [
				'sql' => 'TIME'
			],
			'delete_date' => [
				'sql' => 'DATE'
			],
			'delete_time' => [
				'sql' => 'TIME'
			]
		]
	];

	public function __construct() {

		$this->setup_mailing_list();
		$this->setup_admin_menus();
		$this->wp_hooks();

	}

	protected function setup_mailing_list() {

		Database::install_table( static::$table );
		API::new_key( 'mailing_list' );

	}

	protected function setup_admin_menus() {

		$mailing_list = [
			'type' => 'menu_page',
			'title' => 'Mailing List',
			'menu_title' => 'Mailing List',
			'icon' => 'dashicons-email-alt',
			'view' => INTOOR_VIEWS_DIR . 'admin/mailing-list.php',
			'table' => static::$table
		];

		$mailing_list_stats = [
			'type' => 'submenu_page',
			'title' => 'Mailing List Stats',
			'menu_title' => 'Stats',
			'parent' => 'mailing_list',
			'view' => INTOOR_VIEWS_DIR . 'admin/mailing-list-stats.php',
			'table' => static::$table
		];

		$mailing_list_settings = [
			'type' => 'submenu_page',
			'title' => 'Mailing List Settings',
			'menu_title' => 'Settings',
			'parent' => 'mailing_list',
			'fields' => static::$settings
		];

		new Admin_Menu( $mailing_list );
		new Admin_Menu( $mailing_list_stats );
		new Admin_Menu( $mailing_list_settings );

	}

	protected function wp_hooks() {

		// Update the mailing list
		add_action( 'admin_init', array( &$this, 'update_mailing_list' ) );

		// Export the mailing list
		add_action( 'admin_init', array( &$this, 'export_mailing_list' ) );

	}

	public function update_mailing_list() {

		$action = !empty( $_GET['action2'] ) ? $_GET['action2'] : '';
		$action = !empty( $_GET['action1'] ) ? $_GET['action1'] : $action;

		if( !empty( $action ) && $_GET['page'] == 'mailing_list' ) :

			$selected = ( !empty( $_GET['ckd'] ) ) ? $_GET['ckd'] : array();
			foreach( $selected as $row_id ) {
				switch( $action ) {

					case 'active':
						Database::update_row( static::$table, 'id', $row_id, array( 'status' => 'active' ) );
						break;

					case 'trash':
						Database::update_row( static::$table, 'id', $row_id, array( 'status' => 'trash' ) );
						break;

					case 'delete':
						Database::update_row( static::$table, 'id', $row_id, array( 'email' => 'deleted-' . rand( 9999, 99999999 ), 'status' => 'deleted', 'delete_date' => date( 'Y-m-d', time() ), 'delete_time' => date( 'H:i:s', time() ) ) );
						break;

				}
			}
		
		endif;

	}

	public function export_mailing_list() {

		if( !empty( $_GET['export'] ) && $_GET['page'] == 'mailing_list' ) :

			$args = [
				'filename' => 'Mailing-List',
				'data' => $this->get_mailing_list()
			];

			new CSV( $args );

		endif;

	}

	public function get_mailing_list( $status = NULL ) {

		switch( $status ) {

			case 'active':
				return Database::get_results( static::$table, NULL, array( 'status' => 'active' ) );
				break;

			case 'trash':
				return Database::get_results( static::$table, NULL, array( 'status' => 'trash' ) );
				break;

			case 'deleted':
				return Database::get_results( static::$table, NULL, array( 'status' => 'deleted' ) );
				break;

			default :
				return Database::get_results( static::$table );
				break;

		}

	}

	public static function save_email( $email ) {

		$resp = array();
		$resp['status'] = 'error';
		$resp['type'] = 'database-error';
		$resp['message'] = 'An error occured connecting to the database. Try again later.';
		$resp['display'] = 'Sorry, something went wrong. Please try again later.';
<<<<<<< HEAD

		switch( static::save_to_database( $email ) ) {

			case 'success':
				$resp['status'] = 'success';
				$resp['type'] = 'submitted';
				$resp['message'] = 'The submitted email address has successfully been added to the mailing list.';
				$resp['display'] = 'Thanks for subscribing!';
				break;

			case 'duplicate':
				$resp['status'] = 'duplicate';
				$resp['type'] = 'duplicate';
				$resp['message'] = 'The submitted email address is already on the mailing list.';
				$resp['display'] = 'Welcome back! It looks like you already subscribed.';
				break;

=======

		switch( static::save_to_database( $email ) ) {

			case 'success':
				$resp['status'] = 'success';
				$resp['type'] = 'submitted';
				$resp['message'] = 'The submitted email address has successfully been added to the mailing list.';
				$resp['display'] = 'Thanks for subscribing!';
				break;

			case 'duplicate':
				$resp['status'] = 'duplicate';
				$resp['type'] = 'duplicate';
				$resp['message'] = 'The submitted email address is already on the mailing list.';
				$resp['display'] = 'Welcome back! It looks like you already subscribed.';
				break;

>>>>>>> dev
		}

		return $resp;

	}

	protected static function save_to_database( $email ) {

		$email = strtolower( $email );
		$data = Database::get_row( static::$table, 'email', $email );
		$status = 'error';

		if( !empty( $data['email'] ) ) :

			$status = 'duplicate';

		else :

			$data['email'] = $email;
			$data['create_date'] = date( 'Y-m-d', time() );
			$data['create_time'] = date( 'H:i:s', time() );

			$email = [
				'sender' => !empty( get_option( 'mailing_list_settings_sender' ) ) ? get_option( 'mailing_list_settings_sender' ) : get_bloginfo( 'admin_email' ),
				'recipient' => $email,
				'subject' => 'Thanks for Subscribing!',
				'template' => 'subscribe.php'
			];

			new Email( $email );
			$status = ( Database::insert_row( static::$table, $data ) ) ? 'success' : 'error';

		endif;

		return $status;

	}

	public static function delete_email( $email ) {

		$email = strtolower( $email );
		$resp = array();
		$resp['status'] = 'error';
		$resp['type'] = 'database-error';
		$resp['message'] = 'An error occured connecting to the database. Try again later.';
		$resp['display'] = 'Sorry, something went wrong. Please try again later.';

<<<<<<< HEAD
		switch( static::remove_from_database( $email ) ) {

			case 'success':
				$resp['status'] = 'success';
				$resp['type'] = 'removed';
				$resp['message'] = 'The submitted email address has successfully been removed from the mailing list.';
				$resp['display'] = 'Your email address has been successfully removed from our mailing list.';
				break;

			case 'not-found':
				$resp['status'] = 'error';
				$resp['type'] = 'not-found';
				$resp['message'] = 'The submitted email address is not on the mailing list.';
				$resp['display'] = 'Your email address isn\'t on our mailing list.';
				break;

		}

=======
			switch( static::remove_from_database( $email ) ) {

				case 'success':
					$resp['status'] = 'success';
					$resp['type'] = 'removed';
					$resp['message'] = 'The submitted email address has successfully been removed from the mailing list.';
					$resp['display'] = 'Your email address has been successfully removed from our mailing list.';
					break;

				case 'not-found':
					$resp['status'] = 'error';
					$resp['type'] = 'not-found';
					$resp['message'] = 'The submitted email address is not on the mailing list.';
					$resp['display'] = 'Your email address isn\'t on our mailing list.';
					break;

			}

>>>>>>> dev
		return $resp;

	}

	protected static function remove_from_database( $email ) {

		$email = strtolower( $email );
		$data = Database::get_row( static::$table, 'email', $email );
		$status = 'error';

		if( empty( $data['email'] ) ) :

			$status = 'not-found';

		else :

			$data['email'] = 'deleted-' . rand( 9999, 99999999 );
			$data['status'] = 'deleted';
			$data['delete_date'] = date( 'Y-m-d', time() );
			$data['delete_time'] = date( 'H:i:s', time() );

			$email = [
				'sender' => !empty( get_option( 'mailing_list_settings_sender' ) ) ? get_option( 'mailing_list_settings_sender' ) : get_bloginfo( 'admin_email' ),
				'recipient' => $email,
				'subject' => 'Unsubscribe Confirmation',
				'template' => 'unsubscribe.php'
			];

			new Email( $email );
			$status = ( Database::update_row( static::$table, 'id', $data['id'], $data ) ) ? 'success' : 'error';

		endif;

		return $status;

	}

	public static function get_form( $args = array(), $template = '' ) {

		ob_start();
		include ( !empty( $template ) ) ? $template : INTOOR_VIEWS_DIR . 'mailing-list-form.php';
		$html = ob_get_contents();
		ob_end_clean();
		return $html;

	}

	public static function form( $args = array(), $template = '' ) {

		echo static::get_form( $args, $template );

	}

}