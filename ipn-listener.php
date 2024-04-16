<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');
require('functions.php');
    // Handle the PayPal response.
    $raw_data = file_get_contents('php://input');
    $raw_array = explode('&', $raw_data);
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
        'item_name_1' => $pData['item_name_1'],
        'quantity_1' => $pData['quantity_1'],
        'item_name_2' => $pData['item_name_2'],
        'quantity_2' => $pData['quantity_2'],
        'payment_status' => $pData['payment_status'],
        'payment_amount' => $pData['mc_gross'],
        'payment_currency' => $pData['mc_currency'],
        'txn_id' => $pData['txn_id'],
        'txn_type' => $pData['txn_type'],
        'receiver_email' => $pData['receiver_email'],
	'custom' => $pData['custom'],
	'item_number' => $pData['item_number']
    ];

    // We need to verify the transaction comes from PayPal and check we've not
    // already processed the transaction before adding the payment to our
    // database.
    if (verifyTransaction($pData) && checkTxnid($data['txn_id']) && $data['txn_type'] == 'cart') {
        // Payment successfully added into db.
        addPayment($data);
    }else{
            //Payment failed
            addPayment($data);
    }

?>
