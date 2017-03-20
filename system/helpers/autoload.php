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
 * @link       http://www.dynamiccodes.com/dynaportx/doc/helpers/autoload
 * @since      File available since Release 0.2.0
 */

/**
 * Auto load classes
 * 
 * @param string $class_name Name of the class
 * @param bool $no_error Show an error when there is no match
 */
function __autoload($class_name){
    
    // List possibilities while prioritizing them
    $possibilities = array(
        'system/core/'.$class_name.'.php',
        'system/libs/'.$class_name.'.php',
        GLBL_FOLDERS_APPLICATION.'/libs/'.$class_name.'.php'
    );
    
    // Loop through possibilities; break and require when there is a match
    foreach($possibilities AS $v){
        if(file_exists($v)){
            require $v;
            return true;
        }
    }
    
    if(class_exists('Error',false)){
        new DPxError('Something non-existing was called',500,"DPX.Helpers.__autoload: The class {$class_name} does not exists");
    }else{
        die("Error: The class {$class_name} does not exists");
    }
}

?>
