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
 * View Class
 *
 * The loader class which helps load classes into the application.
 *
 * @package     DynaPort X
 * @subpackage  Core
 * @category    Core
 * @author      Prasad Nayanajith
 * @link        http://www.dynamiccodes.com/dynaportx/doc/core/loader
 */
class View {
    
    /**
     * Whether the \View is loaded once.
     * 
     * @var bool
     */
    private $_loadedOnce = false;
    
    /**
     * Render a view
     * 
     * @param string $file File (view) to render.
     */
    public function render($file,$noerror=0){
        
        $file = Loader::pathnameToFile('view',$file);
        
        // Check whether Hooks are enabled.
        if(GLBL_ENABLE_HOOKS==true && !$this->_loadedOnce){
            
            $this->_loadedOnce = true;
            
            // Run hooks registered to pre-view.
            Hooks::run('dpx_pre_view');
        }
        
        // Check the availability of the view.
        // If available, print it. Otherwise, throw a 500 error.
        if(file_exists($file)){
        
            include $file;
            
        }else{
            
            if($noerror==0){
                new Error('Something unavailable was called.',500,'DPX.View.render: \''.$file.'\' is not available.');
            }
        }
    }

}

?>