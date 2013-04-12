<?php

class CommonSessionModel extends Model {

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
                'id'    => 1564289843,
                'ip'    => '123.123.123.123',
                'active'=> 1
            )
        );
    }

}

?>