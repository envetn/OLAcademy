<?php
$pageId ="guestbook";
$pageTitle ="- Gästbok";
include("include/header.php");
$limit  = 15; //Posts per page
$offset = isset($_GET['offset']) && is_numeric($_GET['offset']) ? $_GET['offset'] : 0; //Start index

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

<?php 
echo presentPost($db, $offset, $limit);
$nrOfRows = countAllRows($db, "posts");
echo paging($limit, $offset, $nrOfRows, $numbers=5);
?>

<?php include("include/footer.php"); ?>
