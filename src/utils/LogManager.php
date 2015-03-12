<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

if(!class_exists('LogManager')){
	class LogManager{
	
		private static $me;
	
		private $log;
	
		private function __construct(){
	
		}
	
		public static function getInstance(){
			if(empty(self::$me)){
				self::$me = new LogManager();
				self::$me->log = new Logger(APP_NANE);
				$log->pushHandler(new StreamHandler(ini_get('error_log'), LOG_LEVEL));
			}
	
			return self::$me;
		}
	
		public function info($message){
			$log->addInfo($message);
		}
	
		public function debug($message){
			$log->addDebug($message);
		}
	
		public function error($message){
			$log->addError($message);
		}
	}	
}
