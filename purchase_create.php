<?php

include_once __DIR__ . '/bootstrap.php';

use BookShop\Purchase;
use BookShop\Auth;


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

$bookId = filter_input(INPUT_GET, 'book_id');
$user = filter_input(INPUT_GET, 'user');



if($bookId && $user){
    
    try{
        $redirectUrl = Purchase::createPurchase($bookId, $user);
        
        header("Location: $redirectUrl");
    }catch(Exception $e){
        $response["message"] = $e->getMessage();
    }
    
}else{
    $response['message'] = "Not supported request";
}


header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json');

echo json_encode($response);