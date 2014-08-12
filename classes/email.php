<?php
/**
 * This model manages the creation and distribution of HTML Emails.
 *
 * Required classes: Functions
 *
 * @package		Интоор Library (intoor)
 * @author		Colton James Wiscombe <colton@hazardmediagroup.com>
 * @copyright	2014 Hazard Media Group LLC
 * @license		MIT License - http://www.opensource.org/licenses/mit-license.html
 * @link 		https://github.com/Alekhen/intoor
 * @version		Release: 1.0
 */

class Email {

	public $settings = array(
		'sender' => '',			// Email address of sender (default to admin email)
		'reply_to' => '',		// Email address recipients may reply to (default to admin email)
		'recipient' => '',		// Email address to which the email will be sent
		'subject' => '',		// Subject line of the email
		'message' => '',		// HTML email string to be sent to recipient
		'template' => '',		// File name of the HTML email template (templates must be located in lib/views/email)
		'data' => array()		// Array of data to be used by the HTML email template
	);

	public function __construct( $args ) {

		$this->settings = Functions::merge_array( $args, $this->settings );
		$this->send_mail();

	}

	protected function send_mail() {

		extract( $this->settings );

		$rn = "\r\n";
		$headers = "From: " . $sender . $rn;
		//$headers .= "Reply-To: " . $reply_to . $rn;
		$headers .= "Mime-Version: 1.0" . $rn;
		$headers .= "Content-type: text/html; charset=UTF-8" . $rn;
		$headers .= "X-Mailer: PHP/" . phpversion();

		ob_start();
		require_once '../views/email/' . $template;
		$message = ob_get_contents();
		ob_end_clean();

		@mail( $recipient, $subject, $message, $headers );

	}

}