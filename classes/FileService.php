<?php
/*
This file is part of Ice Framework.

Ice Framework is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Ice Framework is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Ice Framework. If not, see <http://www.gnu.org/licenses/>.

------------------------------------------------------------------

Original work Copyright (c) 2012 [Gamonoid Media Pvt. Ltd]  
Developer: Thilina Hasantha (thilina.hasantha[at]gmail.com / facebook.com/thilinah)
 */

class FileService{
	public function updateProfileImage($profile){
		$file = new File();
		$file->Load('name = ?',array('profile_image_'.$profile->id));
		
		if($file->name == 'profile_image_'.$profile->id){
			$profile->image = CLIENT_BASE_URL.'data/'.$file->filename;
		}else{
			if($profile->gender == 'Female'){
				$profile->image = BASE_URL."images/user_female.png";			
			}else{
				$profile->image = BASE_URL."images/user_male.png";	
			}
		}

		return $profile;
	}
	
	public function deleteProfileImage($profileId){
		$file = new File();
		$file->Load('name = ?',array('profile_image_'.$profileId));
		if($file->name == 'profile_image_'.$profileId){
			$ok = $file->Delete();	
			if($ok){
				error_log("Delete File:".CLIENT_BASE_PATH.$file->filename);
				unlink(CLIENT_BASE_PATH.'data/'.$file->filename);		
			}else{
				return false;
			}	
		}	
		return true;
	}
	
	public function deleteFileByField($value, $field){
		$file = new File();
		$file->Load("$field = ?",array($value));
		if($file->$field == $value){
			$ok = $file->Delete();
			if($ok){
				error_log("Delete:".CLIENT_BASE_PATH.'data/'.$file->filename);
				unlink(CLIENT_BASE_PATH.'data/'.$file->filename);
			}else{
				return false;
			}
		}
		return true;
	}
}