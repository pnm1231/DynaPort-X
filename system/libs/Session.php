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
 * @version    2.0.0
 * @link       http://www.dynamiccodes.com/dynaportx
 * @since      File available since Release 0.2.0
 */

/**
 * Session Class
 *
 * The session class which handles PHP sessions.
 *
 * @package     DynaPort X
 * @subpackage  Core
 * @category    Core
 * @author      Prasad Nayanajith
 * @link        http://www.dynamiccodes.com/dynaportx/doc/core/session
 */
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
        // Check whether the session has started already. If not, start it.
        if(session_id()==''){
            self::init();
        }
        
        $_SESSION[$key] = $value;
    }
    
    /**
     * Retrieve an existing session
     * 
     * @param string $key The name of the session
     * @return mixed Date of the session
     */
    public static function get($key){
        // Check whether the session has started already. If not, start it.
        if(session_id()==''){
            self::init();
        }
        
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
        // Check whether the session has started already. If not, start it.
        if(session_id()==''){
            self::init();
        }
        
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
        return true;
    }

}

?>