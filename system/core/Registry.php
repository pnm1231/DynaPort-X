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
 * Registry Class
 *
 * @author              JREAM
 * @link                http://jream.com
 * @copyright           2011 Jesse Boyer (contact@jream.com)
 * @license             GNU General Public License 3 (http://www.gnu.org/licenses/)
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details:
 * http://www.gnu.org/licenses/
*/

/**
 * Registry Class
 *
 * The registry class which passes data throughtout the framework.
 *
 * @package     DynaPort X
 * @subpackage  Core
 * @category    Core
 * @author      Prasad Nayanajith
 * @link        http://www.dynamiccodes.com/dynaportx/doc/core/registry
 */
class Registry{
    
    /**
     * Stored records
     * 
     * @var array
     */
    private static $_record = array();

    /** 
     * set - Places an item inside the registry record
     * 
     * @param string $key The name of the item
     * @param mixed &$item The item to reference
     * @return boolean
     */
    public static function set($key, &$item){
        self::$_record[$key] = &$item;
    }

    /**
     * get - Gets an item out of the registry
     * 
     * @param string $key The name of the item
     * @return boolean
     */
    public static function get($key){
        if(isset(self::$_record[$key])){
            return self::$_record[$key];
        }else{
            return false;
        }
    }
    
    /**
     * delete - Delete an item from the registry
     * 
     * @param string $key The name of the item
     * @return boolean
     */
    public static function delete($key){
        if(isset(self::$_record[$key])){
            unset(self::$_record[$key]);
            return true;
        }else{
            return false;
        }
    }
}

?>