<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');
require('functions.php');
// For test payments we want to enable the sandbox mode. If you want to put live
// payments through then this setting needs changing to `false`.
$enableSandbox = true;

// PayPal settings. Change these to your account details and the relevant URLs
// for your site.
$paypalConfig = [
    'email' => 'sb-jzplm30408430@business.example.com',
    'return_url' => 'https://3.113.178.224/payment-success.html',
    'cancel_url' => 'https://3.113.178.224/payment-cancelled.html',
    'notify_url' => 'https://3.113.178.224/ipn-listener.php'
];

$paypalUrl = $enableSandbox ? 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr' : 'https://ipnpb.paypal.com/cgi-bin/webscr';

// Check if paypal request or response
if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])) {
    // Grab the post data so that we can set up the query string for PayPal.
    // Ideally we'd use a whitelist here to check nothing is being injected into
    // our post data.
    $data = [];
    foreach ($_POST as $key => $value) {
        $data[$key] = stripslashes($value);
    }

    // Set the PayPal account.
    $data['business'] = $paypalConfig['email'];

    // Set the PayPal return addresses.
    $data['return'] = stripslashes($paypalConfig['return_url']);
    $data['cancel_return'] = stripslashes($paypalConfig['cancel_url']);
    $data['notify_url'] = stripslashes($paypalConfig['notify_url']);

    // Set the details about the product being purchased, including the amount and currency so that these aren't overridden by the form data.
    $data['item_name'] = $itemName;
    $data['amount'] = $itemAmount;
    $data['currency_code'] = 'HKD';
    // Add any custom fields for the query string.
    //$data['custom'] = USERID;

    // Build the query string from the data.
    $queryString = http_build_query($data);
    // Redirect to paypal IPN
    header('location:' . $paypalUrl . '?' . $queryString);
    exit();
}
else {
    // Handle the PayPal response.
    $raw_data = file_get_contents('php://input');
    $raw_array = explode('&', $raw_post_data);
    $pData = [];
    foreach ($raw_array as $ra) {
        $ra = explode('=', $ra);
        if (count($ra) == 2) {
                $pData[$ra[0]] = urldecode($ra[1]);
        }
    }
    error_log(print_r($_POST,true));
    // Create a connection to the database.
    $db = mysqli_connect('buythebest.cvc6844gen9o.ap-northeast-1.rds.amazonaws.com', 'buythebest-admin','huangjiaqi8024', 'buythebest');

    // Assign posted variables to local data array.
    $data = [
        'item_name_1' => $pData['item_name1'],
        'quantity_1' => $pData['quantity1'],
        'item_name_2' => $pData['item_name2'],
        'quantity_2' => $pData['quantity2'],
        'payment_status' => $pData['payment_status'],
        'payment_amount' => $pData['mc_gross'],
        'payment_currency' => $pData['mc_currency'],
	'txn_id' => $pData['txn_id'],
	'txn_type' => $pData['txn_type'],
        'receiver_email' => $pData['receiver_email'],
        'custom' => $pData['custom'],
    ];

    // We need to verify the transaction comes from PayPal and check we've not
    // already processed the transaction before adding the payment to our
    // database.
    if (verifyTransaction($_pData) && checkTxnid($data['txn_id']) && $data['txn_type'] == 'cart') {
        // Payment successfully added into db.
    	addPayment($data);
    }
}
?>
