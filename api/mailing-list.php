<?php
/**
 * Mailing list API
 *
 * Required parameters: action, email
 *
 * Required classes: Mailing_List
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor
 * @version		Release: 1.0
 */

// Define as JSON application
header( 'Content-type: application/json' );
require_once dirname( dirname( __FILE__ ) ) . '/config.php';

// Set the default API response
$resp = array(
	'status' => 'error',
	'desc' => 'missing-parameters',
	'message' => 'Warning: required parameters not found',
	'user' => 'Please fill out all required fields.'
);

// Define parameters
$params = $_GET;

// Verify parameters
if( !empty( $params['action'] ) && !empty( $params['email'] ) ) {
	$resp = Mailing_List::run_api_action( $params['action'], $params['email'] );
}

// Redirect or return JSON response string
if( !empty( $params['redirect'] ) ) {
	header( 'Location: ' . $params['redirect'] . '?' . http_build_query( $resp ), TRUE, 303 );
} else {
	echo json_encode( $resp );
}