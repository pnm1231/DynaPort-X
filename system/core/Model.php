<?php

class Model {

    function __construct() {
        /**
         * Check if the database name and the user is defined
         * If defined, call the database
         */
        if(DB_NAME && DB_USER){
            $this->db = new Database(DB_TYPE,DB_HOST,DB_NAME,DB_USER,DB_PASS);
        }
    }

}

?>