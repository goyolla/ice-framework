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

class FileService{
	
	private static $me = null;
	
	private function __construct(){
	
	}
	
	public static function getInstance(){
		if(empty(self::$me)){
			self::$me = new FileService();
		}
	
		return self::$me;
	}
	
	public function updateProfileImage($profile){
		$file = new File();
		$file->Load('name = ?',array('profile_image_'.$profile->id));
		
		if($file->name == 'profile_image_'.$profile->id){
			$uploadFilesToS3 = SettingsManager::getInstance()->getSetting("Files: Upload Files to S3");	
			if($uploadFilesToS3 == "1"){
				$uploadFilesToS3Key = SettingsManager::getInstance()->getSetting("Files: Amazon S3 Key for File Upload");
				$uploadFilesToS3Secret = SettingsManager::getInstance()->getSetting("Files: Amazone S3 Secret for File Upload");
				$s3FileSys = new S3FileSystem($uploadFilesToS3Key, $uploadFilesToS3Secret);
				$s3WebUrl = SettingsManager::getInstance()->getSetting("Files: S3 Web Url");
				$fileUrl = $s3WebUrl.CLIENT_NAME."/".$file->filename;
				$fileUrl = $s3FileSys->generateExpiringURL($fileUrl);
				$profile->image = $fileUrl;
			}else{
				$profile->image = CLIENT_BASE_URL.'data/'.$file->filename;
			}
			
		}else{
			if($profile->gender == 'Female'){
				$profile->image = BASE_URL."images/user_female.png";			
			}else{
				$profile->image = BASE_URL."images/user_male.png";	
			}
		}

		return $profile;
	}
	
	public function getFileUrl($fileName){
		$file = new File();
		$file->Load('name = ?',array($fileName));
	
		$uploadFilesToS3 = SettingsManager::getInstance()->getSetting("Files: Upload Files to S3");
		
		if($uploadFilesToS3 == "1"){
			$uploadFilesToS3Key = SettingsManager::getInstance()->getSetting("Files: Amazon S3 Key for File Upload");
			$uploadFilesToS3Secret = SettingsManager::getInstance()->getSetting("Files: Amazone S3 Secret for File Upload");
			$s3FileSys = new S3FileSystem($uploadFilesToS3Key, $uploadFilesToS3Secret);
			$s3WebUrl = SettingsManager::getInstance()->getSetting("Files: S3 Web Url");
			$fileUrl = $s3WebUrl.CLIENT_NAME."/".$file->filename;
			$fileUrl = $s3FileSys->generateExpiringURL($fileUrl);
			return $fileUrl;
		}else{
			return  CLIENT_BASE_URL.'data/'.$file->filename;
		}
	}
	
	public function deleteProfileImage($profileId){
		$file = new File();
		$file->Load('name = ?',array('profile_image_'.$profileId));
		if($file->name == 'profile_image_'.$profileId){
			$ok = $file->Delete();	
			if($ok){
				LogManager::getInstance()->info("Delete File:".CLIENT_BASE_PATH.$file->filename);
				unlink(CLIENT_BASE_PATH.'data/'.$file->filename);		
			}else{
				return false;
			}	
		}	
		return true;
	}
	
	public function deleteFileByField($value, $field){
		LogManager::getInstance()->info("Delete file by field: $field / value: $value");
		$file = new File();
		$file->Load("$field = ?",array($value));
		if($file->$field == $value){
			$ok = $file->Delete();
			if($ok){			
				$uploadFilesToS3 = SettingsManager::getInstance()->getSetting("Files: Upload Files to S3");
				
				if($uploadFilesToS3 == "1"){
					$uploadFilesToS3Key = SettingsManager::getInstance()->getSetting("Files: Amazon S3 Key for File Upload");
					$uploadFilesToS3Secret = SettingsManager::getInstance()->getSetting("Files: Amazone S3 Secret for File Upload");
					$s3Bucket = SettingsManager::getInstance()->getSetting("Files: S3 Bucket");
					
					$uploadname = CLIENT_NAME."/".$file->filename;
					LogManager::getInstance()->info("Delete from S3:".$uploadname);
					
					$s3FileSys = new S3FileSystem($uploadFilesToS3Key, $uploadFilesToS3Secret);
					$res = $s3FileSys->deleteObject($s3Bucket, $uploadname);
						
				}else{
					LogManager::getInstance()->info("Delete:".CLIENT_BASE_PATH.'data/'.$file->filename);
					unlink(CLIENT_BASE_PATH.'data/'.$file->filename);
				}
				
				
			}else{
				return false;
			}
		}
		return true;
	}
}