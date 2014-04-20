<?php

include_once 'bootstrap.php';

use BookShop\Purchase;
use BookShop\Auth;



if(!Auth::isFullyAuthenticated()){
    $response['message'] = "Only logged in users can buy books";
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-Type: application/json');

    echo json_encode($response);
    return;
}

$bookId = filter_input(INPUT_GET, 'book_id');
$user = filter_input(INPUT_GET, 'user');

$response = array(
    "success" => false,
    "message" => ""
);



if($bookId && $user){
    
    try{
        Purchase::createPurchase($bookId, $user);
                
        $response["success"] = true;
        $response["message"] = "Book has been created";
        
    }catch(Exception $e){
        $response["message"] = $e->getMessage();
    }
    
}else{
    $response['message'] = "Not supported request";
}


header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json');

echo json_encode($response);