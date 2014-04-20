<?php

namespace BookShop;


use \PDO;

/**
 * Description of BookReview
 *
 * @author bartek
 */
class BookReview {
    
    private $id;
    
    private $book_id;
    
    private $user;
    
    private $review;
    
    private $rating;
    
    
    
    public function getId() {
        return $this->id;
    }

    public function getBookId() {
        return $this->book_id;
    }

    public function getUser() {
        return $this->user;
    }

    public function getReview() {
        return $this->review;
    }

    public function getRating() {
        return $this->rating;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setBookId($book_id) {
        $this->book_id = $book_id;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function setReview($review) {
        $this->review = $review;
    }

    public function setRating($rating) {
        $this->rating = $rating;
    }


    public function update(){
        
        if($this->id){
            $query = "UPDATE book_review SET book_id = :book, user = :user, review = :review, rating = :rating WHERE id = :id";
        }else{
            $query = "INSERT INTO book_review SET book_id = :book, user = :user, review = :review, rating = :rating";
        }
        
        try{
            $stmt = Database::getConnection()->prepare($query);
            $stmt->bindParam(":book", $this->book_id, PDO::PARAM_INT);
            $stmt->bindParam(":user", $this->user, PDO::PARAM_INT);
            $stmt->bindParam(":review", $this->review, PDO::PARAM_STR);
            $stmt->bindParam(":rating", $this->rating, PDO::PARAM_INT);
           
            if($this->id){
                $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            }
            
            return $stmt->execute();
        }catch(Exception $e){
            throw new \Exception($e->getMessage(), 500);
        }
        
    }
    
    
    public function canEdit($user_id){
        $query = "SELECT id FROM book_review WHERE id = :id AND user = :user";
        $stmt = Database::getConnection()->prepare($query);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":user", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        
        return $stmt->rowCount() > 0 ? true : false;
    }
    
    public function reload(){
        $query = "SELECT r.* FROM book_review r WHERE r.id = :id";
                
        $stmt = Database::getConnection()->prepare($query);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->fetchObject("BookShop\BookReview");
        
        
    }
    
    public function toArray(){
        return array(
            'book_id' => $this->book_id,
            'user' => $this->user,
            'review' => $this->review,
            'rating' => (int)$this->rating
        );
    }
    
    
    
    
    
    
    public static function delete($id){
        try{
            $query = "DELETE FROM book_review WHERE id = :id";
            $stmt = Database::getConnection()->prepare($query);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        
            return $stmt->execute();
        }catch(Exception $e){
            throw new \Exception($e->getMessage(), 500);
        }
    }
    
    
    public static function createReview($book_id, $user, $review, $rating){
        $r = new BookReview();
        
        $r->setBookId($book_id);
        $r->setUser($user);
        $r->setReview($review);
        $r->setRating($rating);
        
        if($r->update()){
            return $r;
        }else{
            throw new \Exception("Review can not be created", 500);
        }
    }
    
    
    
}
