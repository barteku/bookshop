<?php

namespace BookShop;

use \PDO;
use BookShop\Database;

/**
 * Description of Logger
 *
 * @author Devante Campbell
 */
class Logger {
    
    
        
    
    public static function log($message, $hash = null){
        $query = "INSERT INTO log SET message = :message, hash = :hash";
        
        try{
            $stmt = Database::getConnection()->prepare($query);
            $stmt->bindParam(":message", $message);
            $stmt->bindParam(":hash", $hash);
            
            return $stmt->execute();
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    
    
    public static function search($start, $length){
        $query = "SELECT id as number, created as timestamp, message as cleartest_message, hash, 'signature' as signature FROM log";
        $params = array();
        
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
