<?php

class Log {
    
    /**
     * Logging levels from syslog protocol defined in RFC 5424
     * 
     * @var array $levels Logging levels
     */
    protected static $levels = array(
        100 => 'DEBUG',
        200 => 'INFO',
        250 => 'NOTICE',
        300 => 'WARNING',
        400 => 'ERROR',
        500 => 'CRITICAL',
        550 => 'ALERT',
        600 => 'EMERGENCY',
    );
    
    /**
     * Log a debug message
     * 
     * @param string $message Message to log
     * @param array $stack Stack of the exception
     * @return boolean
     */
    static function debug($message,$stack=null){
        return self::write(100,$message,$stack);
    }
    
    /**
     * Log an info
     * 
     * @param string $message Message to log
     * @param array $stack Stack of the exception
     * @return boolean
     */
    static function info($message,$stack=null){
        return self::write(200,$message,$stack);
    }
    
    /**
     * Log a notice
     * 
     * @param string $message Message to log
     * @param array $stack Stack of the exception
     * @return boolean
     */
    static function notice($message,$stack=null){
        return self::write(250,$message,$stack);
    }
    
    /**
     * Log a warning
     * 
     * @param string $message Message to log
     * @param array $stack Stack of the exception
     * @return boolean
     */
    static function warning($message,$stack=null){
        return self::write(300,$message,$stack);
    }
    
    /**
     * Log an error
     * 
     * @param string $message Message to log
     * @param array $stack Stack of the exception
     * @return boolean
     */
    static function error($message,$stack=null){
        return self::write(400,$message,$stack);
    }
    
    /**
     * Log a critical error
     * 
     * @param string $message Message to log
     * @param array $stack Stack of the exception
     * @return boolean
     */
    static function critical($message,$stack=null){
        return self::write(500,$message,$stack);
    }
    
    /**
     * Log an alert
     * 
     * @param string $message Message to log
     * @param array $stack Stack of the exception
     * @return boolean
     */
    static function alert($message,$stack=null){
        return self::write(550,$message,$stack);
    }
    
    /**
     * Log an emergency
     * 
     * @param string $message Message to log
     * @param array $stack Stack of the exception
     * @return boolean
     */
    static function emergency($message,$stack=null){
        return self::write(600,$message,$stack);
    }
    
    /**
     * Write to log
     * 
     * @param integer $level Log level
     * @param string $message Message
     * @param array $stack
     * @return boolean
     */
    static function write($level,$message,$stack=null){
        if(is_int($level)){
            if(isset(self::$levels[$level])){
                $levelName = self::$levels[$level];
            }else{
                new Error('Internal error occurred while logging! (#1)',500,'DPX.Libs.Log.write: Invalid login level defined #1: '.$level);
            }
        }else{
            $levelName = $level;
            if(!array_search(strtoupper($level),self::$levels)){
                new Error('Internal error occurred while logging! (#2)',500,'DPX.Libs.Log.write: Invalid login level defined #2: '.$level);
            }
        }
        
        if(empty($message)){
            new Error('Internal error occurred while logging! (#3)',500,'DPX.Libs.Log.write: No message was given');
        }
        
        $folder = 'application/logs/';
        $fileName = 'log_'.$level.'_'.date('Y-m-d').'_';
        $fileIncrement = str_pad(1,4,0,STR_PAD_LEFT);
        $fileType = '.log';
        
        if(!is_dir($folder)){
            mkdir($folder,0755,true);
        }
        
        do {
            $file = $folder.$fileName.$fileIncrement.$fileType;
            $fileIncrement = str_pad($fileIncrement+1,4,0,STR_PAD_LEFT);
        }while(file_exists($file) && filesize($file)>=102400);
        
        $fp = fopen($file,'a+');
        
        $logContent = "--------------------------------------------------\n";
        $logContent.= "timestamp\t: ".time()."\n";
        $logContent.= "date/time\t: ".date('Y-m-d h:i:s A')."\n";
        $logContent.= "module\t\t: ".Registry::get('dpx_module')."\n";
        $logContent.= "controller\t: ".Registry::get('dpx_controller')."\n";
        $logContent.= "message\t\t:\n{$message}\n";
        $logContent.= "stack\t\t:\n".json_encode($stack)."\n";
        $logContent.= "--------------------------------------------------\n";
        
        fwrite($fp,$logContent);
        fclose($fp);
        
        return true;
    }

}

?>