<?php
/**
 * Social API
 *
 * Required parameters: action, post_id, key
 *
 * Required classes: Social
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

// Define as JSON application
header( 'Content-type: application/json' );
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
require_once dirname( dirname( __FILE__ ) ) . '/config.php';

// Set the default API response
$resp = array(
	'status' => 'error',
	'desc' => 'missing-parameters',
	'message' => 'Warning: required parameters not found'
);

// Define parameters
$params = $_POST;

// Verify action
if( !empty( $params['action'] ) && !empty( $params['post_id'] ) && !empty( $params['key'] ) ) :
	$resp = Social::run_api_action( $params['action'], $params['post_id'], $params['key'] );
endif;

echo json_encode( $resp );