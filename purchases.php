<?php

include_once 'bootstrap.php';

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


$book = filter_input(INPUT_GET, 'book_id');
$user = filter_input(INPUT_GET, 'user');
$start = filter_input(INPUT_GET, 'start');
$length = filter_input(INPUT_GET, 'length');


try{
    if(!$user && !Auth::isAdmin()){
        $user = Auth::getLogedUserId();
    }
    
    $purchases = Purchase::search($user, $book, $start, $length);
    $response = $purchases;
    
} catch (Exception $e) {
    $response = array(
        "success" => false,
        "message" => $e->getMessage()
    );
}

header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json');

echo json_encode($response); 
