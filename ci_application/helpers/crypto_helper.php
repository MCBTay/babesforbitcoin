<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Encrypt something
 *
 * @return string
 */
function encrypt($text)
{
	global $config;

	$iv = mcrypt_create_iv(
		mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC),
		MCRYPT_DEV_URANDOM
	);

	$encrypted = base64_encode(
		$iv .
		mcrypt_encrypt(
			MCRYPT_RIJNDAEL_256,
			hash('sha256', $config['encryption_key'], true),
			$text,
			MCRYPT_MODE_CBC,
			$iv
		)
	);

	return $encrypted;
}

/**
 * Decrypt something
 *
 * @return string
 */
function decrypt($text)
{
	global $config;

	$data = base64_decode($text);
	$iv   = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC));

	$decrypted = rtrim(
		mcrypt_decrypt(
			MCRYPT_RIJNDAEL_256,
			hash('sha256', $config['encryption_key'], true),
			substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)),
			MCRYPT_MODE_CBC,
			$iv
		),
		"\0"
	);

	return $decrypted;
}

/* End of file crypto_helper.php */
/* Location: ./application/helpers/crypto_helper.php */