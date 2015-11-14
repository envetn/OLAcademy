<?php
$pageId ="guestbook";
$pageTitle ="- Gästbok";
include("include/header.php");
$limit  = 7; //Posts per page
$offset = isset($_GET['offset']) && is_numeric($_GET['offset']) ? $_GET['offset'] : 0; //Start index

if (isset($_POST['submit']))
{
	makePost($db, $_POST['name'], $_POST['text']);
}

$postForm = 
	'<div class="col-sm-4 elementBox">
		<form action='.$_SERVER['PHP_SELF'].' method="POST">
			<label>Namn:<br><input type="text" name="name" size="30"/></label><br>
			<label>Text:<br><textarea name="text" rows="8" cols="40"></textarea></label><br>
			<label><input type="submit" class="btn btn-primary" name="submit" value="Skicka"/></label><br>
		</form>
	</div>';


echo "<div class='row clearFix'>";
	echo $postForm;
	echo "<div class='col-sm-8 elementBox'>";
		echo presentPost($db, $offset, $limit);
		$nrOfRows = countAllRows($db, "posts");
		echo paging($limit, $offset, $nrOfRows, $numbers=5, "");
	echo "</div>";
echo "</div>";

include("include/footer.php"); ?>