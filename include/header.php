<?php
include("include/config.php");
include("include/src/Database/database.php");
$db = new Database($GLOBAL['database']);
$priviledge =  getUserPriviledge($db);
?>
<!doctype html>  
<html lang='sv'>
<head>
    <meta name="viewport" content="width=device-width initial-scale=1", charset="UTF-8">
	<title> <?php echo isset($pageTitle) ? $GLOBAL['pageTitle']. $pageTitle : $GLOBAL['pageTitle']; ?></title>

	<script src="//code.jquery.com/jquery.min.js"></script>

	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="Style/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="Style/style.css">
</head>

<body>
<header <?php if(isset($pageId)) echo "id='$pageId' ";?>>

	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<main>
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">
					<img  id="bthLogo" src="img/bthLogo.png" alt="BTH logo">
				</a>
			</div>


			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a id="index-" href="index.php">Startsida</a></li>
					<li><a id="news-" href='news.php'>Nyheter</a></li>
					<li><a id="guestbook-" href='guestbook.php'>Gästbok</a></li>
					<li><a id="calender-" href='calender.php'>Kalender</a></li>
					<?php echo $priviledge == 2 ? "<li><a href='admin.php'>Admin</a>" : "";?>
				</ul>

				<form id="signin" class="navbar-form navbar-right" role="form">
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input id="email" type="email" class="form-control" name="email" value="" placeholder="Användarnamn">
					</div>

					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<input id="password" type="password" class="form-control" name="password" value="" placeholder="Lösenord">
					</div>

					<button type="submit" class="btn btn-primary">Login</button>
				</form>

			</div>
			</main>
		</div>
	</nav>


	<div id="top">
		<!--
		<nav id='nav_header'>
			<a class='menu_a' href='index.php'>Startsida</a>
			<a class='menu_a' href='news.php'>Nyheter</a>
			<a class='menu_a' href='guestbook.php'>Gästbook</a>
			<a class='menu_a' href='calendar.php'>Kalender</a>
			<?php echo $priviledge == 2 ? "<a class='menu_a' href='admin.php'> Admin </a>" : ""; ?>
		</nav>
		-->
		<span id='login'>
			<?php echo showLoginLogout($db);?>
		</span>
		<!--
	</div>
	<div id="banner">
		<img  id="bthLogo" src="img/bthLogo.png" alt="BTH logo">
		<h1 id="title">OL-Academy</h1>
		<h3 id="subtitle">Blekinge Tekniska Högskola</h3>
		-->
	</div>

</header>
<main>
