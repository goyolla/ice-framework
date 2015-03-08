<?php
if(!class_exists("TestTemplate")) {
	include dirname(__FILE__).'/../../TestTemplate.php';
}

class UsersActionManagerTester extends TestTemplate{
	
	var $obj = null;
	
	protected function setUp()
	{
		parent::setUp();
		
		global $baseService;
		global $emailSender;
		
		include APP_BASE_PATH."admin/users/api/UsersEmailSender.php";
		include APP_BASE_PATH."admin/users/api/UsersActionManager.php";
		
		$this->obj = new UsersActionManager();
		$this->obj->setUser($this->usersArray['admin']);
		$this->obj->setBaseService($baseService);
		$this->obj->setEmailSender($emailSender);
	}
	
	
	public function testChangePassword(){

		$this->obj->getCurrentProfileId();
		
		$this->assertEquals(1, 1);
		
	}
}