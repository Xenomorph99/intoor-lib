<?php
/**
 * Library configuration
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor
 * @version		Release: 1.0
 */

// Wordpress config file (defines ABSPATH and includes 'wp-settings.php', etc.)
//require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';

// General library definitions
define( 'INTOOR_LIB_VERSION', '1.0' );
define( 'INTOOR_DIR_NAME', 'lib' );

// Define file and directory paths
require_once dirname( __FILE__ ) . '/paths.php';

// Include required classes
require_once INTOOR_ADMIN_MENU_CLASS;
require_once INTOOR_DATABASE_CLASS;
require_once INTOOR_EMAIL_CLASS;
require_once INTOOR_ENCRYPTION_CLASS;
require_once INTOOR_FORMS_CLASS;
require_once INTOOR_FUNCTIONS_CLASS;
require_once INTOOR_LEADS_CLASS;
require_once INTOOR_MAILING_LIST_CLASS;
require_once INTOOR_META_BOX_CLASS;
require_once INTOOR_POPULAR_CLASS;
require_once INTOOR_POST_TYPE_CLASS;
require_once INTOOR_QUICK_EDIT_CLASS;

// Include required JavaScript
function intoor_enqueue_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'intoor_admin', get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/js/min/intoor-admin-min.js', array( 'jquery' ), '1.0' );
}
add_action( 'admin_enqueue_scripts', 'intoor_enqueue_scripts' );