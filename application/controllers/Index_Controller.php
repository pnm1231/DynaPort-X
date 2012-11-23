<?php

class Index_Controller extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    function doBefore(){
        //echo 'I am doing before<br />';
    }
    
    function doAfter(){
        //echo 'I am doing after<br />';
    }
    
    function public_index(){
        $this->view->sampleDbGet = $this->model->sampleGet();
        $this->view->render('index/index');
    }

}

?>