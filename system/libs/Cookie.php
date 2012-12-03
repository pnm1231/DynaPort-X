<?php

class Cookie {
    
    /**
     * Create a cookie
     * 
     * @param string $key Name
     * @param string $value Data
     * @param int $expire Expire unix timestamp (optional)
     * @param string $path Path (optional)
     * @param string $domain Domain (optional)
     * @return boolean
     */
    public static function set($key,$value,$expire=null,$path='/',$domain=null){
        if($domain==null){
            $domain = $_SERVER['SERVER_NAME'];
        }
        setcookie($key,$value,$expire,$path,$domain);
        return true;
    }
    
    /**
     * Get a cookie
     * 
     * @param string $key Cookie name
     * @return string Data
     */
    public static function get($key){
        if(isset($_COOKIE[$key]))
        return $_COOKIE[$key];
    }
    
    /**
     * Delete a cookie
     * 
     * @param string $key Cookie name
     * @param string $path Path (optional)
     * @param string $domain Domain (optional)
     * @return boolean
     */
    public static function delete($key,$path='/',$domain=null){
        if($domain==null){
            $domain = $_SERVER['SERVER_NAME'];
        }
        setcookie($key,'',time()-3600,'/',$domain);
        return true;
    }

}

?>