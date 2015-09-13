<?php
$pageId ="guestbook";
$pageTitle ="- G�stbok";
include("include/header.php");
$limit  = 15; //Posts per page
$offset = isset($_GET['offset']) && is_numeric($_GET['offset']) ? $_GET['offset'] : 0; //Start index

if (isset($_POST['submit']))
{
	makePost($db, $_POST['name'], $_POST['text']);
}

?>
<div class="col-sm-4 b">
	<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
		<label>Name:<br><input type="text" name="name" size="30"/></label><br>
		<label>Message:<br><textarea name="text" rows="8" cols="40"></textarea></label><br>
		<label><input type="submit" name="submit" value="Submit"/></label><br>
	</form>
</div>
<div class="col-sm-8 b">
<?php
echo presentPost($db, $offset, $limit);
$nrOfRows = countAllRows($db, "posts");
echo paging($limit, $offset, $nrOfRows, $numbers=5);
?>
</div>

<?php include("include/footer.php"); ?>
