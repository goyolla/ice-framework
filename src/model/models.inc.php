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


class File extends ICEHRM_Record {
	var $_table = 'Files';
	public function getAdminAccess(){
		return array("get","element","save","delete");
	}
	
	public function getUserAccess(){
		return array();
	}
	
	public function getAnonymousAccess(){
		return array("save");
	}
}



class Setting extends ICEHRM_Record {
	public function getAdminAccess(){
		return array("get","element","save","delete");
	}
	
	public function getUserAccess(){
		return array();
	}
	var $_table = 'Settings';
}

class Report extends ICEHRM_Record {
	public function getAdminAccess(){
		return array("get","element","save","delete");
	}
	
	public function getManagerAccess(){
		return array("get","element","save","delete");
	}
	
	public function getUserAccess(){
		return array();
	}
	var $_table = 'Reports';
}

class Audit extends ICEHRM_Record {
	var $_table = 'AuditLog';
}


class DataEntryBackup extends ICEHRM_Record {
	var $_table = 'DataEntryBackups';
}


class Notification extends ICEHRM_Record {
	var $_table = 'Notifications';
}

class RestAccessToken extends ICEHRM_Record {
	var $_table = 'RestAccessTokens';
}



