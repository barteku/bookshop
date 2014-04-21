<?php

include_once 'bootstrap.php';

use BookShop\Log;
use BookShop\Auth;


$response = array(
    "success" => false,
    "message" => ""
);

if(!Auth::isFullyAuthenticated() && !Auth::isAdmin()){
    $response['message'] = "Only logged in users can buy books";
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-Type: application/json');

    echo json_encode($response);
    return;
}

$start = filter_input(INPUT_GET, 'start');
$length = filter_input(INPUT_GET, 'length');


try{
    if(!$user && !Auth::isAdmin()){
        $user = Auth::getLogedUserId();
    }
    
    $log = Log::search($start, $length);
    $response = $log;
    
} catch (Exception $e) {
    $response = array(
        "success" => false,
        "message" => $e->getMessage()
    );
}

header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json');

echo json_encode($response); 
