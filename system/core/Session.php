<?php

class Session {
    
    /**
     * Initialize a PHP session
     */
    public static function init() {
        session_start();
    }

    /**
     * Create a new session
     * 
     * @param string $key The name of the session
     * @param mixed $value The value of the session
     */
    public static function set($key,$value){
        $_SESSION[$key] = $value;
    }
    
    /**
     * Retrieve an existing session
     * 
     * @param string $key The name of the session
     * @return mixed Date of the session
     */
    public static function get($key){
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
    }
    
    /**
     * Delete a session
     * 
     * @param string $key The name of the session
     * @return boolean True/false
     */
    public static function delete($key){
        unset($_SESSION[$key]);
        return true;
    }

}

?>