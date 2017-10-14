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
class DPxError {

    /**
     * Display an error
     * 
     * @param string $message Public error message
     * @param integer $code Error code (404/500)
     * @param string $real_message Real error message
     */
    public function __construct($message=null,$code=0,$real_message=null){
        if($code==400){
            $header = '400 Bad Request';
            $message = 'Bad Request';
        }else if($code==404){
            $header = '404 Not Found';
            $message = 'Page Not Found';
        }else if($code==500 OR $message==false){
            $header = '500 Internal Server Error';
        }
        if(!headers_sent() && isset($header)){
            header('HTTP/1.1 '.$header);
        }

        if($code!=404){
            Log::error($real_message);
        }

        $this->show_modern($code,$message,$real_message);

        exit;
    }
    
    /**
     * Print the error message
     * 
     * @param string $message Public error message
     * @param string $real_message Real error message
     */
    private function show($message,$real_message){
        echo $message.'<br><br>';
        echo 'More info: '.$real_message;
    }

    /**
     * @param string $code Error code
     * @param string $message Readable message
     * @param string $real_message Real error message
     */
    private function show_modern($code,$message,$real_message){
        $file = 'application/modules/common/views/error.php';

        if($_SERVER['ENVIRONMENT']=='production'){
            $real_message = Dencrypt::encrypt($real_message);
        }

        if(file_exists($file)){
            require_once $file;
        }else{
            $this->show($message,$real_message);
        }
    }

}

?>