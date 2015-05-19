<?php
include("include/config.php");
include("include/database.php");
$db = new Database($GLOBAL['database']);
?>
<!doctype html>  
<html lang='sv'>  
<head>
    <meta charset="UTF-8">
	<title> <?php echo $GLOBAL['pageTitle']; ?></title>
	<link rel="stylesheet" type="text/css" href="style/style.css">
<div style="width: 100%; overflow: hidden;">
	<div id='div_header'>
        <nav id='nav_header'>
            <a class='menu_a' href='index.php'>Startsida</a>
            <a class='menu_a' href='news.php'>Nyheter</a>
            <a class='menu_a' href='guestbook.php'>Gässtbook</a>
            <a class='menu_a' href=''>Site4</a>
        </nav>
        <div id='div_login'>
            <?php include("login.php");?>
       </div>
    </div>
 </div>

</head>
<body>


