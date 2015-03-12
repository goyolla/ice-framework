<?php
require dirname(__FILE__).'/vendor/autoload.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

if(!defined('TAGS_TO_PRESERVE')){define('TAGS_TO_PRESERVE','');}
$jsVersion = '41.8';
$cssVersion = '2.8';


include (APP_BASE_PATH."utils/SessionUtils.php");
include (APP_BASE_PATH."utils/InputCleaner.php");
include (APP_BASE_PATH."utils/LogManager.php");


$_REQUEST = InputCleaner::cleanParameters($_REQUEST);
$_GET = InputCleaner::cleanParameters($_GET);
$_POST = InputCleaner::cleanParameters($_POST);



//Find timezone diff with GMT
$dateTimeZoneColombo = new DateTimeZone("Asia/Colombo");
$dateTimeColombo = new DateTime("now", $dateTimeZoneColombo);
$dateTimeColomboStr = $dateTimeColombo->format("Y-m-d H:i:s");
$dateTimeNow = date("Y-m-d H:i:s");

$diffHoursBetweenServerTimezoneWithGMT = (strtotime($dateTimeNow) - (strtotime($dateTimeColomboStr) - 5.5*60*60))/(60*60);

if (!function_exists('fixJSON')) {
	function fixJSON($json){
		global $noJSONRequests;
		if($noJSONRequests."" == "1"){
			$json = str_replace("|",'"',$json);
		}
		return $json;
	}
}