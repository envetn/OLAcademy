<?php
include("include/config.php");
include("include/database.php");
$db = new Database($GLOBAL['database']);
?>
<!doctype html>  
<html lang='sv'>  
    <meta charset="UTF-8">
	<title> <?php echo isset($pageTitle) ? $GLOBAL['pageTitle']. $pageTitle : $GLOBAL['pageTitle']; ?></title>
	<link rel="stylesheet" type="text/css" href="style/style.css">
<header>

	<div id="top">
		<nav id='nav_header'>
			<a class='menu_a' href='index.php'>Startsida</a>
			<a class='menu_a' href='news.php'>Nyheter</a>
			<a class='menu_a' href='guestbook.php'>Gästbook</a>
			<a class='menu_a' href=''>Site4</a>
		</nav>
		<span id='login'>
			<?php include("login.php");?>
		</span>
	</div>
	<div id="banner">
		<img  id="bthLogo" src="img/bthLogo.png" alt="BTH logo">
		<h1 id="title">OL-Academy</h1>
		<h3 id="subtitle">Blekinge Tekniska Högskola</h2>
	</div>



</header>
<body>
<main>
