<?php

class Uri {

    public $controller;
    public $method;
    public $params = array();

    function __construct(){
        
        /**
         * Check if the URI class have already called and did it task
         */
        $registry_controller = Registry::get('dpx_uri_controller');
        if(!empty($registry_controller)){
            /**
             * Define the controller from the registry
             */
            $this->controller = $registry_controller;
            
            /**
             * Define the method from the registry
             */
            $registry_method = Registry::get('dpx_uri_method');
            if(!empty($registry_method)){
                $this->method = $registry_method;
            }
            
            /**
             * Define parametes from the registry
             */
            $registry_params = Registry::get('dpx_uri_params');
            if(!empty($registry_params)){
                $this->params = $registry_params;
            }
        }else{
            /**
             * Check if the URI is provided as a GET variable
             * If not, get the current full URL and guess the URI
             */
            if(isset($_GET['uri']) && !empty($_GET['uri'])){
                $uri = $_GET['uri'];
            }else{
                $url = GLBL_URL;
                $cur_url = $this->currentUrl();
                $uri = preg_replace('@'.$url.'/@','',$cur_url);
                if(preg_match('/^http\:/',$uri)){
                    new Error_Controller('GLBL_URL seems to have a wrong URL');
                }
            }
            
            /**
             * Modify the URI further
             */
            $uri = rtrim($uri,'/');
            $uri = filter_var($uri,FILTER_SANITIZE_URL);
            $uri = preg_replace('/\?(.*)$/','',$uri);
            
            /**
             * Check if there are routing methods declared
             * If so, route the current URI
             * The URI will be modified if there is a match
             */
            if(class_exists('Router',false)){
                $uri = Router::route($uri);
            }
            
            /**
             * Break the URI into sections
             */
            $uri = @explode('/',$uri);
            
            /**
             * If the URI array is not empty, get the controller, method and params out of it
             */
            if(!empty($uri) && is_array($uri)){

                /**
                 * Define the controller and save it in the registry for future use
                 */
                if(isset($uri[0]) && !empty($uri[0])){
                    $this->controller = $uri[0];
                    Registry::set('dpx_uri_controller',$uri[0]);
                    unset($uri[0]);
                }

                /**
                 * Define the method and save it in the registry for future use
                 */
                if(isset($uri[1]) && !empty($uri[1])){
                    $this->method = $uri[1];
                    Registry::set('dpx_uri_method',$uri[1]);
                    unset($uri[1]);
                }

                /**
                 * Define parameters and save them in the registry for future use
                 * This would be a string if only 1 param, and an array if there are more params
                 */
                if(count($uri)>0){
                    if(count($uri)>1){
                        foreach($uri AS $v){
                            $this->params[] = $v;
                        }
                    }else{
                        foreach($uri AS $v){
                            $this->params = $v;
                        }
                    }
                    Registry::set('dpx_uri_params',$this->params);
                }
            }
        }
    }
    
    /**
     * Generate the current URL
     * 
     * @return string URL
     */
    function currentUrl() {
        $url = 'http';
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on'){
            $url.= 's';
        }
        $url.= '://';
        $url.= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        return $url;
    }
    
    /**
     *
     * @param string $change_k The KEY of the array
     * @param string $change_v The value you need to add
     * @return string Imploded, manipulated string
     */
    static function editUrlParams($change_k=null,$change_v=null){
        $url_queries = parse_url($_SERVER['REQUEST_URI'],PHP_URL_QUERY);
	$url_queries = preg_replace('/^&/','',$url_queries);
	parse_str($url_queries,$what);
        
        if(count($what)>0){
            if(array_key_exists($change_k,$what)){
                $first = true;
                $output = '';
                foreach($what as $key => $value) {
                    if($change_k==$key){
                        $value = $change_v;
                    }
                    if($first){
                        $output = '?'.$key.'='.$value;
                        $first = false;
                    }else{
                        $output .= '&'.$key.'='.$value;   
                    }
                }
            }else{
                $output = '?'.$url_queries.'&'.$change_k.'='.$change_v;
            }
        }else{
            $output = '?'.$change_k.'='.$change_v;
        }
	return $output;
    }
    
    static function breakParams($params=null){
        if(empty($params) && !is_array($params)){
            $uri = new Uri();
            $params = $uri->params;
        }
        
        if(is_array($params)){
            foreach($params AS $param){
                list($k,$v) = explode('-',$param,2);
                $get[$k] = $v;
            }
        }else{
            list($k,$v) = explode('-',$params,2);
            $get[$k] = $v;
        }
        
        return $get;
    }
    
    static function editParams($change_k=null,$change_v=null){
        $uri = new Uri();
        $get = $uri->breakParams($uri->params);
        
        $return[] = GLBL_URL;
        $return[] = $uri->controller;
        $return[] = $uri->method;
        
        foreach($get AS $k=>$v){
            if($k==$change_k){
                $v = $change_v;
            }
            $return[] = $k.'-'.$v;
        }
        
        if(!array_key_exists($change_k,$get)){
            $return[] = $change_k.'-'.$change_v;
        }
        
        $return = array_filter($return);
        $return = implode('/',$return);
        return $return;
    }

}

?>