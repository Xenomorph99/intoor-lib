<?php
/**
 * Load required classes
 *
 * @package     Интоор Library (intoor)
 * @author      Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright   2014 Hazard Media Group LLC
 * @license     MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link        https://github.com/Alekhen/intoor-lib
 * @version     Release: 1.2
 */

// Include required classes
require_once INTOOR_ADMIN_MENU_CLASS;
require_once INTOOR_API_CLASS;
require_once INTOOR_CATEGORY_FORM_CLASS;
//require_once INTOOR_COOKIE_CLASS;
require_once INTOOR_DATABASE_CLASS;
//require_once INTOOR_DOCS_CLASS;
require_once INTOOR_EMAIL_CLASS;
require_once INTOOR_ENCRYPTION_CLASS;
require_once INTOOR_FORMS_CLASS;
require_once INTOOR_FUNCTIONS_CLASS;
//require_once INTOOR_LEADS_CLASS;
require_once INTOOR_MAILING_LIST_CLASS;
require_once INTOOR_META_BOX_CLASS;
require_once INTOOR_POPULAR_CLASS;
require_once INTOOR_POST_TYPE_CLASS;
//require_once INTOOR_QUICK_EDIT_CLASS;
require_once INTOOR_SHORTCODES_CLASS;
require_once INTOOR_SOCIAL_CLASS;
//require_once INTOOR_TAXONOMY_CLASS;
require_once INTOOR_TRACKING_CLASS;
//require_once INTOOR_UPSELL_CLASS;
//require_once INTOOR_USER_CLASS;

// Run static setup methods
API::setup();

// Include required JavaScript files
function intoor_enqueue_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'intoor_lib', get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/js/min/intoor-lib-min.js', array( 'jquery' ), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'intoor_enqueue_scripts' );

// Include required JavaScript files in Wordpress admin
function intoor_enqueue_admin_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'intoor_admin', get_template_directory_uri() . '/' . INTOOR_DIR_NAME . '/js/min/intoor-admin-min.js', array( 'jquery' ), '1.0' );
}
add_action( 'admin_enqueue_scripts', 'intoor_enqueue_admin_scripts' );