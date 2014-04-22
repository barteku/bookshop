<?php

namespace BookShop;


use BookShop\Database;
use \PDO;

/**
 * Description of Book
 *
 * @author bart
 */
class Book {
    
    private $id;
    
    private $title;
    
    private $authors;
    
    private $description;
    
    private $price;
    
    private $image;
    
    private $content;
      
    private $reviews;
    
    

    public function getId() {
        return $this->id;
    }
    
    public function getTitle() {
        return $this->title;
    }

    public function getAuthors() {
        return $this->authors;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getImage() {
        return $this->image;
    }

    public function getContent() {
        return $this->content;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }

    public function setAuthors($authors) {
        $this->authors = $authors;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setPrice($price) {
        $this->price = $price;
    }
    
    public function getReviews() {
        return $this->reviews;
    }

    public function setReviews($reviews) {
        $this->reviews = $reviews;
    }

    
    
    
    /**
     * 
     * @return type
     * 
     */
    public function save(){
        $query = "INSERT INTO book SET title = :title, authors = :authors, description = :description, price = :price, image = :image, content = :content";
        
        try{
            $db = Database::getConnection();
            $stmt = $db->prepare($query);
            $stmt->bindParam(":title", $this->title, PDO::PARAM_STR);
            $stmt->bindParam(":authors", $this->authors, PDO::PARAM_STR);
            $stmt->bindParam(":description", $this->description, PDO::PARAM_STR);
            $stmt->bindParam(":price", $this->price);
            $stmt->bindParam(":image", $this->image, PDO::PARAM_STR);
            $stmt->bindParam(":content", $this->content, PDO::PARAM_STR);
            
            $stmt->execute();
            
            return $db->lastInsertId('id');
            
            //return $stmt->fetchObject("BookShop\Book");
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    
    /**
     * 
     * @param type $image
     * @param type $content
     */
    public function saveFiles($image = null, $content = null){
        
        if($image['error'] === UPLOAD_ERR_OK){
            if($image["type"] == "image/jpeg"){
                $filename = DATA_FOLDER . DIRECTORY_SEPARATOR . md5("image" . time()) . '.jpeg';
                
                if(move_uploaded_file($image['tmp_name'], $filename)){
                    $this->image = $filename;
                }
            }
        }
        
        if($content['error'] === UPLOAD_ERR_OK){
            if($content["type"] == "application/pdf"){
                $filename = DATA_FOLDER . DIRECTORY_SEPARATOR . md5("content" . time()) . '.pdf';
                
                if(move_uploaded_file($content['tmp_name'], $filename)){
                    $this->content = $filename;
                }
            }
        }
        
    }
    
    public function toArray($withReviews = false) {
        $data = array(
            'book_id' => $this->id,
            'title' => $this->title,
            'authors' => $this->authors,
            'description' => $this->description,
            'price' => $this->price
        );
        
        if($withReviews){
            $data['reviews'] = $this->reviews;
        }
        
        return $data;
    }
    
    
    public function reload($withReviews = false){
        if($withReviews){
            $query = "SELECT b.*, GROUP_CONCAT(r.id) AS reviews FROM book b LEFT JOIN book_review r ON r.book_id = b.id WHERE b.id = :id";
        }else{
            $query = "SELECT b.* FROM book b WHERE b.id = :id";
        }
        
        $stmt = Database::getConnection()->prepare($query);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchObject("BookShop\Book");
        
    }

    
    public function canUserDownload($user){
        $query = "SELECT p.id from purchase p where p.book_id = :book and p.user = :user and p.status = :status and downloads < 100";
        
        $paid = Purchase::STATUS_PAID;
        
        $stmt = Database::getConnection()->prepare($query);
        $stmt->bindParam(":status", $paid, PDO::PARAM_STR);
        $stmt->bindParam(":user", $user, PDO::PARAM_INT);
        $stmt->bindParam(":book", $this->id, PDO::PARAM_INT);
        $stmt->execute();
        
        $canDownload = false;
        
        if($purchase = $stmt->fetch()){
            $query = "update purchase set downloads = downloads + 1 where id = :id";
            $stmt = Database::getConnection()->prepare($query);
            $stmt->bindParam(":id", $purchase['id'], PDO::PARAM_INT);
            $stmt->execute();
            
            $canDownload = true;
        }
            
        return $canDownload;
    }
    
    private function increaseCounterOnDownloads($user){
        $paid = Purchase::STATUS_PAID;
        
        $query = "select p.id purchase p where p.user = :user and p.status = :status and downloads < 100 order by id asc limit 1";
    }
    
    /**
     * 
     * @param type $title
     * @param type $authors
     * @param type $description
     * @param type $price
     * @param type $image
     * @param type $content
     * @throws Exception
     */
    public static function createBook($title, $authors, $description, $price, $image, $content){
        $book = new Book;
        
        $book->setTitle($title);
        $book->setAuthors($authors);
        $book->setDescription($description);
        $book->setPrice($price);
        
        $book->saveFiles($image, $content);
        
        $bookId = $book->save();
        
        if(!$bookId){
            throw new \Exception("Book was not created", 500);
        }
        
        return $bookId;
        
    }
    
    /**
     * 
     * @param type $book_id
     * @return type
     */
    public static function getImageSrc($book_id){
        $query = "SELECT image from book where id = :id";
        
        try{
            $stmt = Database::getConnection()->prepare($query);
            $stmt->bindParam(":id", $book_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $res = $stmt->fetch();
            
            return $res['image'];
            
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    
    
    
    public static function search($title = null, $authors = null, $start = 0, $length = 10){
        $query = "SELECT id as book_id, title, authors, description, price FROM book";
        $params = array();
        
        if($title){
            $query .= " WHERE title like :title";
            $params['title'] = $title;
            
            if($authors){
                $query .= " AND authors like :authors";
                $params['authors'] = $authors;
            }
            
        }elseif($authors){
            $query .= " WHERE authors like :authors";
            $params['authors'] = $authors;
        }
        
        if($start && $length){
            $query .= " LIMIT " . $start . ", ".$length;
        }   
        
        $stmt = Database::getConnection()->prepare($query);
        foreach ($params as $key=>$param){
            $stmt->bindParam(":".$key, $param);
        }
        
        $stmt->execute();
            
        return $stmt->fetchAll();
    }
    
}
