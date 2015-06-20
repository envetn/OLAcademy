<?php include("include/header.php"); ?>

<article id="start_news"><h1>Nyheter</h1></article>

<article id="start_calendar"><h1>Kalender</h1></article>

<article id="start_gb"><h1>Gästbok</h1><?php echo presentPost($db, 0, 3); ?></article>

<aside id="start_registration">
	<h1>Anmälan</h1>
	<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
	<input type="hidden" name="id" value="<?php if (isset($_SESSION['uid'])) echo $_SESSION['uid']; ?>">
	<select>
		<option value="1">Test1</option>
	</select>
	<input type="submit" name="submit" value="Anmäl">
	</form>
</aside>


<?php include("include/footer.php");?>