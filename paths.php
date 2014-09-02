<?php
/**
 * Directory and file path definitions.
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

// Lib root directory
define( 'INTOOR_DIR', dirname( __FILE__ ) . '/' );

// General directories
define( 'INTOOR_API_DIR', INTOOR_DIR . 'api/' );
define( 'INTOOR_CLASSES_DIR', INTOOR_DIR . 'classes/' );
define( 'INTOOR_CSV_DIR', INTOOR_DIR . 'csv/' );
define( 'INTOOR_IMAGES_DIR', INTOOR_DIR . 'images/' );
define( 'INTOOR_JS_DIR', INTOOR_DIR . 'js/' );
define( 'INTOOR_VIEWS_DIR', INTOOR_DIR . 'views/' );

// Classes
define( 'INTOOR_ADMIN_MENU_CLASS', INTOOR_CLASSES_DIR . 'admin-menu.php' );
define( 'INTOOR_COOKIE_CLASS', INTOOR_CLASSES_DIR . 'cookie.php' );
define( 'INTOOR_DATABASE_CLASS', INTOOR_CLASSES_DIR . 'database.php' );
define( 'INTOOR_EMAIL_CLASS', INTOOR_CLASSES_DIR . 'email.php' );
define( 'INTOOR_ENCRYPTION_CLASS', INTOOR_CLASSES_DIR . 'encryption.php' );
define( 'INTOOR_FORMS_CLASS', INTOOR_CLASSES_DIR . 'forms.php' );
define( 'INTOOR_FUNCTIONS_CLASS', INTOOR_CLASSES_DIR . 'functions.php' );
define( 'INTOOR_LEADS_CLASS', INTOOR_CLASSES_DIR . 'leads.php' );
define( 'INTOOR_MAILING_LIST_CLASS', INTOOR_CLASSES_DIR . 'mailing-list.php' );
define( 'INTOOR_META_BOX_CLASS', INTOOR_CLASSES_DIR . 'meta-box.php' );
define( 'INTOOR_POPULAR_CLASS', INTOOR_CLASSES_DIR . 'popular.php' );
define( 'INTOOR_POST_TYPE_CLASS', INTOOR_CLASSES_DIR . 'post-type.php' );
define( 'INTOOR_QUICK_EDIT_CLASS', INTOOR_CLASSES_DIR . 'quick-edit.php' );
define( 'INTOOR_SOCIAL_CLASS', INTOOR_CLASSES_DIR . 'social.php' );
define( 'INTOOR_TAXONOMY_CLASS', INTOOR_CLASSES_DIR . 'taxonomy.php' );
define( 'INTOOR_UPSELL_CLASS', INTOOR_CLASSES_DIR . 'upsell.php' );
define( 'INTOOR_USER_CLASS', INTOOR_CLASSES_DIR . 'user.php' );