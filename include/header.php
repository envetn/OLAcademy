<?php
define('INCLUDE_PATH', __DIR__ . '');
include_once(INCLUDE_PATH . "/config.php");

include_once(INCLUDE_PATH . "/src/Objects/EventObject.php");
include_once(INCLUDE_PATH . "/src/Objects/NewsObject.php");
include_once(INCLUDE_PATH . "/src/Objects/GuestbookObject.php");
include_once(INCLUDE_PATH . "/src/Objects/User/User.php");

$user = new User();
$privilege =  $user->getUserprivilege();

?>
<!doctype html>  
<html lang='sv'>
<head>
    <meta name="viewport" content="width=device-width initial-scale=1", charset="UTF-8">
	<title> <?php echo isset($pageTitle) ? $GLOBAL['pageTitle']. $pageTitle : $GLOBAL['pageTitle']; ?></title>

	<!--<script src="//code.jquery.com/jquery.min.js"></script>-->
	<script src="jquery/jquery.min.js"></script>
	<script src="Style/bootstrap/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="Style/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="Style/style.css">
</head>

<body>
<header <?php if(isset($pageId)) echo "id='$pageId'";?>>
	<nav class="navbar navbar-default navbarCustom">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">
					<img  id="bthLogo" src="img/bthLogo.png" alt="BTH logo">
				</a>
			</div>


			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a id="index-" href="index.php">Startsida</a></li>
					<li><a id="news-" href='news.php'>Nyheter</a></li>
					<li><a id="guestbook-" href='guestbook.php'>Gästbok</a></li>
					<li><a id="calendar-" href='calendar.php'>Kalender</a></li>
					<?php echo $user->getUserPrivilege() === "2" ? "<li><a id='admin-' href='admin.php'>Admin</a>" : "";?>
				</ul>
                    <?php echo showLoginLogout($user, $GLOBAL['salt_char']);?>
			</div>
	</nav>
</header>
<main>
