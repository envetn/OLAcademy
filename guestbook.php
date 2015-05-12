<?php
include("include/header.php");
$limit  = 15; //Posts per page in guestbook
$offset = isset($_GET['offset']) ? $_GET['offset'] : 0; //Start index

if (isset($_POST['submit']))
{
	makePost($db, $_POST['name'], $_POST['text']);
}

?>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST"> 
	<label>Name:<br><input type="text" name="name" size="30"/></label><br>
	<label>Message:<br><textarea name="text" rows="8" cols="40"></textarea></label><br>
	<label><input type="submit" name="submit" value="Submit"/></label><br>
</form>

<?php presentPost($db, $offset, $limit); ?>

<?php include("include/footer.php"); ?>
