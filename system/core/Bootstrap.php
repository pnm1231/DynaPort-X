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

class Bootstrap {

    function __construct(){
        /**
         * Call the URI library
         * The URI library breaks the user request into:
         * 1. Controller
         * 2. Method
         * 3. Parameter(s)
         */
        $uri = new Uri();

        /**
         * Load the respective controller
         * If no controller was called, load the Index controller
         */
        if(!isset($uri->controller)){
            $uri->controller = 'Index_Controller';
        }else{
             $uri->controller.= '_Controller';
        }
        $controller = __autoload($uri->controller);
        
        /**
         * If the controller is loaded, continue
         * Otherwise, throw 404
         */
        if($controller==true){
            $controller = new $uri->controller();
            $controller->loadModel($uri->controller);
            
            /**
             * Run the doBefore method if available, before calling the original method
             */
            if(method_exists($controller,'doBefore')){
                $controller->doBefore();
            }
            
            /**
             * Check if a method a is called
             */
            if(isset($uri->method)){
                
                /**
                 * Add the prefix 'public_' to the method
                 */
                $uri->method = 'public_'.$uri->method;
                
                /**
                 * Check if the called method is available
                 * If available, call it. Otherwise, throw 404
                 */
                if(method_exists($controller,$uri->method)){
                    $controller->{$uri->method}($uri->params);
                }else{
                    $controller = new Error_Controller("DPX.Core.Bootstrap.__construct: The method {$uri->method} does not exists.",404);
                }
            }else{
                
                /**
                 * Check if the index method is available
                 * If available, call it. Otherwise, throw 404
                 */
                if(method_exists($controller,'public_index')){
                    $controller->public_index();
                }else{
                    $controller = new Error_Controller('The index method does not exists.',404);
                }
            }
            
            /**
             * Run the doAfter method if available, after calling the original method
             */
            if(method_exists($controller,'doAfter')){
                $controller->doAfter();
            }
        }else{
            new Error_Controller("DPX.Core.Bootstrap.__construct: The controller {$uri->controller} is not available.",404);
        }
    }

}

?>