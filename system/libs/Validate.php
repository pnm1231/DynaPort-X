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
 * Validate Class
 *
 * A simple validation class to validate strings against various types.
 *
 * @package     DynaPort X
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Prasad Nayanajith
 * @link        http://www.dynamiccodes.com/dynaportx/doc/libs/validate
 */
class Validate {
    
    /**
     * @param string $input String to validate
     * @param string $type Validation method
     * @return boolean Validation result
     */
    public static function this($string,$method){
        // If the string is empty, return false right away.
        if(empty($string)){
            return false;
        }
        
        $methods = array(
            'int'       => FILTER_VALIDATE_INT,
            'boolean'   => FILTER_VALIDATE_BOOLEAN,
            'float'     => FILTER_VALIDATE_FLOAT,
            'email'     => FILTER_VALIDATE_EMAIL,
            'url'       => FILTER_VALIDATE_URL,
            'ip'        => FILTER_VALIDATE_IP,
            'string'    => 'string'
        );
        
        if(!empty($method) && array_key_exists($method,$methods)){
            $string = trim($string);
            if($method=='string'){
                if(is_string($string)){
                    return true;
                }else{
                    return false;
                }
            }else{
                if(filter_var($string,$methods[$method])==false){
                    return false;
                }else{
                    return true;
                }
            }
        }else{
            new Error('Validation error',500,'DPX.Libs.Validate.this: Invalid validation type');
        }
    }

}

?>