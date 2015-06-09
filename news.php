
<?php
/*If not uploading correctly
 * Check php.ini for file_uploads = On, post_max_size, and upload_max_file_size. Make sure you’re editing the correct php.ini – use phpinfo() to verify your settings. Make sure you don’t misspell the directives as 8MB instead of the expected 8M!
 * Make sure your directory has read+write permissions set for the tmp and upload directories
 * Make sure your file destination and tmp/upload directories do not have spaces in them.
 */
$pageTitle = "- Nyheter";
include("include/header.php");
/*initialize variables */
$priviledge = getUserPriviledge($db);
$username = getUserById($db);
$btn_addNew = ($priviledge == 1 || $priviledge == 2) ? "<form method='get'><input type='submit' value='Lägg till' name='p'/></form>" : "<p>Logga in för att lägga till nyheter.</p>";

if (isset($_GET['p']) && $_GET['p'] == "Lägg till")
{
	if($priviledge == 1 || $priviledge == 2)
	{
		$singleArticle = "<form id='form_addNew' method='post' enctype='multipart/form-data'>
		<input name='title' placeholder='Title' type='text'/><br/>
		<textarea name='content' placeholder='Innehåll' type='text' cols='50' rows='5'></textarea><br/>
		<label/>Av : $username</label><br/>
		
<!--		<input type='hidden' name='name' value='".datetime(). " - ".$username."'></input><br/>
		<label for='file'>Filename:</label><br/>
		<input type='file' name='fileToUpload' id='fileToUpload'><br> 
-->
		
		<input type='submit' name='btn_addNew' value='Lägg till'/>
		</form>";
		
/*		
				"<form action='' method='post' id='form_addNew'
				enctype='multipart/form-data'>
				<input type='hidden' name='name' value='".datetime(). " - ".$username."'></input><br/>
				<label for='file'>Filename:</label><br/>
				<input type='file' name='fileToUpload' id='fileToUpload'><br>
				<input type='submit' name='submit' value='Submit'>
				</form>";*/
	}
}
else if(isset($_GET['r']) && is_numeric($_GET['r']) && $priviledge == 2)
{
	/*remove news */
	// TODO : display popup before delete?
	$id = $_GET['r'];
	$sql = "DELETE FROM news WHERE id=?";
	$params = array($id);
	$db->ExecuteQuery($sql, $params);
	header("Location: news.php");
}
else if(isset($_GET['p']) && is_numeric($_GET['p']))
{
	//check page
	if(isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] >= 0)
	{
		$page = $_GET['page'];
	}
	else
	{
		$page = 0;
	}
	$p = $_GET['p'];
	$sql = "SELECT * FROM news WHERE id=? LIMIT 1";
	$params = array($p);
	$res = $db->queryAndFetch($sql,$params);
}
else
{
	$sql = "SELECT * from news ORDER BY id DESC LIMIT 1;";
	$res = $db->queryAndFetch($sql);
}

if(isset($_POST['btn_addNew']))
{
	if($_POST['title'] != "" && $_POST['content'] != "")
	{
		if($priviledge == 1 || $priviledge == 2)
		{
		
			$title 	     = strip_tags($_POST['title']);
			$content	 = strip_tags($_POST['content']);
			$date		 = dateTime();
			$author 	 = $username;
			if(isset($_FILES))
			{
				uploadImage($db);
			}
			$sql = "INSERT INTO news (title, content, author, added) VALUES (?,?,?,?)";
			$params = array($title, $content, $author, $date);
			$db->ExecuteQuery($sql, $params);
			header("Location: news.php");
		}
	}
}

try
{
	//Single article has not been initialized yet.
	if(!isset($singleArticle))
	{
		$singleArticle = "<article id='singeArticle'><h3 style=''>" . $res[0]->title ."</h3><hr style='width:80%;'/>";
		$singleArticle .= "<p>".   $res[0]->content ."</p>";
		$singleArticle .= "<p>By: ".   $res[0]->author ."</p>";
		$singleArticle .= "</article>";
	}
}
catch (Exception $e)
{
	$singleArticle = "<article id='singeArticle'><h3> Ingen nyhet hittades <hr/><br/> ".$e."</h3></article>";
}

?>
<div id='div_articles'>
<?php echo $btn_addNew; ?>
<?php echo getArticleSideBar($db);?>
<?php  echo isset($singleArticle) ?  $singleArticle : "";?>
</div>

</body>