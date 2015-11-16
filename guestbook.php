<?php
$pageId ="guestbook";
$pageTitle ="- Gästbok";
include("include/header.php");
$limit  = 7; //Posts per page
$offset = isset($_GET['offset']) && is_numeric($_GET['offset']) ? $_GET['offset'] : 0; //Start index
$guestbookObject = new GuestbookObject($db);

function makePost($guestbookObject)
{
	$name = strip_tags($_POST['name']);
	$text = strip_tags($_POST['text']);
	if(empty($name) || empty($text))
	{
		$_SESSION['error'] = "<pre class=red>Fyll i alla fält.</pre>";
	}
	else
	{
		$text = makeLinks($text);
		$max_text_length = 2000;
		$max_name_length = 50;
		if (strlen($text) > $max_text_length)
		{
			$_SESSION['error'] = "<pre class=red>Text must not exceed " . $max_text_length . " characters.</pre>";
		}
		elseif (strlen($name) > $max_name_length)
		{
			$_SESSION['error'] = "<pre class=red>Name must not exceed " . $max_name_length . " characters.</pre>";
		}
		else
		{
			$params = array($name, $text);
			$guestbookObject->addSingleEntry($params);
		}
	}
}


if (isset($_POST['submit']))
{
	makePost($guestbookObject);
}

$postForm = 
	'<div class="col-sm-4 elementBox">
		<h2>Gästbok</h2>
		<form action='.$_SERVER['PHP_SELF'].' method="POST">
			<label>Namn:<br><input type="text" name="name" size="30"/></label><br>
			<label>Text:<br><textarea name="text" rows="8" cols="40"></textarea></label><br>
			<label><input type="submit" class="btn btn-primary" name="submit" value="Skicka"/></label><br>
		</form>
	</div>';


echo isset($_SESSION['error']) ? $_SESSION['error'] : "";
echo "<div class='row'>";
	echo $postForm;
	echo "<div class='col-sm-8 elementBox'>";
		echo presentPost($guestbookObject, $offset, $limit);
		$nrOfRows = $guestbookObject->countAllRows();
		echo paging($limit, $offset, $nrOfRows, $numbers=5, "");
	echo "</div>";
echo "</div>";

include("include/footer.php"); ?>
