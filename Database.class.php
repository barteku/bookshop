<?php

namespace BookShop;

use \PDO;

/**
 * Description of Database
 *
 * @author Bartosz Urbanski
 */
class Database {

    private static $connection = null;
    
    
    private static $hostname = DB_HOST;
    private static $dbname = DB_DATABASE;
    private static $username = DB_USERNAME;
    private static $password = DB_PASSWORD;

    
    
    
    private static $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );
    
    
    public static function getConnection()
    {
        if (!self::$connection) {
            try {
                
                self::$connection = new PDO("mysql:host=" . self::$hostname . ";dbname=" . self::$dbname, self::$username, self::$password, self::$options);
            } catch (PDOException $e) {
                echo "Failed to get DB handle: " . $e->getMessage() . "\n";
                exit;
            }
        }
        
        return self::$connection;
    }
    
}
