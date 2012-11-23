<?php

class Index_Model extends Model {

    function __construct(){
        parent::__construct();
    }
    
    function sampleGet(){
        
        return $this->db->select('*','users',array(
            'id' => 1,
            'username' => 'p%'
        ),array(
            'id','desc'
        ),
        array(
            0,30
        ));
        
    }

}

?>