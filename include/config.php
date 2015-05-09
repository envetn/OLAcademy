<?php
/*GLOBAL VARIABLES*/

$GLOBAL['database']['dsn']            = 'mysql:host=localhost;dbname=olacademy;'; 
$GLOBAL['database']['username']		= 'root'; // still using default settings
if(linux_server())
{
    $GLOBAL['database']['password']		= 'hallonsaft';
}
$GLOBAL['database']['password']		= '';
$GLOBAL['database']['driver_option']	= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");

$GLOBAL['pageTitle'] = "Ol Academy";


/*check if the operativsystem is linux*/
function linux_server()
{
    return in_array(strtolower(PHP_OS), array("linux", "superior operating system"));
}

?>