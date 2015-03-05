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
 * Bootstrap Class
 *
 * The bootstrap of the framework.
 *
 * @package     DynaPort X
 * @subpackage  Core
 * @category    Core
 * @author      Prasad Nayanajith
 * @link        http://www.dynamiccodes.com/dynaportx/doc/core/bootstrap
 */
class DynaPortX {

    function __construct(){

        // Give credit ;)
        header('X-Framework: DynaPort X/2.0.71');
        
        // Auto-start session if it is required.
        if(GLBL_AUTOSTART_SESSION==true){
            Session::init();
        }
        
        // Check whether Hooks are enabled.
        if(GLBL_ENABLE_HOOKS==true){
            
            // Run hooks registered to post-start.
            Hooks::run('dpx_post_start');
        }

        // Include routing rules if available.
        if(file_exists(GLBL_FOLDERS_APPLICATION.'/config/routes.php')){
            require GLBL_FOLDERS_APPLICATION.'/config/routes.php';
        }
        
        // Call the URI library to break down the URL into sections.
        $uri = new Uri();
        
        // Check if the URI returned any errors. If so, throw it out.
        if(!empty($uri->error)){
            new Error('',$uri->error);
        }
        
        // Start generating the path.
        $path = GLBL_FOLDERS_APPLICATION.'/';
        
        // Check whether the app is modularized.
        if(GLBL_MODULARIZED==true){
            
            // Since the app is modularized, add 'modules' to the path.
            $path.= 'modules/';
            
            // Check whether a module is called.
            if(empty($uri->module)){
                
                // If a module is not called, set module to the default module name.
                $uri->module = GLBL_DEFAULT_MODULE;
                
                // Add the default module name to the registry.
                Registry::set('dpx_module',$uri->module);
            }
                
            // Add the called module name to the path.
            $path.= $uri->module.'/';

            // Check the availability of the module and throw 404 in case of an error.
            if(!is_dir($path)){
                new Error('',404,'DPX.DynaPortX.__construct: Module \''.ucwords($uri->module).'\' does not exist.');
            }
        }
        
        // Add the controllers folder to the path.
        $path.= 'controllers/';
        
        // If no controller is called, set it to the default controller.
        if(empty($uri->controller)){
            
            // Set the controller name to the default controller name.
            $uri->controller = GLBL_DEFAULT_CONTROLLER;
            
            // Add the default controller name to the registry.
            Registry::set('dpx_controller',$uri->controller);
            
        }
        
        // Add the controller name to the path.
        $path.= $uri->controller;

        // Check the availability of the controller.
        // If available, include it and create an object.
        // If not, throw 404.
        if(file_exists($path.'.php')){
            
            // Include the controller file.
            require_once $path.'.php';

            // Generate the controller class name.
            $classname = ucfirst($uri->module);
            $classname.= ucfirst($uri->controller);
            $classname.= 'Controller';
            
            // Check the availability of the controller.
            // Throw error 404 if not available.
            if(class_exists($classname,false)){

                // Check whether Hooks are enabled.
                if(GLBL_ENABLE_HOOKS==true){

                    // Run hooks registered to pre-controller.
                    Hooks::run('dpx_pre_controller');
                }

                // Create the controller object.
                $controller = new $classname;
                
                // Check the availability of 'doBefore' method
                if(method_exists($controller,'doBefore')){
                    
                    // Call the 'doBefore' method along with parameters if available.
                    Object::callMethod($controller,'doBefore',$uri->params);
                    
                }

                // If no method is called, set it to the default method.
                if(empty($uri->method)){

                    // Set the controller name to the default controller name.
                    $uri->method = GLBL_DEFAULT_METHOD;
                    
                    // Add the default method name to the registry.
                    Registry::set('dpx_method',$uri->method);

                }
                
                // Check the availability of the method with the request type (get/post) first.
                // If not available, check the availability of the 'public' method.
                // If failed, throw a 404.
                if(method_exists($controller,strtolower($_SERVER['REQUEST_METHOD']).'_'.$uri->method)){
                    
                    // Call the request type's method along with parameters if available.
                    Object::callMethod($controller,strtolower($_SERVER['REQUEST_METHOD']).'_'.$uri->method,$uri->params);
                    
                }else if(method_exists($controller,'public_'.$uri->method)){
                    
                    // Call the 'public' method along with parameters if available.
                    Object::callMethod($controller,'public_'.$uri->method,$uri->params);
                    
                }else{
                    
                    //Throw error 404
                    new Error('',404,'DPX.DynaPortX.__construct: Method \''.$uri->method.'\' does not exist.');
                }
                
                // Check the availability of 'doAfter' method
                if(method_exists($controller,'doAfter')){
                    
                    // Call the 'doAfter' method along with parameters if available.
                    Object::callMethod($controller,'doAfter',$uri->params);
                    
                }

                // Check whether Hooks are enabled.
                if(GLBL_ENABLE_HOOKS==true){

                    // Run hooks registered to pre-end.
                    Hooks::run('dpx_pre_end');
                }
            
            }else{
            
                //Throw error 404
                new Error('',404,'DPX.DynaPortX.__construct: Controller \''.$uri->controller.'\' (class) does not exist.');
            }
            
        }else{
            
            //Throw error 404
            new Error('',404,'DPX.DynaPortX.__construct: Controller \''.$uri->controller.'\' ('.$path.') does not exist.');
        }
        
    }

}

?>