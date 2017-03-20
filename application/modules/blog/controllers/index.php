<?php

class BlogIndexController extends Controller {
    
    /**
     * Auto-load models
     * 
     * @var array Array of models
     */
    public $models = array('common/session','blog/users');
    
    /**
     * Render the header first.
     */
    function doBefore(){
        $this->view->data['page_title'] = 'Blog';
        
        $this->view->render('common/header');
    }

    function public_index(){

        // Get DB data through model [Method 1]
        // Since the 'users' model is loaded automatically thanks to: public $models at the top,
        // It is possible to call it as: $this->model->users, where 'users' is the model name.
        $users = $this->model->users->get();

        $session = $this->model->session->get();

        // Get DB data through model [Method 2]
        // Posts model is loaded manually using the following line.
        $this->load->model('blog/posts');
        // As with the above method, it's possible to call it as: $this->model->posts, where 'posts' is the model name.
        $posts = $this->model->posts->get();

        // Assign returned data to the view.
        // The view can retrieve the followings by using $this->session, $this->users and $this->posts
        $this->view->data['session'] = $session;
        $this->view->data['users'] = $users;
        $this->view->data['posts'] = $posts;

        // Render the view
        $this->view->render('blog/posts');
    }

    /**
     * Render the footer at the end.
     */
    function doAfter(){
        $this->view->render('common/footer');
    }

}

?>