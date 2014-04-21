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

$book = filter_input(INPUT_POST, 'book_id');
$user = filter_input(INPUT_POST, 'user');
$review = filter_input(INPUT_POST, 'review');
$rating = filter_input(INPUT_POST, 'rating');
$id = filter_input(INPUT_GET, 'review_id');


if($book && $user && $review && $rating && $id){
   
    $review = BookReview::createReview($book, $user, $review, $rating);
    $review->setId($id);
    
    if($review->canEdit($user)){
    
        try{
            $review->update();    
            $response["success"] = true;
            $response["message"] = "Review has been updated";


        }catch(Exception $e){
            $response["message"] = $e->getMessage();
        }
    }else{
        $response["message"] = "Only author can edit review";
    }
    
}else{
    $response['message'] = "Not supported request";
}


header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json');

echo json_encode($response);