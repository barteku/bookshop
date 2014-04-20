<?php

include_once 'bootstrap.php';

use BookShop\BookReview;


$response = array(
    "success" => false,
    "message" => ""
);

$id = filter_input(INPUT_GET, 'review_id');

if($id){
    try{
        $review = new BookReview();
        $review->setId($id);
        $review = $review->reload();

        $reviewArray = $review->toArray();

        unset($response['message']);
        $response["success"] = true;
        $response = array_merge($response, $reviewArray);
        
    } catch (Exception $e) {
        $response["message"] = $e->getMessage();
    }
}
    
header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json');

echo json_encode($response);    