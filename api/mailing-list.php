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
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

// Define as JSON application
header( 'Content-type: application/json' );
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
require_once dirname( dirname( __FILE__ ) ) . '/config.php';

// Set the default API response
$resp = array();
$resp['status'] = 'error';
$resp['type'] = 'unauthorized-access';
$resp['message'] = 'Unauthorized Access';
$resp['display'] = 'Unauthorized Access';

$params = $_GET;

// Validate API Key
if( empty( $params['api-key'] ) || !API::key_auth( 'mailing_list', $params['api-key'] ) ) :

	die( 'Unauthorized Access' );

else :

	$resp['status'] = 'error';
	$resp['type'] = 'unauthorized-access';
	$resp['message'] = 'Warning: unidentified robot detected';
	$resp['display'] = 'Sorry, but robots are not allowed to subscribe to the mailing list.'

	// Validate captcha (must be empty)
	if( isset( $params['h'] ) && empty( $params['h'] ) ) :

		$resp['status'] = 'error';
		$resp['type'] = 'missing-parameters';
		$resp['message'] = 'Warning: required parameters not found';
		$resp['display'] = 'Please fill out all required fields.';

		// Verify required parameters
		if( !empty( $params['action'] ) && !empty( $params['email'] ) ) :

			$resp = Mailing_List::run_api_action( $params['action'], $params );

		endif;

	endif;

endif;

// Redirect or return JSON response string
if( !empty( $params['redirect'] ) ) {
	header( 'Location: ' . $params['redirect'] . '?' . http_build_query( $resp ), TRUE, 303 );
} else {
	echo json_encode( $resp );
}