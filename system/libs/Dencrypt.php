<?php

/**
 * DynaPort X
 *
 * A simple yet powerful PHP framework for rapid application development.
 *
 * Licensed under BSD license
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    DynaPort X
 * @copyright  Copyright (c) 2012-2013 DynamicCodes.com (http://www.dynamiccodes.com/dynaportx)
 * @license    http://www.dynamiccodes.com/dynaportx/license   BSD License
 * @link       http://www.dynamiccodes.com/dynaportx
 * @since      File available since Release 0.2.0
 */

/**
 * Dencrypt Class
 *
 * The two-way encrypt/decrypt class.
 *
 * @package     DynaPort X
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Prasad Nayanajith
 * @link        http://www.dynamiccodes.com/dynaportx/doc/libs/dencrypt
 */
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
     * Decrypt a string
     * 
     * @param string $str The encrypted string to decrypt
     * @param string $key Secret key
     * @return string Decrypted string
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