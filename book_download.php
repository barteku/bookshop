<?php

require __DIR__ . '/bootstrap.php';

use BookShop\Auth;
use BookShop\Book;

$response = array(
    "success" => false,
    "message" => ""
);

if(!Auth::isFullyAuthenticated()){
    $response['message'] = "Only logged in users can buy books";
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-Type: application/json');

    echo json_encode($response);
    return;
}

$bookId = filter_input(INPUT_GET, 'book_id');
$user = filter_input(INPUT_GET, 'user');

if($bookId && $user){
    $book = new Book();
    $book->setId($bookId);

    $book = $book->reload();
    
    if($book->canUserDownload($user)){
        $name = $book->getContent();
        
        $fp = fopen($name, 'rb');
        header('Content-Type: application/pdf');
        header("Content-Length: " . filesize($name));

        // dump the picture and stop the script
        fpassthru($fp);
    }else{
        header('HTTP/1.0 403 Forbidden');
    }
}
