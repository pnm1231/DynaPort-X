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

class Mail {
    
    private $_mail;
    private $_name;
    private $_subj;
    private $_set;
    private $_calledMsg;
    public $errorInfo;

    function __construct(){
        require_once 'system/libs/Mail/class.phpmailer.php';
        
        $this->_mail = new PHPMailer();
    }
    
    function set($k,$v){
        if(!empty($k) && !empty($v)){
            $this->_set[$k] = $v;
        }
        return $this;
    }
    
    function to($email,$name){
        $this->_mail->AddAddress($email,$name);
        $this->_name = $name;
        
        return $this;
    }
    
    function subject($subj){
        $this->_mail->Subject = $subj;
        $this->_subj = $subj;
        return $this;
    }
    
    function template($tpl){
        $this->set('template',$tpl);
        return $this;
    }
    
    function msg($msg=null){
        $this->_calledMsg = true;
        
        $file = (!empty($this->_set['template']))?$this->_set['template']:'default';
        $file = 'application/views/'.$file.'.html';
        
        if(!file_exists($file)){
            new Error_Controller('Unable to send the email',500,'The email template ('.$file.') does not exists');
        }
        
        $body = file_get_contents($file);
        $body = stripslashes($body);
        
        $pat = array(
            '/{%subj%}/',
            '/{%name%}/',
            '/{%msg%}/'
        );
        $rep = array(
            $this->_subj,
            $this->_name,
            $msg
        );
        if(is_array($this->_set) && count($this->_set)>0){
            foreach($this->_set AS $k=>$v){
                $pat[] = '{%'.$k.'%}';
                $rep[] = $v;
            }
        }
        $body = str_replace($pat,$rep,$body);
        
        $this->_mail->MsgHTML($body);
        return $this;
    }
    
    function send(){
        if($this->_calledMsg!=true){
            $this->msg();
        }
        
        $this->_set = '';
        
        if(MAIL_METHOD=='smtp'){
            $this->_mail->IsSMTP();
            $this->_mail->SMTPDebug  = 1;               // enables SMTP debug information (for testing)
                                                        // 1 = errors and messages
                                                        // 2 = messages only
            $this->_mail->SMTPAuth   = true;
            $this->_mail->SMTPSecure = 'tls';           // sets the prefix to the servier
        }
        $this->_mail->Host       = MAIL_HOST;
        $this->_mail->Port       = MAIL_PORT;
        $this->_mail->Username   = MAIL_USER;
        $this->_mail->Password   = MAIL_PASS;

        if(MAIL_REPLY_NAME && MAIL_REPLY_EMAIL){
            $this->_mail->SetFrom(MAIL_FROM_EMAIL,MAIL_FROM_NAME,0);
            $this->_mail->AddReplyTo(MAIL_REPLY_EMAIL,MAIL_REPLY_NAME);
        }else{
            $this->_mail->SetFrom(MAIL_FROM_EMAIL,MAIL_FROM_NAME);
        }

        $this->_mail->AltBody    = 'To view the message, please use an HTML compatible email viewer!'; // optional, comment out and test

        if($this->_mail->Send()){
            return true;
        }else{
            new Error_Controller('Unable to send the email',500,$this->_mail->ErrorInfo);
        }
    }

}

?>