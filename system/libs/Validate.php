<?php

/*
 * This file is part of the DynaPort X package.
 *
 * (c) Prasad Nayanajith <prasad.n@dynamiccodes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

/**
 * Validate a string
 */
class Validate {
    
    /**
     * @param string $input String to validate
     * @param string $type Validation method
     * @return boolean Validation result
     */
    public static function this($string,$method){
        $methods = array(
            'int'       => FILTER_VALIDATE_INT,
            'boolean'   => FILTER_VALIDATE_BOOLEAN,
            'float'     => FILTER_VALIDATE_FLOAT,
            'email'     => FILTER_VALIDATE_EMAIL,
            'url'       => FILTER_VALIDATE_URL,
            'ip'        => FILTER_VALIDATE_IP
        );
        
        if(!empty($method) && array_key_exists($method,$methods)){
            if(filter_var($string,$methods[$method])==false){
                return false;
            }else{
                return true;
            }
        }else{
            new Error_Controller('Validation error',500,'DPX.Libs.Validate.this: Invalid validation type');
        }
    }

}

?>