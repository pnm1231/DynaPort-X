<?php

class Blog_Controller extends Controller {

    function __construct(){
        parent::__construct();
    }
    
    function public_index(){
        echo 'This is blog';
    }
    
    function public_posts(){
        echo 'List all blog posts';
    }

    function public_post($id){
        echo 'Show post '.$id;
    }

}

?>