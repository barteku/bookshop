<?php

namespace BookShop;

use \PDO;
use BookShop\Database;

/**
 * Description of Logger
 *
 * @author bartek
 */
class Logger {
    
    private $id;
    
    private $message;
    
    private $created;
        
    
    public static function log($message){
        $query = "INSERT INTO log SET message = :message";
        
        try{
            $stmt = Database::getConnection()->prepare($query);
            $stmt->bindParam(":message", $message);
            
            return $stmt->execute();
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    
    
}
