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

class ProfilesActionManager extends SubActionManager{
	public function get($req){
		
		$profile = $this->baseService->getElement('Profile',$this->getCurrentProfileId(),$req->map,true);	
		
		$subordinate = new Profile();
		$subordinates = $subordinate->Find("supervisor = ?",array($profile->id));
		$profile->subordinates = $subordinates;
		
		$fs = FileService::getInstance();
		$profile = $fs->updateProfileImage($profile);
		
		if(!empty($profile->birthday)){
			$profile->birthday = date("F jS, Y",strtotime($profile->birthday));
		}
		
		if(empty($profile->id)){
			return new IceResponse(IceResponse::ERROR,$profile);		
		}
		return new IceResponse(IceResponse::SUCCESS,$profile);
	}
	
	public function deleteProfileImage($req){
		if($this->user->user_level == 'Admin' || $this->user->profile == $req->id){
			$fs = FileService::getInstance();
			$res = $fs->deleteProfileImage($req->id);
			return new IceResponse(IceResponse::SUCCESS,$res);	
		}
	}
}