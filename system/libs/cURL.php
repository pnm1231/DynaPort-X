<?php

class cURL {
    
    private $ch;

    function __construct(){
        // is cURL installed yet?
        if(!function_exists('curl_init')){
            new Error_Controller('Sorry cURL is not installed!');
        }
        
        // OK cool - then let's create a new cURL resource handle
        $this->ch = curl_init();
        $this->setReturnType();
    }
    
    function setUrl($url){
        // Set URL to download
        $url = filter_var($url,FILTER_SANITIZE_URL);
        if(!empty($url)){
            curl_setopt($this->ch,CURLOPT_URL,$url);
        }else{
            new Error_Controller('Please provide a valid URL.');
        }
    }
    
    function setReferer($referer){
        // Set a referer
        curl_setopt($this->ch,CURLOPT_REFERER,$referer);
    }
    
    function setUserAgent($userAgent){
        // User agent
        curl_setopt($this->ch,CURLOPT_USERAGENT,$$userAgent);
    }
    
    function setInclHeader($header=0){
        // Include header in result? (0 = yes, 1 = no)
        curl_setopt($this->ch,CURLOPT_HEADER,$header);
    }
    
    function setReturnType($return=1){
        // Should cURL return or print out the data? (true = return, false = print)
        curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,$return);
    }
    
    function setTimeout($timeout){
        // Timeout in seconds
        if(is_numeric($timeout)){
            curl_setopt($this->ch,CURLOPT_TIMEOUT,$timeout);
        }else{
            new Error_Controller('Timeout should be an integer.');
        }
    }
    
    function output(){
        // Download the given URL, and return output
        $output = curl_exec($this->ch);
        
        // Close the cURL resource, and free system resources
        curl_close($this->ch);
        
        return $output;
    }

}

?>