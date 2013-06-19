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
 * Loader Class
 *
 * The loader class which helps load controllers and models into the application.
 *
 * @package     DynaPort X
 * @subpackage  Core
 * @category    Core
 * @author      Prasad Nayanajith
 * @link        http://www.dynamiccodes.com/dynaportx/doc/core/loader
 */
class Loader {
    
    /**
     * Load a class
     * 
     * @param string $type Class type (controller/model)
     * @param string $name Class path and name
     * @param bool $noerror Don't throw 'not found' error
     * @return \class
     */
    static function load($type,$name,$noerror=0){
        
        $file = self::pathnameToFile($type,$name);
        
        // Check whether the file exists.
        if(file_exists($file)){
            
            // Check if the app is modularized, again.
            if(GLBL_MODULARIZED==true){

                // If modularized, use 'ModuleController' naming.
                list($module,$nameFName) = explode('/',$name);
                $class = ucwords($module).ucwords($nameFName);

            }else{

                // If not modularized, use 'Controller' naming.
                $class = ucwords($name);
            }

            // Add the type (Controller/Model) at the end.
            $class.= ucwords($type);

            // Include the clas file.
            require_once $file;

            // Create the object and return it.
            return new $class;
            
        }else{
            
            // If 'Do not show error' is 0/false, throw error 500.
            if($noerror==0){
                new Error('Something unavailable was called.',500,'DPX.Loader.'.ucwords($type).': '.ucwords($type).' \''.$name.'\' is not availale.');
            }
        }
    }
    
    /**
     * Convert a path-name to the file
     * 
     * @param string $type Component type (controller/model/view)
     * @param string $name Component name
     * @return string Full file name with path
     */
    static function pathnameToFile($type,$name){
        
        // Sanitize the class path/name.
        $name = preg_replace('@([^A-z0-9\/])@','',$name);
        
        // Start building the file path.
        $file = GLBL_FOLDERS_APPLICATION.'/';
        
        // Check if the app is modularized.
        if(GLBL_MODULARIZED==true){
            
            // If modularized, look inside the modules folder.
            $file.= 'modules/';
            
        }
        
        // Append the component type to the first separator
        $file.= preg_replace('@/@','/'.$type.'s/',$name,1);
        
        // Add the file type at the end.
        $file.= '.php';
        
        return $file;
    }

}

?>