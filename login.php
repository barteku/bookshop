<?php

include_once 'bootstrap.php';

use BookShop\Auth;

$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');
$response = array(
    "success" => false,
    "message" => ""
);



if($username && $password){
    try{
        if(Auth::login($username, $password)){
            try{
                $response["success"] = true;
                $response["message"] = "Logged";
            }catch(Exception $e){
                $response["message"] = $e->getMessage();
            }
        }else{
            $response["message"] = "User ad poaasord not match";
        }
        
    }catch(Exception $e){
        $response["message"] = $e->getMessage();
    }
    
}else{
    $response['message'] = "Not supported request";
}


header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json');


echo json_encode($response);





