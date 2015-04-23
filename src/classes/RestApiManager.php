<?php
class RestApiManager{
	
	private static $me = NULL;
	
	private function __construct(){
		if(empty(self::$me)){
			self::$me = new RestApiManager();	
		}
		
		return self::$me;
	}
	
	public function generateUserAccessToken($username, $password){
		
		$user = new User();
		$user->Load("username = ? and password = ?",array($username, md5($password)));
		
		if(empty($user->id)){
			$user = new User();
			$user->Load("email = ? and password = ?",array($username, md5($password)));
		}
		
		$data = array();
		$data['userId'] = $user->id;
		$data['expires'] = strtotime('now') + 60*60;
		
		$accessTokenTemp = AesCtr::encrypt(json_encode($data), $user->password, 256);
		$accessTokenTemp = $user->id."|".$accessTokenTemp;
		$accessToken = AesCtr::encrypt($accessTokenTemp, APP_SEC, 256);
		
		return new IceResponse(IceResponse::SUCCESS, $accessToken);
	}
	
	public function validateAccessToken($accessToken){
		$accessTokenTemp = AesCtr::decrypt($accessToken, APP_SEC, 256);
		$parts = explode("|", $accessTokenTemp);
		
		if(!empty($parts[0])){
			$user = new User();
			$user->Load("id = ?",array($parts[0]));
			if(empty($user->id)){
				return new IceResponse(IceResponse::ERROR, -1);
			}
		}
		
		$accessToken = AesCtr::encrypt($accessTokenTemp, APP_SEC, 256);
	}
}