<?php

class Redirect {

    /**
     * Redirect to a specific URL
     * 
     * @param string $uri The URL or the Path
     */
    public static function go($uri){
        if(!preg_match('/^http\:/',$uri)){
            $uri = ltrim($uri,'/');
            $uri = GLBL_URL.'/'.$uri;
        }
        if(!empty($uri) && filter_var($uri,FILTER_SANITIZE_URL)){
            if(!headers_sent()){
                header('location: '.$uri);
                exit;
            }else{
                new Error_Controller('Unable to redirect',500,'DPX.Lib.Redirect.go: Cannot redirect since the header is already sent');
            }
        }else{
            new Error_Controller('Unable to redirect',500,'DPX.Lib.Redirect.go: The provided redirect URL is invalid');
        }
    }

}

?>