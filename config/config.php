<?php

if (!defined('PROJECT_PATH')) {
    define('PROJECT_PATH', 'http://127.0.0.1/Php/Mobile/'); // replace this value with your project path
}

if (!defined('IS_SANDBOX')) {
    define('IS_SANDBOX', true); // 'true' for sandbox, 'false' for live
}

if (!defined('STORE_ID')) {
    define('STORE_ID', 'devsh66a1477023bf1'); // your store id. For sandbox, register at https://developer.sslcommerz.com/registration/
}

if (!defined('STORE_PASSWORD')) {
    define('STORE_PASSWORD', 'devsh66a1477023bf1@ssl'); // your store password.
}

return [
    'success_url' => 'pg_redirection/success.php', // your success url
    'failed_url' => 'pg_redirection/fail.php', // your fail url
    'cancel_url' => 'pg_redirection/cancel.php', //your cancel url
    'ipn_url' => 'pg_redirection/ipn.php', // your ipn url


    'projectPath' => PROJECT_PATH,
    'apiDomain' => IS_SANDBOX ? 'https://sandbox.sslcommerz.com' : 'https://securepay.sslcommerz.com',
    'apiCredentials' => [
        'store_id' => STORE_ID,
        'store_password' => STORE_PASSWORD,
    ],
    'apiUrl' => [
        'make_payment' => "/gwprocess/v4/api.php",
        'order_validate' => "/validator/api/validationserverAPI.php",
    ],
    'connect_from_localhost' => true,
    'verify_hash' => true,
];