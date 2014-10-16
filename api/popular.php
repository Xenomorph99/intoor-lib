<?php
/**
 * Popular API
 *
 * Required parameters: action, post_id
 *
 * Required classes: Popular
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

$params = $_POST;

// Validate API Key
if( empty( $params['api-key'] ) || !API::key_auth( 'popular', $params['api-key'] ) ) :

	die( 'Unauthorized Access' );

else :

	$resp['status'] = 'error';
	$resp['type'] = 'missing-parameters';
	$resp['message'] = 'Warning: required parameters not found';

	// Verify required parameters
	if( !empty( $params['action'] ) && !empty( $params['post_id'] ) ) :

		$resp = Popular::run_api_action( $params['action'], $params );

	endif;

endif;

// Redirect or return JSON response string
if( !empty( $params['redirect'] ) ) {
	header( 'Location: ' . $params['redirect'] . '?' . http_build_query( $resp ), TRUE, 303 );
} else {
	echo json_encode( $resp );
}