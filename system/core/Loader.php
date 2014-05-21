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
     * Main controller instance
     * 
     * @var \Controller
     */
    private $controller;
    
    /**
     * Current component
     * 
     * @var string 
     */
    private $component;
    
    /**
     * Arguments
     * 
     * @var array
     */
    private $arguments;
    
    function __construct(&$controller){
        
        // Store the DynaPort X controller instance.
        $this->controller = $controller;
    }
    
    /**
     * Load a controller
     * 
     * @param string $name Controller name
     * @return bool
     */
    public function controller($name){
        
        // Mark the current loading component as a controller.
        $this->component = 'controller';
        
        // Remove previously stored arguments.
        $this->arguments = null;
        
        // Store arguments if provided.
        $arguments = func_get_args();
        if($arguments>1){
            unset($arguments[0]);
            $this->arguments = $arguments;
        }
        
        return $this->loadComponent($name);
    }
    
    /**
     * Load a model
     * 
     * @param string $name Model name
     * @return bool
     */
    public function model($name){
        
        // Mark the current loading component as a model.
        $this->component = 'model';
        
        // Remove previously stored arguments.
        $this->arguments = null;
        
        // Store arguments if provided.
        $arguments = func_get_args();
        if($arguments>1){
            unset($arguments[0]);
            $this->arguments = $arguments;
        }
        
        return $this->loadComponent($name);
    }
    
    /**
     * Load a library
     * 
     * @param string $name Library name
     * @return bool
     */
    public function library($name){
        
        // Mark the current loading component as a library.
        $this->component = 'library';
        
        // Remove previously stored arguments.
        $this->arguments = null;
        
        // Store arguments if provided.
        $arguments = func_get_args();
        if($arguments>1){
            unset($arguments[0]);
            $this->arguments = $arguments;
        }
        
        return $this->loadComponent($name);
    }
    
    /**
     * Load a component
     * 
     * @param string $name Component name
     * @return bool
     */
    private function loadComponent($name){
        
        $file = $this->pathnameToFile($this->component,$name);
        
        // Check if the component file is available.
        if(file_exists($file)){
            
            // Require the file once.
            require_once $file;
            
            // Explode the component name by the seperators.
            $nameExpl = explode('/',$name);
            
            // Initialize the component name.
            $componentName = end($nameExpl);
            
            $className = '';
            
            // Check if the component is a controller or a model.
            if($this->component=='controller' || $this->component=='model'){
                
                // If the app is modularized, add module name to the class name.
                if(GLBL_MODULARIZED==true){
                    $className = ucfirst($nameExpl[0]);
                }
                
            }
            
            // Append the component name to the class name.
            $className.= ucfirst($componentName);
            
            // Check if the component is a controller or a model.
            if($this->component=='controller' || $this->component=='model'){
                
                // Append the controller name to the class name.
                $className.= ucfirst($this->component);
                
            }
            
            // Check if any arguments are passed.
            if(count($this->arguments)==0){
                
                // Create the object without any arguments.
                try {
                    $object = new $className();
                }catch(Exception $e){
                    new Error('Unable to load a component.',500,'DPX.Loader.loadComponent: '.$e->getMessage());
                }
                
            }else{
                
                // Create the object by passing the arguments.
                try {
                    $reflect  = new ReflectionClass($className);
                    $object = $reflect->newInstanceArgs($this->arguments);
                }catch(Exception $e){
                    new Error('Unable to load a component.',500,'DPX.Loader.loadComponent: '.$e->getMessage());
                }
            }
            
            // Convert the component's variable into an object.
            if(!is_object($this->controller->{$this->component})){
                $this->controller->{$this->component} = new stdClass();
            }
            
            // Assign the component name as the variable name
            $variableName = $componentName;
            
            // Check if the same name is alredy being used
            // If so, prepend the module name so it does not replace
            if(isset($this->controller->{$this->component}->{$variableName})){
                $variableName = $nameExpl[0].ucfirst($componentName);
            }
            
            // Assign the component object to the variable name
            $this->controller->{$this->component}->{$variableName} = $object;
        
            return $object;
            
        }else{
            new Error('Unable to load a component.',500,'DPX.Loader.loadComponent: '.ucfirst($this->component).' \''.$name.'\' is not available.');
        }
    }
    
    /**
     * Convert a path-name to the file
     * 
     * @param string $component Component type
     * @param string $name Component name
     * @return string File and path
     */
    static function pathnameToFile($component,$name){
        
        // Sanitize the class path/name.
        $name = preg_replace('@([^A-z0-9\/-])@','',$name);
        
        // Start building the file path.
        $file = GLBL_FOLDERS_APPLICATION.'/';
        
        // Check if a library is requested.
        if($component=='library'){
            
            // Check if it's for a system library
            if(preg_match('@^system/@',$name)){
                
                // Put the system library folder as the file path.
                $file = 'system/libs/';
                
                // Remove the part 'system/' from the component name.
                $name = preg_replace('@^system/@','',$name);
                
            }else{
            
                // Append the application library folder to the file path.
                $file.= GLBL_FOLDERS_LIBRARY.'/';
                
            }
            
        // Check if the request is for a controller, model or a view.
        }else if(in_array($component,array('controller','model','view'))){
            
            // Check if the app is modularized.
            if(GLBL_MODULARIZED==true){

                // If modularized, look inside the modules folder.
                $file.= 'modules/';
            
                // Check if the module is not defined.
                if(!preg_match('@/@',$name)){
                    
                    // Prepend the current module to the component name.
                    $name = Registry::get('dpx_module').'/'.$name;
                }

                // Inject the component type to the first separator.
                $name = preg_replace('@/@','/'.$component.'s/',$name,1);

            }else{
            
                // Prepend the component type to the name.
                $name = $component.'s/'.$name;
            
            }
            
        }else{
            
            // Invalid component type is requested.
            new Error('Invalid component type is being loaded.',500,'DPX.Loader.load: Requested component type \''.$component.'\' is not valid.');
            
        }
        
        // Append the component name and the extension to the file path.
        $file.= $name.'.php';
        
        return $file;
    }

}

?>