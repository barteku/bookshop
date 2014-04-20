<?php

include_once 'bootstrap.php';

use BookShop\User;


$username = filter_input(INPUT_POST, 'username');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password');
$response = array(
    "success" => false,
    "message" => ""
);



if($username && $email && $password){
    
    try{
        if(User::checkIfAvailableUsernameAndEmail($username, $email)){
            $user = User::createUser($username, $email, $password);
            
            try{
                $user->update();
            
                $response["success"] = true;
                $response["message"] = "User has been created";
            }catch(Exception $e){
                $response["message"] = $e->getMessage();
            }
        }else{
            $response["message"] = "User with username or email already exist. Please try again";
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