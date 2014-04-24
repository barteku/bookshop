<?php
namespace BookShop;


use BookShop\User;


/**
 * Description of Auth
 *
 * @author Devante Campbell
 */
class Auth {
    
    const SESSION_AUTH_KEY = "_auth";
    const SESSION_ROLE_KEY = "_role";
    const SESSION_USER_ID_KEY = "_id";
   
    
    public static function isFullyAuthenticated(){
        if(isset($_SESSION[self::SESSION_AUTH_KEY])){
            if(User::checkIfAuth($_SESSION[self::SESSION_AUTH_KEY])){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    
    
    
    public static function login($username, $password){
        $user = User::fetchForLogin($username, $password);
        
        if($user){
            $_SESSION[self::SESSION_AUTH_KEY] = $user->generateAuthKey();
            $_SESSION[self::SESSION_ROLE_KEY] = $user->getType();
            $_SESSION[self::SESSION_USER_ID_KEY] = $user->getId();
            
            return true;
        }
        
        return false;
    }
    
    public static function logout(){
        $_SESSION[self::SESSION_AUTH_KEY] = null;
        $_SESSION[self::SESSION_ROLE_KEY] = null;
        $_SESSION[self::SESSION_USER_ID_KEY] = null;
                
        session_destroy();
    }
    
    
    public static function getLogedUserId(){
        return $_SESSION[self::SESSION_USER_ID_KEY];
    }
    
    public static function isAdmin(){
        return self::getUserRole() == User::TYPE_ADMIN;
    }
    
    
    private static function getUserRole(){
        if(self::isFullyAuthenticated()){
            if(isset($_SESSION[self::SESSION_ROLE_KEY])){
                return $_SESSION[self::SESSION_ROLE_KEY];
            }
            
            return false;
        }
    }
}
