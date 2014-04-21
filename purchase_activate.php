<?php

require __DIR__ . '/bootstrap.php';

use BookShop\Auth;
use BookShop\BookPaypalPayment;

$response = array(
    "success" => false,
    "message" => ""
);

if(!Auth::isFullyAuthenticated()){
    $response['message'] = "Only logged in users can buy books";
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-Type: application/json');

    echo json_encode($response);
    return;
}


$token = filter_input(INPUT_GET, 'token');
$payerId = filter_input(INPUT_GET, 'PayerID');

if($token && $payerId) {
    if(BookPaypalPayment::executePayment($token, $payerId)){
        $response['message'] = "You have bough a book, enjoy reading";
    }
    
} else {
    $response['message'] = "Not supported request";
}

header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json');

echo json_encode($response);