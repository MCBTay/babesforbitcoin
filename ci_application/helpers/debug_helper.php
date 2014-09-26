<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Die Debug
 *
 * @return string
 */
function dd($text)
{
	echo '<pre>';
	print_r($text);
	echo '</pre>';
	die();
}

/* End of file debug_helper.php */
/* Location: ./application/helpers/debug_helper.php */