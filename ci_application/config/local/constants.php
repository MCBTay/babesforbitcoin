<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Encryption key used for encryption/decryption
define('ENCRYPT_KEY', 'cXpoBBaOhhxXas7QBYOZEEtLVmpYJuT6');

// Site title as shown in the title bar
define('SITE_TITLE', 'Babes for Bitcoin');

// AWS S3 Bucket for assets
define('S3_BUCKET', 'jneal-qa-bucket');

// CDN URL for uploaded assets
define('CDN_URL', 'http://d164uh1dt5s074.cloudfront.net/');

// Location of installed applications
define('BIN_PATH', '/usr/local/bin/');

// Fees
define('FEE_CARD',     0.20); // 20%
define('FEE_BANK',     0.05); //  5%
define('FEE_BTC',      0.05); //  5%
define('FEE_CONVERT',  0.02); //  2%
define('FEE_PURCHASE', 0.35); // 35%

// Mandrill API Key
define('MANDRILL_API_KEY', 'j8Ie2ReGdPzo5H5MsRcf1w');

// Coinbase API Key
define('COINBASE_API_KEY',    'mQ22iXpzdDL2UUaqpe13/ESRIOo5rebEDABvtce5HcD+LaT5OUyO0p0DSNnVUipxRa/mntEHdBZzR0svNSqnsw==');
define('COINBASE_API_SECRET', 'Ty3Njdq4DyRgMmNiAsbrJkOzh+9I0I4wDBiejQ7QlWx0wAtBzPdhRK5j5Mmev3nIUJIDGTdcDM+e7S5LQfL+Mw==');

// Dwolla API credentials
define('DWOLLA_KEY',    'ZiO4sYId4WL0JlIq8Rrtzd5Db0D1ARwKrWlk18AZ5WwRXzBRB89VBylUS2hWiI7P0TQ+1J/MbBxbCLEE/BtqJeu6OMd67TmbNB8yZvCbN9hFi4YU63xo2Z5ChekvQJQL');
define('DWOLLA_SECRET', 'RUcUn5CdfkmWbHjkfRfWIj9JxAIvIhaVRzj79Y86pFZuor/emYXj/sbU7aV6Temi4bwnIVBrpTkOy34tMrwqQZzj8YJCBMwhGrN9V7Om9NN20klHy9lAkn9RsiHkCJMi');
define('DWOLLA_TOKEN',  'BHMK13jlvYyTGW0e9LEl8gUJePCiyVqEW7W8M1oqlsEV7INk9PZZYju6u6sSDlqxxcqlETkJNqHdgeOnkMAJoTaP0Mj+jUl7+kKbRJkeUk3BJ1wWWOMD76KXbkvG/t+c');
define('DWOLLA_PIN',    'U7fXUXUlXBTxtr8AcWJS4LUZUkFJosTUbApw1px5varL5ZKa3zDGs+/u0I/Phzf2pt7DgX68kWNnWwv7iX5tuw==');

/* End of file constants.php */
/* Location: ./application/config/local/constants.php */