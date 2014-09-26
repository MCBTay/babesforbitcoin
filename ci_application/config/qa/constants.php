<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Encryption key used for encryption/decryption
define('ENCRYPT_KEY', 'cXpoBBaOhhxXas7QBYOZEEtLVmpYJuT6');

// Site title as shown in the title bar
define('SITE_TITLE', 'Babes for Bitcoin');

// AWS S3 Bucket for assets
define('S3_BUCKET', 'bfb-prod-bucket');

// CDN URL for uploaded assets
define('CDN_URL', 'http://d3jxsdbg3fdggl.cloudfront.net/');

// Location of installed applications
define('BIN_PATH', '/usr/local/bin/');

// Fees
define('FEE_CARD',     0.20); // 20%
define('FEE_BANK',     0.05); //  5%
define('FEE_BTC',      0.05); //  5%
define('FEE_CONVERT',  0.02); //  2%
define('FEE_PURCHASE', 0.35); // 35%

// Mandrill API Key
define('MANDRILL_API_KEY', 'p7yMjaiaFDiLz5yna1BPLA');

// Coinbase API Key
define('COINBASE_API_KEY',    'At4gBAbpgdtJgaqIdxwRCY6VmOHbgfaNNRf20oWOyL9egAvLoeLsS6W7K6Zr4zBhnncWyYkj1fdm65lKs/qXbg==');
define('COINBASE_API_SECRET', 'M6t1VbyxnRrWbmDyj29FOecZ449b9KDYG/x40Bm21Snh4bhDaLRJY+GhH5lUz4TwbSJbb02pqXbJxSaLnAwJ/w==');

// Dwolla API credentials
define('DWOLLA_KEY',    'ZiO4sYId4WL0JlIq8Rrtzd5Db0D1ARwKrWlk18AZ5WwRXzBRB89VBylUS2hWiI7P0TQ+1J/MbBxbCLEE/BtqJeu6OMd67TmbNB8yZvCbN9hFi4YU63xo2Z5ChekvQJQL');
define('DWOLLA_SECRET', 'RUcUn5CdfkmWbHjkfRfWIj9JxAIvIhaVRzj79Y86pFZuor/emYXj/sbU7aV6Temi4bwnIVBrpTkOy34tMrwqQZzj8YJCBMwhGrN9V7Om9NN20klHy9lAkn9RsiHkCJMi');
define('DWOLLA_TOKEN',  'BHMK13jlvYyTGW0e9LEl8gUJePCiyVqEW7W8M1oqlsEV7INk9PZZYju6u6sSDlqxxcqlETkJNqHdgeOnkMAJoTaP0Mj+jUl7+kKbRJkeUk3BJ1wWWOMD76KXbkvG/t+c');
define('DWOLLA_PIN',    'U7fXUXUlXBTxtr8AcWJS4LUZUkFJosTUbApw1px5varL5ZKa3zDGs+/u0I/Phzf2pt7DgX68kWNnWwv7iX5tuw==');

/* End of file constants.php */
/* Location: ./application/config/qa/constants.php */