<?php

class Cache {

    public $cacheData;
    private $_controller;
    private $_uniqueId = array();
    private $_folder;

    function __construct($name=null){
        $regisrty_controller = (!empty($name))?Registry::get('dpx_controller'):'';
        if(!empty($name)){
            $name = preg_replace('/(![a-z0-9_-]*)/i','',$name);
            $name = strtolower($name);
            $this->_controller = $name;
        }else if(!empty($regisrty_controller)){
            $this->_controller = $regisrty_controller;
        }else{
            new Error_Controller(   'Unable to cache the page',
                                    500,
                                    'DPX.Lib.Cache.__construct: Controller name is required when creating a cache object.');
        }
        $this->_folder = 'application/cache/'.$this->_controller;
    }
    
    function appendId($str){
        if(!empty($str)){
            if(is_array($str)){
                $this->_uniqueId = $str;
            }else{
                $this->_uniqueId[] = $str;
            }
        }
        return $this;
    }
    
    private function getId(){
        if(count($this->_uniqueId)>0){
            $id = implode('_',$this->_uniqueId);
            return Hash::create($id);
        }
    }
    
    private function buildFilename(){
        $id = $this->getId();
        if(!$id){
            $id = 'default';
        }
        return $file = $this->_folder.'/'.$id.'.tmp';
    }
    
    function checkAvail(){
        $file = $this->buildFilename();
        if(file_exists($file)){
            return true;
        }else{
            return false;
        }
    }
    
    function save($print=false){
        if($this->cacheData){
            if(!is_dir($this->_folder)){
                mkdir($this->_folder,0777,true);
            }
            $file = $this->buildFilename();
            file_put_contents($file,$this->cacheData,LOCK_EX);
            if($print==true){
                echo $this->cacheData;
            }
        }else{
            new Error_Controller(   'Unable to cache the page',
                                    500,
                                    'DPX.Lib.Cache.save: Data was not provided to create the cache file.');
            exit;
        }
    }
    
    function serve(){
        if($this->checkAvail()){
            require $this->buildFilename();
        }else{
            $this->save(true);
        }
    }

}

?>