<?php
namespace BookShop;

use BookShop\Database;
use \PDO;

/**
 * Description of User
 *
 * @author bartek
 */
class User {

    const TYPE_USER = "user";
    const TYPE_ADMIN = "admin";
    
    const HASHING_ALGORITHM = "md5";
    
    private $id;
    private $username;
    private $password;
    private $email;
    private $type;
    
    
    
    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getType() {
        return $this->type;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setType($type) {
        $this->type = $type;
    }
    
    public function setPassword($password) {
        $this->password = $password;
    }

        
    /**
     * 
     * @return type
     */
    public function generateAuthKey(){
        return md5(date('Y-m-d') . $this->getEmail());
    }
    
    
    /**
     * 
     * @return type
     */
    public function update(){
        
        if($this->id){
            $query = "UPDATE user set username = :username, email = :email, type = :type WHERE id = :id";
        }else{
            $query = "INSERT INTO user set username = :username, email = :email, password = :password, type = :type";
        }
        
        try{
            $stmt = Database::getConnection()->prepare($query);
            $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
            $stmt->bindParam(":username", $this->username, PDO::PARAM_STR);
            $stmt->bindParam(":type", $this->type, PDO::PARAM_STR);
            
            if($this->id){
                $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
            }else{
                $stmt->bindParam(":password", $this->password, PDO::PARAM_STR);
            }
            
            return $stmt->execute();
                        
        }catch(Exception $e){
            echo $e->getMessage();
        }
        
    }
    
    
    
    
    
    
    /**
     * static methods
     */
    
    /**
     * 
     * @param type $username
     * @param type $email
     * @param type $password
     * @param type $type
     * @return \BookShop\BookShop\User
     * 
     */
    public static function createUser($username = null, $email = null, $password = null, $type = self::TYPE_USER){
        $user = new User;
        
        $user->setUsername($username);
        $user->setType($type);
        $user->setEmail($email);
        
        //to avoid setting blank passwords
        if($password){
            $user->setPassword(hash(self::HASHING_ALGORITHM, $password));
        }
        
        return $user;
    }

    
    

    public static function checkIfAuth($authKey){
        $query = "SELECT u.id FROM user u WHERE md5(CONCAT(DATE_FORMAT(now(),'%Y-%m-%d'),u.email)) = :auth_key";
        
        try{
            $stmt = Database::getConnection()->prepare($query);
            $stmt->bindParam(":auth_key", $authKey, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->rowCount() > 0 ? true : false;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    
    public static function fetchForLogin($username, $password){
        $query = "SELECT * FROM user u WHERE u.username = :username AND password = md5(:password)";
        
        try{
            $stmt = Database::getConnection()->prepare($query);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $password, PDO::PARAM_STR);
            $stmt->execute();
            
            $object = $stmt->fetchObject("BookShop\User");
            
            return $object;
        }catch(Exception $e){
            echo $e->getMessage();
        }
        
    }
    
    public static function checkIfAvailableUsernameAndEmail($username, $email){
        $query = "SELECT u.id FROM user u WHERE u.username = :username OR email = :email";
        
        try{
            $stmt = Database::getConnection()->prepare($query);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->rowCount() == 0 ? true : false;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
