<?php

include_once 'bootstrap.php';

use BookShop\BookReview;
use BookShop\Auth;


$response = array(
    "success" => false,
    "message" => ""
);

if(!Auth::isFullyAuthenticated()){
    $response['message'] = "Only logged users can add reviews";
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-Type: application/json');

    echo json_encode($response);
    return;
}

$book = filter_input(INPUT_POST, 'book_id');
$user = filter_input(INPUT_POST, 'user');//Auth::getLogedUserId();
$review = filter_input(INPUT_POST, 'review');
$rating = filter_input(INPUT_POST, 'rating');

if($book && $user && $review && $rating){
   
   try{
        $r = BookReview::createReview($book, $user, $review, $rating);
        $r->update();
        
        $response["success"] = true;
        $response["message"] = "Review has been created";
            
    
    }catch(Exception $e){
        $response["message"] = $e->getMessage();
    }
    
}else{
    $response['message'] = "Not supported request";
}


header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json');

echo json_encode($response);