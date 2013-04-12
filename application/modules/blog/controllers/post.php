<?php

class BlogPostController extends Controller {
    
    /**
     * Auto-load models
     * 
     * @var array Array of models
     */
    public $models = array(
        'common/session',
        'blog/users',
        'blog/posts',
        'blog/comments'
    );

    function __construct(){
        parent::__construct();
    }
    
    /**
     * Render the header first.
     */
    function doBefore(){
        $this->view->title = 'Blog Post';
        $this->view->render('common/header');
    }
    
    function public_index($id){
        
        $users = $this->model->users->get();
        $session = $this->model->session->get();
        $posts = $this->model->posts->get();
        $comments = $this->model->comments->get();
        
        $this->view->id = $id;
        $this->view->session = $session;
        $this->view->users = $users;
        $this->view->posts = $posts;
        $this->view->comments = $comments;
        
        // Render the view
        $this->view->render('blog/single_post');
    }
    
    /**
     * Render the footer at the end.
     */
    function doAfter(){
        $this->view->render('common/footer');
    }

}

?>