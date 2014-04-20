<?php

include_once 'bootstrap.php';

use BookShop\Book;


$title = filter_input(INPUT_GET, 'title');
$authors = filter_input(INPUT_GET, 'authors');
$start = filter_input(INPUT_GET, 'start');
$length = filter_input(INPUT_GET, 'length');

$response = array(
    "success" => false
);

try{
    $books = Book::search($title, $authors, $start, $length);
    $response = $books;
    
} catch (Exception $e) {
    $response["message"] = $e->getMessage();
}

header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json');

echo json_encode($response); 
