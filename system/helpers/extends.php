<?php

class Error extends Error_Controller {

    function __construct($msg='',$errorNo=0,$msgExplained='') {
        parent::__construct($msg,$errorNo,$msgExplained);
    }

}

?>