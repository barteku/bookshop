<?php

require __DIR__ . '/bootstrap.php';

use BookShop\Auth;
use BookShop\Purchase;

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
if($token){
    Purchase::cancelPurchase($token);
}
