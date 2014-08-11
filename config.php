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
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';

// General theme definitions
define( 'INTOOR_LIB_VERSION', '1.0' );

// Define file and directory paths
require_once dirname( __FILE__ ) . '/paths.php';

// Include required models
require_once ADMIN_MENU_CLASS;
require_once DATABASE_CLASS;
require_once EMAIL_CLASS;
require_once ENCRYPTION_CLASS;
require_once FORMS_CLASS;
require_once FUNCTIONS_CLASS;
require_once LEADS_CLASS;
require_once MAILING_LIST_CLASS;
require_once META_BOX_CLASS;
require_once POPULAR_CLASS;
require_once POST_TYPE_CLASS;
require_once QUICK_EDIT_CLASS;