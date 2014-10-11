<?php
/*
 * This file is part of Ice Framework.
 * Copyright Thilina Hasantha (thilina.hasantha[at]gmail.com | http://facebook.com/thilinah | https://twitter.com/thilina84)
 * Licensed under MIT (https://github.com/thilinah/ice-framework/master/LICENSE)
 */

abstract class EmailSender{
	var $settings = null;
	public function __construct($settings){
		$this->settings	= $settings;	
	}
	
	public function sendEmail($subject, $toEmail, $template, $params){
		
		$body = $template;	

		foreach($params as $k=>$v){
			$body = str_replace("#_".$k."_#", $v, $body);	
		}
		$fromEmail = APP_NAME." <".$this->settings->getSetting("Email: Email From").">";
		
		
		//Convert to an html email
		$emailBody = file_get_contents(APP_BASE_PATH.'/templates/email/emailBody.html');
		
		$emailBody = str_replace("#_emailBody_#", $body, $emailBody);
		
		$user = new User();
		$user->load("username = ?",array('admin'));
		
		$emailBody = str_replace("#_adminEmail_#", $user->email, $emailBody);
		$emailBody = str_replace("#_url_#", CLIENT_BASE_URL, $emailBody);
		$emailBody = str_replace("#_logourl_#", BASE_URL."images/logo.png", $emailBody);
		
		$this->sendMail($subject, $emailBody, $toEmail, $fromEmail);
	}	
	
	protected  abstract function sendMail($subject, $body, $toEmail, $fromEmail);
	
	public function sendResetPasswordEmail($emailOrUserId){
		$user = new User();
		$user->Load("email = ?",array($emailOrUserId));	
		if(empty($user->id)){
			$user = new User();
			$user->Load("username = ?",array($emailOrUserId));
			if(empty($user->id)){
				return false;
			}
		}
		
		$params = array();
		//$params['user'] = $user->first_name." ".$user->last_name;
		$params['url'] = CLIENT_BASE_URL;
		
		$newPassHash = array();
		$newPassHash["CLIENT_NAME"] = CLIENT_NAME;
		$newPassHash["oldpass"] = $user->password;
		$newPassHash["email"] = $user->email;
		$newPassHash["time"] = time();
		$json = json_encode($newPassHash);
		
		$encJson = AesCtr::encrypt($json, $user->password, 256);
		$encJson = urlencode($user->id."-".$encJson);
		$params['passurl'] = CLIENT_BASE_URL."service.php?a=rsp&key=".$encJson;
		
		$emailBody = file_get_contents(APP_BASE_PATH.'/templates/email/passwordReset.html');
		
		$this->sendEmail("[".APP_NAME."] Password Change Request", $user->email, $emailBody, $params);
		return true;
	}
}


class SNSEmailSender extends EmailSender{
	var $ses = null;
	public function __construct($settings){
		parent::__construct($settings);
		$arr = array(
				'key'    => $this->settings->getSetting('Email: Amazon SNS Key'),
				'secret' => $this->settings->getSetting('Email: Amazone SNS Secret')
		);
		$this->ses = new AmazonSES($arr);
	}
	
	protected  function sendMail($subject, $body, $toEmail, $fromEmail) {
		
		error_log("Sending email to: ".$toEmail."/ from: ".$fromEmail);

        $toArray = array('ToAddresses' => array($toEmail),
        				'CcAddresses' => null,
        				'BccAddresses' => null);
        $message = array( 
	        'Subject' => array(
	            'Data' => $subject,
	            'Charset' => 'iso-8859-1'
	        ),
	        'Body' => array(
	            'Html' => array(
	                'Data' => $body,
	                'Charset' => 'iso-8859-1'
	            )
	        )
    	);
    	
    	$response = $this->ses->send_email($fromEmail, $toArray, $message);
    	
    	return $response->isOK();
    	
    }
}


class SMTPEmailSender extends EmailSender{
	
	public function __construct($settings){
		parent::__construct($settings);
	}
	
	protected  function sendMail($subject, $body, $toEmail, $fromEmail) {

		error_log("Sending email to: ".$toEmail."/ from: ".$fromEmail);
		
		$host = $this->settings->getSetting("Email: SMTP Host");
		$username = $this->settings->getSetting("Email: SMTP User");
		$password = $this->settings->getSetting("Email: SMTP Password");
		$port = $this->settings->getSetting("Email: SMTP Port");
		
		if(empty($port)){
			$port = '25';
		}
		
		if($this->settings->getSetting("Email: SMTP Authentication Required") == "0"){
			$auth = array ('host' => $host,
     		'auth' => false);	
		}else{
			$auth = array ('host' => $host,
     		'auth' => true,
     		'username' => $username,
			'port' => $port,		
     		'password' => $password);	
		}
		
		
		$smtp = Mail::factory('smtp',$auth);

		$headers = array ('MIME-Version' => '1.0',
  		'Content-type' => 'text/html',
  		'charset' => 'iso-8859-1',
  		'From' => $fromEmail,
  		'To' => $toEmail,
   		'Subject' => $subject);
		
		
		$mail = $smtp->send($toEmail, $headers, $body);
		
		
		return true;
    }
}