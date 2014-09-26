<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$active_group = 'prod';
$active_record = TRUE;

$db['prod']['hostname'] = 'bfb-prod-db.crfteigz1fo2.us-east-1.rds.amazonaws.com';
$db['prod']['username'] = 'bfb_prod_db_user';
$db['prod']['password'] = 'wg1!qIZ5e5cWe9kO';
$db['prod']['database'] = 'babesforbitcoin_com_www';
$db['prod']['dbdriver'] = 'mysql';
$db['prod']['dbprefix'] = '';
$db['prod']['pconnect'] = TRUE;
$db['prod']['db_debug'] = TRUE;
$db['prod']['cache_on'] = FALSE;
$db['prod']['cachedir'] = '';
$db['prod']['char_set'] = 'utf8';
$db['prod']['dbcollat'] = 'utf8_general_ci';
$db['prod']['swap_pre'] = '';
$db['prod']['autoinit'] = TRUE;
$db['prod']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/prod/database.php */