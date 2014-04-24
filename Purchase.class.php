<?php

namespace BookShop;


use BookShop\BookPaypalPayment;
use BookShop\Book;
use BookShop\Database;
use \PDO;


/**
 * Description of Purchase
 *
 * @author Devante Campbell
 */
class Purchase {
    
    const STATUS_NEW = "new";
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';
    
    private $id;
    
    private $bookId;
    
    private $user;
    
    private $token;
    
    private $payerId;
    
    private $status;
    
    private $paymentId;
    
    
    public function __construct() {
        $this->status = self::STATUS_NEW;
    }


    
    public function getId() {
        return $this->id;
    }

    public function getBookId() {
        return $this->bookId;
    }

    public function getUser() {
        return $this->user;
    }

    public function getToken() {
        return $this->token;
    }

    public function getPayerId() {
        return $this->payerId;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setBookId($bookId) {
        $this->bookId = $bookId;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function setToken($token) {
        $this->token = $token;
    }

    public function setPayerId($payerId) {
        $this->payerId = $payerId;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getPaymentId() {
        return $this->paymentId;
    }

    public function setPaymentId($paymentId) {
        $this->paymentId = $paymentId;
    }

    
    
    private function save(){
        $query = "INSERT INTO purchase SET book_id = :book, user = :user, token = :token, status = :status, paymentId = :payment";
        
        try{
            $stmt = Database::getConnection()->prepare($query);
            $stmt->bindParam(":book", $this->bookId, PDO::PARAM_INT);
            $stmt->bindParam(":user", $this->user, PDO::PARAM_INT);
            $stmt->bindParam(":token", $this->token, PDO::PARAM_STR);
            $stmt->bindParam(":status", $this->status);
            $stmt->bindParam(":payment", $this->paymentId);
            
            
            return $stmt->execute();
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    
    public function reload(){
        $query = "SELECT b.* FROM purchase b WHERE b.id = :id";
                
        $stmt = Database::getConnection()->prepare($query);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchObject("BookShop\Purchase");
        
    }
     
    public function setAsPaid($payerId){
        $query = "UPDATE purchase set status = :status, PayerID = :payer WHERE id = :id";
        
        $paid = self::STATUS_PAID;
        
        $stmt = Database::getConnection()->prepare($query);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":status", $paid, PDO::PARAM_STR);
        $stmt->bindParam(":payer", $payerId, PDO::PARAM_STR);
        
        try{
            return $stmt->execute();
        } catch (\Exception $ex) {
            throw new \Exception($e->getMessage());
        }
        
    }
    
    public static function cancelPurchase($token){
        $query = "UPDATE purchase set status = :status WHERE token = :token";
       
        $cancelled = self::STATUS_CANCELLED;
        
        $stmt = Database::getConnection()->prepare($query);
        $stmt->bindParam(":token", $token, PDO::PARAM_STMT);
        $stmt->bindParam(":status", $cancelled, PDO::PARAM_STR);
        
        try{
            return $stmt->execute();
        } catch (\Exception $ex) {
            throw new \Exception($e->getMessage());
        }
        
    }
    
    public static function findByToken($token){
        $query = "SELECT b.* FROM purchase b WHERE b.token = :token";
                
        $stmt = Database::getConnection()->prepare($query);
        $stmt->bindParam(":token", $token, PDO::PARAM_STR);
        
        $stmt->execute();
        return $stmt->fetchObject("BookShop\Purchase");
    }

    public static function createPurchase($bookId, $user){
        $purchase = new Purchase();
        $purchase->setBookId($bookId);
        $purchase->setUser($user);
        
        $book = new Book();
        $book->setId($bookId);
        $book = $book->reload();
        
        try
        {
            $payment = new BookPaypalPayment($book);
            $payment->initPayment();
            
        }  catch (\Exception $e){
            throw new Exception($e->getMessage());
        }
        
        try
        {
            $purchase->setToken($payment->getToken());
            $purchase->setPaymentId($payment->getTransactionId());

            $purchase->save();
            
            return $payment->getRedirectUrl();
        }  catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
        
    }
    
    
    public static function search($user = null, $book = null, $start = 0, $length = 10){
        $query = "SELECT book_id, user FROM purchase";
        $params = array();
        
        if($book){
            $query .= " WHERE book_id = :book";
            $params['book'] = $book;
            
            if($user){
                $query .= " AND user = :user";
                $params['user'] = $user;
            }
            
        }elseif($user){
            $query .= " WHERE user = :user";
                $params['user'] = $user;
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
