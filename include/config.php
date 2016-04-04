<?php
session_start();
ob_start();
/*GLOBAL VARIABLES*/
require_once INCLUDE_PATH .'/src/php-markdown-lib/Michelf/Markdown.php';
require_once(INCLUDE_PATH . "/functions.php");
set_error_handler('exceptions_error_handler');

$GLOBAL['database']['dsn']              = 'mysql:host=localhost;dbname=olacademy;'; 
$GLOBAL['database']['username']	    	= 'olacademy';
$GLOBAL['database']['password']		    = 'hallonsaft';
$GLOBAL['database']['driver_option']	= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");
$GLOBAL['pageTitle']				    = "OL-Academy";

$_SESSION['error'] = "";
$_SESSION['success'] = "";

?>