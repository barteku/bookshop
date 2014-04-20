<?php

include_once 'bootstrap.php';

use BookShop\Book;


$response = array(
    "success" => false
);

$id = filter_input(INPUT_GET, 'book_id');

if($id){
    try{
        $book = new Book();
        $book->setId($id);
        $book = $book->reload(true);

        $bookArray = $book->toArray(true);

        $response["success"] = true;
        $response['reviews'] = $bookArray['reviews'];
        unset($bookArray['reviews']);
        $response['book'] = $bookArray;
            
    } catch (Exception $e) {
        $response["message"] = $e->getMessage();
    }
}else{
    $response['message'] = "Not supported request";
}
    
header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json');

echo json_encode($response); 