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

class Controller {

    function __construct() {
        $this->view = new View();
    }
    
    /**
     * Load a model
     * 
     * @param type $name Name of the model to be loaded
     */
    public function loadModel($name){
        $model_name = preg_replace('/_Controller$/i','_Model',$name);
        $model = __autoload($model_name);
        if($model==true){
            $this->model = new $model_name();
        }
    }

}

?>