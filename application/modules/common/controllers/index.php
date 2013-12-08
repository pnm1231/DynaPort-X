<?php

class CommonIndexController extends Controller {
    
    /**
     * Auto-load models
     * 
     * @var array Array of models
     */
    public $models = array('common/session');

    function __construct(){
        parent::__construct();
    }
    
    /**
     * Render the header first.
     */
    function doBefore(){
        $this->view->render('common/header');
    }
    
    function public_index(){
        
        // Get DB data through model [Method 1]
        // Since the 'users' model is loaded automatically thanks to: public $models at the top,
        // It is possible to call it as: $this->model->users, where 'users' is the model name.
        $session = $this->model->session->get();
        
        // Get DB data through model [Method 2]
        // Refer application/modules/blog/controllers/index.php for the 2nd method
        
        // Assign returned data to the view.
        // The view can retrieve the following by using $this->session
        $this->view->session = $session;
        
        // Render the view
        // Unlike when rendering the header, module name is NOT specified here.
        // Since v2.0.38, the framework supports rendering same-module views
        // without repeating the module name. Therefore this is acceptable.
        $this->view->render('index');
    }
    
    /**
     * Render the footer at the end.
     */
    function doAfter(){
        $this->view->render('common/footer');
    }

}

?>