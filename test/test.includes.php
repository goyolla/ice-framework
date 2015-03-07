<?php
include('/usr/lib/php5/mysql.auth.php');
include(dirname(__FILE__).'/test.config.php');

include ("include.common.php");

include("server.includes.inc.php");



$dropDBCommand = "mysqladmin -u".MYSQL_ROT_USER." -p".MYSQL_ROT_PASS." drop ".APP_DB;
$createDBCommand = "mysqladmin -u".MYSQL_ROT_USER." -p".MYSQL_ROT_PASS." create ".APP_DB;

exec($dropDBCommand);
exec($createDBCommand);

//Run create table script
$insql = file_get_contents(APP_BASE_PATH."scripts/icef_db.sql");
$sql_list = preg_split('/;/',$insql);
foreach($sql_list as $sql){
	if (preg_match('/^\s+$/', $sql) || $sql == '') { # skip empty lines
		continue;
	}
	$db->Execute($sql);
}

//Run create table script
$insql = file_get_contents(APP_BASE_PATH."scripts/icef_master_data.sql");
$sql_list = preg_split('/;/',$insql);
foreach($sql_list as $sql){
	if (preg_match('/^\s+$/', $sql) || $sql == '') { # skip empty lines
		continue;
	}
	$db->Execute($sql);
}

