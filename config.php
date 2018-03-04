<?php
session_start();
define('dbhost', 'localhost');
define('dbuser', 'root');
define('dbpass', 'sincosten');
define('dbname', 'pasteit');

try {
	$connect = new PDO("mysql:host=".dbhost."; dbname=".dbname, dbuser, dbpass);
	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
	echo $e->getMessage();
}
?>
