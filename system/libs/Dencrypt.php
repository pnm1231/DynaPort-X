<?php

class Dencrypt {

    /**
     * Encrypt a string
     * 
     * @param string $str The string to encrypt
     * @param string $key Secret key
     * @return string Encrypted string
     */
    public static function encrypt($str,$key=DENCRYPT_KEY){
        if(!empty($str) && !empty($key)){
            $result = '';
            for($i=0;$i<strlen($str);$i++) {
                $char = substr($str,$i,1);
                $keychar = substr($key,($i%strlen($key))-1,1);
                $char = chr(ord($char)+ord($keychar));
                $result.=$char;
            }
            return urlencode(base64_encode($result));
        }
    }
    
    /**
     * Decrept a string
     * 
     * @param string $str The encrypted string to decrypt
     * @param string $key Secret key
     * @return string Decrepted string
     */
    public static function decrypt($str,$key=DENCRYPT_KEY){
        if(!empty($str) && !empty($key)){
            $str = base64_decode(urldecode($str));
            $result = '';
            for($i=0;$i<strlen($str);$i++) {
                $char = substr($str,$i,1);
                $keychar = substr($key,($i%strlen($key))-1,1);
                $char = chr(ord($char)-ord($keychar));
                $result.=$char;
            }
            return $result;
        }
    }

}

?>