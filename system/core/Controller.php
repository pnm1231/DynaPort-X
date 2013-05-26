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
 * Controller Class
 *
 * The main Controller class of the framework.
 *
 * @package     DynaPort X
 * @subpackage  Core
 * @category    Core
 * @author      Prasad Nayanajith
 * @link        http://www.dynamiccodes.com/dynaportx/doc/core/controller
 */
class Controller {
    
    /**
     * Auto-load models
     * 
     * @var array Array of models
     */
    public $models = array();
    
    /**
     * Loaded models
     * 
     * @var \class Model object(s)
     */
    public $model;
    
    /**
     * Loaded controllers
     * 
     * @var \class Controller object(s)
     */
    public $controller;
    
    /**
     * View class
     * 
     * @var \View
     */
    public $view;
    
    /**
     * Stored variabled
     * 
     * @var array Variables
     */
    private static $vars = array();

    function __construct(){
        
        // Load models that are requested to be pre-loaded.
        if($this->models && is_array($this->models) && count($this->models)>0){
            foreach($this->models AS $model){
                $this->load('model',$model);
            }
        }
        
        // Assign the View class.
        $this->view = new View();
    }
    
    /**
     * Load a component
     * 
     * @param string $type Component type (model,controller)
     * @param string $component Component path/name
     */
    function load($type,$component){
        
        // Validate the component type.
        if($type=='model' || $type=='controller'){
            
            // Check whether the app is modularized.
            if(GLBL_MODULARIZED==true){
            
                // Break down the component path/name
                list($componentLoc,$componentName) = explode('/',$component);
            
            }else{
                
                // Get the component as as the name.
                $componentName = $component;
                
            }
            
            // If the component is already loaded, do not continue.
            if(isset($this->{$type}->{$componentName})){
                return false;
            }
            
            // Convert the component's variable into an object.
            if(!is_object($this->{$type})){
                $this->{$type} = new stdClass();
            }
            
            // Load the model and assign it to the relavant variable.
            $this->{$type}->{$componentName} = Loader::load($type,$component);// $this->load->{$type}($component);
            
        }else{
            
            new Error('DPX.Controller.Load: Invalid component type ('.$type.')',500);
        }
    }
    
    /**
     * Set a variable
     * 
     * These variables are accessible through any controller that is extending
     * the parent (this) controller.
     * 
     * @param string $k Key
     * @param mixed $v Value
     */
    function setVar($k,$v){
        self::$vars[$k] = $v;
    }
    
    /**
     * Return a variable
     * 
     * @param string $k Key
     * @return mixed Value
     */
    function getVar($k){
        if(isset(self::$vars[$k])){
            return self::$vars[$k];
        }else{
            return false;
        }
    }

}

?>