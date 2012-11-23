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

class View {
    
    private $_header = true;
    private $_footer = true;
    
    /**
     * Make the header or footer enable/disable
     * 
     * @param string $arg Header or Footer
     * @param boolean $bool True or false
     * @return \View
     */
    function set($arg,$bool){
        if(($arg=='header' || $arg=='footer') && is_bool($bool)){
            $arg = '_'.$arg;
            $this->$arg = $bool;
        }
        return $this;
    }
    
    /**
     * Render the full page
     * 
     * @param string $name The file to render
     */
    function render($name){
        if($this->_header==true){
            $this->renderHeader();
        }
        
        $this->renderThis($name);
        
        if($this->_footer==true){
            $this->renderFooter();
        }
    }
    
    /**
     * Render the header
     * 
     * @return \View
     */
    function renderHeader(){
        if(file_exists('application/views/header.php')){
            require 'application/views/header.php';
        }else if(file_exists('system/views/header.php')){
            require 'system/views/header.php';
        }
        return $this;
    }
    
    /**
     * Render the footer
     * 
     * @return \View
     */
    function renderFooter(){
        if(file_exists('application/views/footer.php')){
            require 'application/views/footer.php';
        }else if(file_exists('system/views/footer.php')){
            require 'system/views/footer.php';
        }
        return $this;
    }
    
    /**
     * Render a specific file
     * 
     * @param string $name The file to render
     * @return \View
     */
    function renderThis($name){
        if(file_exists('application/views/'.$name.'.php')){
            require 'application/views/'.$name.'.php';
        }else if(file_exists('system/views/'.$name.'.php')){
            require 'system/views/'.$name.'.php';
        }else{
            new Error('The view does not exists.');
        }
        return $this;
    }
    
    /**
     * Cache a page
     * 
     * @param string $name The file to cache and render
     */
    function cache($name){
        $cache = new Cache($name);
        ob_start();
        $this->render($name);
        $cache->cacheData = ob_get_clean();
        $cache->serve(true);
    }

}