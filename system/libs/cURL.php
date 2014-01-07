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
 * @link       https://github.com/pnm1231/DynaPort-X/wiki
 * @since      File available since Release 0.2.0
 */

/**
 * cURL Class
 *
 * The cURL class which handles all the cURL requests.
 *
 * @package     DynaPort X
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Prasad Nayanajith
 * @link        https://github.com/pnm1231/DynaPort-X/wiki/cURL-library
 */
class cURL {
    
    private $ch;
    
    public $error;

    function __construct(){
        // Check if cURL is installed
        if(!function_exists('curl_init')){
            new Error('Sorry cURL is not installed!');
        }
        
        // Create a new cURL resource handle
        $this->ch = curl_init();
        $this->setReturnType();
        $this->set(CURLOPT_FAILONERROR,true);
    }
    
    /**
     * Set the URL
     * 
     * @param string $url URL to access
     */
    function setUrl($url){
        $url = filter_var($url,FILTER_SANITIZE_URL);
        if(!empty($url)){
            curl_setopt($this->ch,CURLOPT_URL,$url);
        }else{
            new Error('Please provide a valid URL.');
        }
    }
    
    /**
     * Set the HTTP method to POST
     * 
     * @param string $body POST fields
     */
    function setHttpPost($body){
        curl_setopt($this->ch,CURLOPT_POST,true);
        curl_setopt($this->ch,CURLOPT_POSTFIELDS,$body);
    }
    
    /**
     * Set the HTTP method to PUT
     * 
     * @param string $body PUT fields
     */
    function setHttpPut($body){
        curl_setopt($this->ch,CURLOPT_CUSTOMREQUEST,'PUT');
        curl_setopt($this->ch,CURLOPT_POSTFIELDS,$body);
    }
    
    /**
     * Set the HTTP method to DELETE
     * 
     * @param string $body DELETE fields
     */
    function setHttpDelete($body){
        curl_setopt($this->ch,CURLOPT_CUSTOMREQUEST,'DELETE');
        curl_setopt($this->ch,CURLOPT_POSTFIELDS,$body);
    }
    
    /**
     * Set the Referring URI
     * 
     * @param string $referer Referring URI
     */
    function setReferer($referer){
        curl_setopt($this->ch,CURLOPT_REFERER,$referer);
    }
    
    /**
     * Set a custom User Agent
     * 
     * @param string $userAgent User agent name
     */
    function setUserAgent($userAgent){
        curl_setopt($this->ch,CURLOPT_USERAGENT,$userAgent);
    }
    
    /**
     * Include header in the result?
     * 
     * @param boolean $header true/false
     */
    function setInclHeader($header=0){
        curl_setopt($this->ch,CURLOPT_HEADER,$header);
    }
    
    /**
     * Should cURL return or print out the data?
     * 
     * @param boolean $return true=return/false=print
     */
    function setReturnType($return=1){
        curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,$return);
    }
    
    /**
     * Timeout of the cURL request
     * 
     * @param int $timeout Timeout in seconds
     */
    function setTimeout($timeout){
        if(is_numeric($timeout)){
            curl_setopt($this->ch,CURLOPT_TIMEOUT,$timeout);
        }else{
            new Error('Timeout should be an integer.');
        }
    }
    
    /**
     * Should the SSL certificates be Verified?
     *
     * @param boolean $sslverify true/false
     */
    function setSslVerify($sslverify=1){
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER,$sslverify);
    }
    
    /**
     * Set a cURL option
     * 
     * @param string option
     * @param string value
     */
    function set($key,$value){
        curl_setopt($this->ch,$key,$value);
    }
    
    /**
     * Output (default: return) the result
     * 
     * @return mixed The result
     */
    function output(){
        // Download the given URL, and return output
        $output = curl_exec($this->ch);
        
        // Update the error variable if there is one
        $this->error = curl_error($this->ch);
        
        // Close the cURL resource, and free system resources
        curl_close($this->ch);
        
        return $output;
    }

}

?>