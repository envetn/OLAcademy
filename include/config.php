<?php
session_start();
/*GLOBAL VARIABLES*/
include("functions.php");
set_error_handler('exceptions_error_handler');

$GLOBAL['database']['dsn']              = 'mysql:host=localhost;dbname=olacademy;'; 
$GLOBAL['database']['username']	    	= 'root';
$GLOBAL['database']['password']		    = '';
$GLOBAL['database']['driver_option']	= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");
$GLOBAL['pageTitle'] = "OL-Academy";
$GLOBAL['salt_char'] = "#@$";
$GLOBAL['error'] = "";

?>
