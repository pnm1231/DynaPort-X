<?php

class BlogCommentsModel extends Model {

    function __construct(){
        parent::__construct();
    }
    
    function get(){
        /*
         * Sample DB query
        return $this->db->select()->
                from('posts')->
                where('user',10)->
                where_or()->
                where('ip','123.123.123.123')->
                fetch();
        */
        
        // For now let's return a static array
        return array(
            array(
                'id'    => 97,
                'name'  => 'First comment...'
            ),
            array(
                'id'    => 98,
                'name'  => 'Nice post. Well done!'
            )
        );
    }

}

?>