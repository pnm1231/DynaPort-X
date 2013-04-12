<?php

/**
 * DynaPort X
 *
 * A simple yet powerful PHP framework for rapid application development.
 *
 * Licensed under BSD license
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    DynaPort X
 * @copyright  Copyright (c) 2012-2013 DynamicCodes.com (http://www.dynamiccodes.com/dynaportx)
 * @license    http://www.dynamiccodes.com/dynaportx/license   BSD License
 * @version    2.0.0
 * @link       http://www.dynamiccodes.com/dynaportx
 * @since      File available since Release 0.2.0
 */

/**
 * Error Class
 *
 * The error class which throws errors.
 *
 * @package     DynaPort X
 * @subpackage  Core
 * @category    Core
 * @author      Prasad Nayanajith
 * @link        http://www.dynamiccodes.com/dynaportx/doc/core/error
 */
class Error {

    /**
     * Display an error
     * 
     * @param string $message Public error message
     * @param integer $code Error code (404/500)
     * @param string $realMessage Real error message
     */
    public function __construct($message=null,$code=0,$realMessage=null){
        if($code==404){
            $header = 'Not Found';
            $message = 'Page Not Found';
        }else if($code==500 OR $message==false){
            $header = '500 Internal Server Error';
        }
        if(!headers_sent() && isset($header)){
            header('HTTP/1.1 '.$header);
        }
        $this->show($message,$realMessage);
    }
    
    /**
     * Print the error message
     * 
     * @param string $message Public error message
     * @param string $realMessage Real error message
     */
    private function show($message,$realMessage){
        echo $message.'<br><br>';
        //echo 'More info: '.Dencrypt::encrypt($realMessage);
        echo 'More info: '.$realMessage;
        exit;
    }

}

?>