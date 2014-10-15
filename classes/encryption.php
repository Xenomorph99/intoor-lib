<?php
/**
 * This model contains methods used for encrypting and decrypting data.
 * By default, encryption and decryption will run using the AUTH_KEY defined
 * in the wp-config.php file.
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor-lib
 * @version		Release: 1.0
 */

class Encryption {

	public static function encrypt( $data, $key = AUTH_KEY ) {

		$encodedData = json_encode( $data );
		$encryptedData = trim( base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $key ), $encodedData, MCRYPT_MODE_ECB, mcrypt_create_iv( mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND ) ) ) );
		return $encryptedData;

	}

	public static function decrypt( $data, $key = AUTH_KEY ) {

		$decryptedArray = trim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $key ), base64_decode( $data ), MCRYPT_MODE_ECB, mcrypt_create_iv( mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND ) ) );
		$decryptedData = json_decode( $decryptedArray, true );
		return $decryptedData;

	}

	public static function keygen( $min = 10000000, $max = 999999999 ) {

		$rand = rand($min, $max);
		$key = $this->encrypt( $rand );
		$key = str_replace( '/', '_', $val );
		$key = str_replace( '+', '_', $val );
		$key = str_replace( '=', '_', $val );
		return $key

	}

}