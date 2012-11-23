<?php

class Index_Model extends Model {

    function __construct(){
        parent::__construct();
    }
    
    function sampleGet(){

	/*        
        return $this->db->select('*','users',array(
            'id' => 1,
            'username' => 'p%'
        ),array(
            'id','desc'
        ),
        array(
            0,30
        ));
	*/

	/**
	* Above MySQL query will return the following array (example)
	*/
	return array(
			array(
				'id'		=> 1,
				'username'	=> 'pnm123',
				'password'	=> 'something'
			),
			array(
				'id'		=> 2,
				'username'	=> 'prasad',
				'password'	=> 'somethingAgain'
			)
		);
        
    }

}

?>