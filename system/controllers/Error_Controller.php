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

class Error_Controller extends Controller {
    
    function __construct($msg=false,$errorNo=0,$msgExplained=''){
        parent::__construct();
        
        $this->view->error = $msg;
        $this->view->errorActual = $msgExplained;
        
        if($errorNo==404){
            $this->public_404();
        }else if($errorNo==500 OR $msg==false){
            $this->public_500();
        }else{
            $this->public_index();
        }
        exit;
    }
    
    function public_index(){
        $this->view->render('error/default');
    }
    
    function public_404(){
        if(!headers_sent()){
            header('HTTP/1.1 404 Not Found');
        }
        $this->view->render('error/404');
    }
    
    function public_500(){
        if(!headers_sent()){
            header('HTTP/1.1 500 Internal Server Error');
        }
        $this->view->render('error/500');
    }

}

?>