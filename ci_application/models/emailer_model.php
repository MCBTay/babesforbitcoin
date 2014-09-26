<?php if (! defined('BASEPATH')) exit('No direct script access');

class Emailer_model extends CI_Model
{

	/**
	 * Class Constructor
	 *
	 * Override the parent class constructor with our own
	 *
	 * @access public
	 * @return n/a
	 */
	public function __construct()
	{
		// Call the parent class constructor
		parent::__construct();
	}

	/**
	 * Send
	 *
	 * Send email
	 *
	 * @access public
	 * @return n/a
	 */
	public function send($mail_to = '', $mail_subject = '', $mail_message = '', $mail_from_email = '', $mail_from_name = '', $tag = 'general')
	{
		$data = array(
			'key'                   => MANDRILL_API_KEY,
			'message'               => array(
				'html'                => $mail_message,
				'subject'             => $mail_subject,
				'from_email'          => $mail_from_email,
				'from_name'           => $mail_from_name,
				'to'                  => array(
					array(
						'email'           => $mail_to,
						'type'            => 'to',
					),
				),
				'headers'             => array(
					'Reply-To'          => $mail_from_email,
				),
				'important'           => FALSE,
				'track_opens'         => TRUE,
				'track_clicks'        => TRUE,
				'auto_text'           => TRUE,
				'auto_html'           => TRUE,
				'inline_css'          => FALSE,
				'url_strip_qs'        => FALSE,
				'preserve_recipients' => FALSE,
				'view_content_link'   => TRUE,
				'merge'               => FALSE,
				'tags' => array(
					$tag
				),
				'metadata'            => array(
					'website'           => 'www.babesforbitcoin.com',
				),
			),
		);

		$data_json = json_encode($data);

		// Send the POST to SendGrid
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://mandrillapp.com/api/1.0/messages/send.json');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		echo curl_error($ch);
		curl_close($ch);
	}

}

/* End of file emailer_model.php */
/* Location: ./application/models/emailer_model.php */