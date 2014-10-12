<?php if (! defined('BASEPATH')) exit('No direct script access');

// Include required Coinbase library
require_once(dirname(__FILE__) . '/Coinbase/Exception.php');
require_once(dirname(__FILE__) . '/Coinbase/ApiException.php');
require_once(dirname(__FILE__) . '/Coinbase/ConnectionException.php');
require_once(dirname(__FILE__) . '/Coinbase/Coinbase.php');
require_once(dirname(__FILE__) . '/Coinbase/Requestor.php');
require_once(dirname(__FILE__) . '/Coinbase/Rpc.php');
require_once(dirname(__FILE__) . '/Coinbase/OAuth.php');
require_once(dirname(__FILE__) . '/Coinbase/TokensExpiredException.php');
require_once(dirname(__FILE__) . '/Coinbase/Authentication.php');
require_once(dirname(__FILE__) . '/Coinbase/SimpleApiKeyAuthentication.php');
require_once(dirname(__FILE__) . '/Coinbase/OAuthAuthentication.php');
require_once(dirname(__FILE__) . '/Coinbase/ApiKeyAuthentication.php');

// See https://github.com/coinbase/coinbase-php

class Coinbase_model extends CI_Model
{

	public $coinbase;

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

		$this->coinbase = Coinbase::withApiKey(decrypt(COINBASE_API_KEY), decrypt(COINBASE_API_SECRET));
	}

	/**
	 * Decode Callback
	 *
	 * Decode a callback by Coinbase
	 *
	 * @access public
	 * @return n/a
	 */
	public function decode_callback()
	{
		$post = file_get_contents("php://input");
		$json = json_decode($post);


		// Send callback POST data by email for testing
		ob_start();
		echo '<pre>';
		print_r($post);
		echo '</pre>';
		$message = ob_get_clean();
		$this->emailer_model->send(
			$mail_to         = 'mcbtay@gmail.com',
			$mail_subject    = 'Test API',
			$mail_message    = $message,
			$mail_from_email = 'info@babesforbitcoin.com',
			$mail_from_name  = 'Babes for Bitcoin',
			$tag             = 'testing'
		);


		if (!is_object($json))
		{
			// We didn't receive a valid JSON object
			return FALSE;
		}

		$transaction = $this->coinbase->getTransaction($json->order->transaction->id);

        // Send callback POST data by email for testing
        ob_start();
        echo '<pre>';
        print_r($transaction);
        echo '</pre>';
        $message = ob_get_clean();
        $this->emailer_model->send(
            $mail_to         = 'mcbtay@gmail.com',
            $mail_subject    = 'Test API - transaction',
            $mail_message    = $message,
            $mail_from_email = 'info@babesforbitcoin.com',
            $mail_from_name  = 'Babes for Bitcoin',
            $tag             = 'testing'
        );
		if (!is_object($transaction))
		{
			// We couldn't find this transaction in Coinbase
			return FALSE;
		}

		if ($json->order->status == 'completed' && $transaction->status != 'complete')
		{
			// Found transaction, but it wasn't really completed
			return FALSE;
		}

		$compare = number_format($json->order->total_btc->cents / 100000000, 8, '.', '');
		$amount  = abs($transaction->amount->amount);

		if ($amount != $compare)
		{
			// The callback amount doesn't match the actual transaction amount
			return FALSE;
		}

		return $json->order;
	}
}

/* End of file coinbase_model.php */
/* Location: ./application/models/coinbase_model.php */