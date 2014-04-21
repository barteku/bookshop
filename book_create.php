<?php

include_once 'bootstrap.php';

use BookShop\Book;
use BookShop\Auth;



if(!Auth::isFullyAuthenticated() && !Auth::isAdmin()){
    $response['message'] = "Only Admin can add books";
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-Type: application/json');

    echo json_encode($response);
    return;
}

$title = filter_input(INPUT_POST, 'title');
$authors = filter_input(INPUT_POST, 'authors');
$description = filter_input(INPUT_POST, 'description');
$price = filter_input(INPUT_POST, 'price');
$image = $_FILES['image'];
$content = $_FILES['content'];


$response = array(
    "success" => false,
    "message" => ""
);



if($title && $authors && $description && $price){
    
    try{
        Book::createBook($title, $authors, $description, $price, $image, $content);
                
        $response["success"] = true;
        $response["message"] = "Book has been created";
        
    }catch(Exception $e){
        $response["message"] = $e->getMessage();
    }
    
}else{
    $response['message'] = "Not supported request";
}


header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json');

echo json_encode($response);