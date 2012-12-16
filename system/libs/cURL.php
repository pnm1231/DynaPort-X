<?php

class cURL {
    
    private $ch;

    function __construct(){
        /*
         * Check if cURL is installed
         */
        if(!function_exists('curl_init')){
            new Error_Controller('Sorry cURL is not installed!');
        }
        
        /*
         * Create a new cURL resource handle
         */
        $this->ch = curl_init();
        $this->setReturnType();
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
            new Error_Controller('Please provide a valid URL.');
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
        curl_setopt($this->ch,CURLOPT_USERAGENT,$$userAgent);
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
            new Error_Controller('Timeout should be an integer.');
        }
    }
    
    /**
     * Output (default: return) the result
     * 
     * @return mixed The result
     */
    function output(){
        /*
         * Download the given URL, and return output
         */
        $output = curl_exec($this->ch);
        
        /*
         * Close the cURL resource, and free system resources
         */
        curl_close($this->ch);
        
        return $output;
    }

}

?>