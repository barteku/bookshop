<?php

include_once 'bootstrap.php';

use BookShop\Auth;

$response = array(
    "success" => false,
    "message" => ""
);


try{
    Auth::logout();

}catch(Exception $e){
    $response["message"] = $e->getMessage();
}
  


header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json');

echo json_encode($response);





