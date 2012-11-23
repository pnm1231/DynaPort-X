<?php

/*
 * This file is part of the DynaPort X package.
 *
 * (c) Prasad Nayanajith <prasad.n@dynamiccodes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

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