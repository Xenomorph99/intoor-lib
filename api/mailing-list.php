<?php
/**
 * Mailing list API
 *
 * Required classes: Mailing_List
 *
 * @package     Интоор Library (intoor)
 * @author      Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright   2014 Hazard Media Group LLC
 * @license     MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link        https://github.com/Alekhen/intoor-lib
 * @version     Release: 1.2
 */

// Define as JSON application
header( 'Content-type: application/json' );
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
require_once dirname( dirname( __FILE__ ) ) . '/config.php';

extract( $_GET );

// Set the default API response
$resp = array();
$resp['status'] = 'error';
$resp['type'] = 'unauthorized-access';
$resp['message'] = 'Unauthorized Access';
$resp['display'] = 'Unauthorized Access';

// Authenticate API Key
if( empty( $api_key ) || !API::key_auth( 'mailing_list', $api_key ) ) :

	die( 'Unauthorized Access' );

else :

	$resp['message'] = 'Warning: unidentified robot detected';
	$resp['display'] = 'Sorry, but robots are not allowed to subscribe to the mailing list.';

	// Validate captcha (must be empty)
	if( isset( $cc ) && empty( $cc ) ) :

		$resp['type'] = 'missing-parameters';
		$resp['message'] = 'Warning: required parameters not found';
		$resp['display'] = 'Please fill out all required fields.';

		// Verify required parameters
		if( !empty( $action ) && !empty( $email ) ) :

			switch( $action ) {

				case 'subscribe':
					$resp = Mailing_List::save_email( $email );
					break;

				case 'unsubscribe':
					$resp = Mailing_List::delete_email( $email );
					break;

				default:
					$resp['type'] = 'invalid-action';
					$resp['message'] = 'Defined API action cannot be performed';
					$resp['display'] = 'Sorry, something went wrong. Please try again later.';
					break;

			}

		endif;

	endif;

	// Redirect or return JSON response string
	if( !empty( $redirect ) ) :

		$resp['message'] = base64_encode( $resp['message'] );
		$resp['display'] = base64_encode( $resp['display'] );
		header( 'Location: ' . $redirect . '?' . http_build_query( $resp ), TRUE, 303 );

	else :

		echo json_encode( $resp );

	endif;

endif;