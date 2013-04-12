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
 * Hooks Class
 *
 * The hooks class which enables extending the core without editing core files,
 * but placing hooks at certain points of the framework.
 *
 * @package     DynaPort X
 * @subpackage  Core
 * @category    Core
 * @author      Prasad Nayanajith
 * @link        http://www.dynamiccodes.com/dynaportx/doc/core/hooks
 */
class Hooks {
    
    /**
     * Store registered hooks
     * 
     * @var array List of hooks 
     */
    protected static $hooks = array();
    
    /**
     * Add a hook
     * 
     * @param string $point At which point this hook should get called
     * @param string $path Path of the class file, relative to application folder
     * @param string $file File name
     * @param string $class Class name
     * @param string $method Specifig method (optional)
     * @param array $params Parameters to pass (optional)
     */
    public static function add($point,$path,$file,$class,$method='',$params=array()){
        if($point && $class){
            self::$hooks[$point][] = array($path,$file,$class,$method,$params);
        }
    }
    
    /**
     * Get registered hooks
     * 
     * @param string $point The point name
     * @return array Registered hooks
     */
    public static function get($point){
        if(!empty(self::$hooks) && isset($hooks[$point])){
            return self::$hooks[$point];
        }
    }
    
    /**
     * Run a hook
     * 
     * @param string $point Point name
     */
    public static function run($point){
        // Check if the application has defined hooks.
        if(!file_exists(GLBL_FOLDERS_APPLICATION.'/config/hooks.php')){
            return false;
        }
        
        // Include hook definitions.
        require_once GLBL_FOLDERS_APPLICATION.'/config/hooks.php';
        
        // If there are no hooks defined, return false
        if(empty(self::$hooks) || empty(self::$hooks[$point])){
            return false;
        }
        
        // Get hooks registered to the given point.
        $hooks = self::$hooks[$point];

        // Check if there are hooks registered for this point.
        if($hooks && count($hooks)>0){

            // Go through hooks.
            foreach($hooks AS $hook){
                
                $hook_file = GLBL_FOLDERS_APPLICATION.'/'.rtrim($hook[0],'/').'/'.$hook[1].'.php';
                
                if(file_exists($hook_file)){
                    
                    require_once $hook_file;

                    // Check whether the hooked class is available.
                    if(class_exists($hook[2])){
                        
                        // If the method is not set but parameters, consider it as Class parameters.
                        if(empty($hook[3]) && !empty($hook[4])){
                            
                            // Create the hooked object with parameters.
                            $hook_class = new $hook[2]($hook[4]);
                            
                            // Remove parameters.
                            $hook[4] = '';
                            
                        }else{
                            
                            // Create the hooked object.
                            $hook_class = new $hook[2];
                        }

                        // Check if a method is also defined along with its existence.
                        if(!empty($hook[3]) && method_exists($hook_class,$hook[3])){
                            
                            /*
                            if(isset($hook[4]) && !empty($hook[4])){
                                
                                $hookArgs = $hook[4];
                                
                                // If the given argument is not an array, make it one.
                                if(!is_array($hook[4])){
                                    $hookArgs = array($hook[4]);
                                }
                                
                                // Call the method with given arguments.
                                call_user_func_array(array($hook_class,$hook[3]),$hookArgs);
                                
                            }else{

                                // Call the method.
                                $hook_class->{$hook[3]}();
                                
                            }
                            */
                            
                            // Call the relavant method with parameters if available.
                            Object::callMethod($hook_class,$hook[3],$hook[4]);
                        }
                    }
                }
            }
        }
    }
    
}

?>