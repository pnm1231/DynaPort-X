<?php

class BlogUsersModel extends Model {

    function __construct(){
        parent::__construct();
    }
    
    function get(){
        /*
         * Sample DB query
        return $this->db->select()->
                from('users')->
                where('user',10)->
                where_or()->
                where('ip','123.123.123.123')->
                fetch();
        */
        
        // For now let's return a static array
        return array(
            array(
                'id'    => 1,
                'name'  => 'Prasad'
            ),
            array(
                'id'    => 2,
                'name'  => 'Nayanajith'
            )
        );
    }

}

?>