<?php

/**
 * Auto load classes
 * 
 * @param string $class_name Name of the class
 * @param bool $no_error Show an error when there is no match
 */

function __autoload($class_name){
    /**
     * List possibilities while prioritizing them
     */
    if(preg_match('/_Controller$/i',$class_name)){
        $type = 'controller';
        $possibilities = array(
            'application/controllers/'.$class_name.'.php',
            'system/controllers/'.$class_name.'.php'
        );
    }else if(preg_match('/_Model$/i',$class_name)){
        $type = 'model';
        $possibilities = array(
            'application/models/'.$class_name.'.php',
            'system/models/'.$class_name.'.php'
        );
    }else{
        $type = 'other';
        $possibilities = array(
            'system/core/'.$class_name.'.php',
            'system/libs/'.$class_name.'.php'
        );
    }
    
    /**
     * Loop through possibilities; break and require when there is a match
     */
    foreach($possibilities AS $v){
        if(file_exists($v)){
            require $v;
            return true;
        }
    }
    
    if($type=='other'){
        if(class_exists('Error_Controller',false)){
            new Error_Controller('Something non-existing was called',500,"DPX.Helpers.__autoload: The class {$class_name} does not exists");
        }else{
            die("Error: The class {$class_name} does not exists");
        }
    }else{
        return false;
    }
}

?>
