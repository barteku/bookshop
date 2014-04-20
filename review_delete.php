<?php

include_once 'bootstrap.php';

use BookShop\BookReview;
use BookShop\Auth;


$response = array(
    "success" => false,
    "message" => ""
);

if(!Auth::isFullyAuthenticated()){
    $response['message'] = "Only author can edit review";
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-Type: application/json');

    echo json_encode($response);
    return;
}


$id = filter_input(INPUT_GET, 'review_id');


if($id){
    $review = new BookReview();
    $review->setId($id);
    
    if($review->canEdit(Auth::getLogedUserId()) || Auth::isAdmin()){
    
        try{
            BookReview::delete($id);    
           
            $response["success"] = true;
            $response["message"] = "Review has been deleted";

        }catch(Exception $e){
            $response["message"] = $e->getMessage();
        }
    }else{
        $response["message"] = "Only author or admin can remove review";
    }
    
}else{
    $response['message'] = "Not supported request";
}


header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json');

echo json_encode($response);