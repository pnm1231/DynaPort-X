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
 * URI Class
 *
 * The URI class which handles URI-related tasks.
 *
 * @package     DynaPort X
 * @subpackage  Core
 * @category    Core
 * @author      Prasad Nayanajith
 * @link        http://www.dynamiccodes.com/dynaportx/doc/core/uri
 */
class Uri {
    
    /**
     * Exploded URI
     * 
     * @var array
     */
    public $uriExpl;
    
    /**
     * Module name
     * @var string
     */
    public $module;
    
    /**
     * Controller name
     * 
     * @var string
     */
    public $controller;
    
    /**
     * Method name
     * 
     * @var string
     */
    public $method;
    
    /**
     * Parameters
     * 
     * @var array
     */
    public $params;

    function __construct(){
        
        // Get the current URL.
        $url = self::currentURL();
        
        // Parse the current URL.
        $urlParse = parse_url($url);
        
        // Parse the base URL.
        $glblParse = parse_url(GLBL_URL);
        
        // Get the URI.
        $uri = ltrim(str_replace($glblParse['path'],'',$urlParse['path']),'/');

        // Check whether Hooks are enabled.
        if(GLBL_ENABLE_HOOKS==true){
            
            // Run hooks registered to pre-route.
            Hooks::run('dpx_pre_route');
        }
        
        // Check if there are routing methods declared.
        // If so, route the current URI.
        // The URI will be modified if there is a match.
        if(class_exists('Router',false)){
            $uri = Router::route($uri);
        }
        
        // If the URI is not empty, break it into sections.
        if(!empty($uri)){
            
            // Break the URI into sections.
            $uriExpl = explode('/',$uri);
            $this->uriExpl = $uriExpl;
            
            // Design the normal URI scheme.
            $uriScheme = array('module','controller','method');
            
            // If the app is not modularized, remove 'module' from the flow.
            if(GLBL_MODULARIZED!=true){
                
                // Remove 'module' from the URI scheme.
                unset($uriScheme[0]);
                
                // Reset array keys of the URI scheme.
                $uriScheme = array_values($uriScheme);
            }
            
            // Go throug the URI scheme and register each section.
            foreach($uriScheme AS $k=>$v){
                
                // Assign the current value to the relavant section.
                Registry::set('dpx_'.$v,$uriExpl[$k]);
                
                // Assign the same to the relavant public variable.
                $this->{$v} = $uriExpl[$k];
                
                // Remove it from the array.
                unset($uriExpl[$k]);
            }
            
            // Reset array keys of URI sections and
            // assign it to the 'params' public variable.
            $this->params = array_values($uriExpl);
            
            // Store parameters in the registry as well.
            Registry::set('dpx_params',$this->params);
        }
        
    }
    
    /**
     * Get the current URL
     * 
     * @return string Current URL
     */
    public static function currentURL(){
        return 'http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on'?'s':'').'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    }

}

?>