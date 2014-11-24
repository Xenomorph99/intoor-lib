<?php
/**
 * Social API
 *
 * Required parameters: action, post_id
 *
 * Required classes: Social
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

extract( $_POST );

// Set the default API response
$resp = array();
$resp['status'] = 'error';
$resp['type'] = 'unauthorized-access';
$resp['message'] = 'Unauthorized Access';

// Authenticate API Key
if( empty( $api_key ) || !API::key_auth( 'social_sharing', $api_key ) ) :

	die( 'Unauthorized Access' );

else :

	$resp['type'] = 'missing-parameters';
	$resp['message'] = 'Warning: required parameters not found';

	// Verify required parameters
	if( !empty( $action ) && !empty( $post_id ) ) :

		switch( $action ) {

			case 'share':
				if( !empty( $network ) ) {
					$resp = Social::add_share( $post_id, $network );
				}
				break;

			default:
				$resp['type'] = 'invalid-action';
				$resp['message'] = 'Defined API action cannot be performed';
				break;

		}

	endif;

	// Redirect or return JSON response string
	if( !empty( $redirect ) ) :

		$resp['message'] = base64_encode( $resp['message'] );
		header( 'Location: ' . $redirect . '?' . http_build_query( $resp ), TRUE, 303 );

	else :

		echo json_encode( $resp );

	endif;

endif;