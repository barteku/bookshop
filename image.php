<?php
include_once 'bootstrap.php';

use BookShop\Book;

$book_id = filter_input(INPUT_GET, 'book_id');

// open the file in a binary mode
$name = Book::getImageSrc($book_id);

$fp = fopen($name, 'rb');

// send the right headers
header("Content-Type: image/jpeg");
header("Content-Length: " . filesize($name));

// dump the picture and stop the script
fpassthru($fp);

