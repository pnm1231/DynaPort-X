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

class Error extends Error_Controller {

    function __construct($msg='',$errorNo=0,$msgExplained='') {
        parent::__construct($msg,$errorNo,$msgExplained);
    }

}

?>