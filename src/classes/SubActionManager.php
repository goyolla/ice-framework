<?php
/*

Copyright [2015] [Thilina Hasantha (thilina.hasantha[at]gmail.com)]

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

 */

class IceResponse{
	
	const SUCCESS = "SUCCESS";
	const ERROR = "ERROR";
	
	var $status;
	var $data;

	public function __construct($status,$data = null){
		$this->status = $status;	
		$this->data = $data;	
	}
	
	public function getStatus(){
		return $this->status;
	}
	
	public function getData(){
		return $this->data;
	}
	
	public function getObject(){
		return $this->data;
	}
	
	public function getJsonArray(){
		return array("status"=>$this->status,"data"=>$this->data);
	}
}

abstract class SubActionManager{
	var $user = null;
	protected $baseService = null;
	var $emailTemplates = null;
	var $emailSender = null;
	
	public function setUser($user){
		$this->user = $user;	
	}
	
	public function setBaseService($baseService){
		$this->baseService = $baseService;	
	}
	
	public function getCurrentProfileId(){
		return $this->baseService->getCurrentProfileId();
	}
	
	public function setEmailTemplates($emailTemplates){

		$this->emailTemplates	= $emailTemplates;
		
	}
	
	public function getEmailTemplate($name){
		//Read module email templates
		if($this->emailTemplates == null){
			$this->emailTemplates = array();
			if(is_dir(MODULE_PATH.'/emailTemplates/')){
				$ams = scandir(MODULE_PATH.'/emailTemplates/');
				foreach($ams as $am){
					if(!is_dir(MODULE_PATH.'/emailTemplates/'.$am) && $am != '.' && $am != '..'){
						$this->emailTemplates[$am] = file_get_contents(MODULE_PATH.'/emailTemplates/'.$am);	
					}	
				}
			}
		}
		
		return 	$this->emailTemplates[$name];
	}
	
	public function setEmailSender($emailSender){
		$this->emailSender = $emailSender;
	}
	
	public function getUserFromProfileId($profileId){
		return $this->baseService->getUserFromProfileId($profileId);
	}
	
	
}