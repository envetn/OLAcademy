<?php

$pageTitle = "- Nyheter";
include("include/header.php");

/*initialize variables */
$limit       = 5; //Posts per page
$offset      = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : 0; //Start index
$priviledge  = getUserPriviledge($db);
$username    = isset($_SESSION['username']) ? $_SESSION['username']: "";
$btn_addNew  = "";

// Show add news button
if($priviledge == 1 || $priviledge == 2)
{
    $btn_addNew = "<form method='get'><input type='submit' value='Lägg till' name='p'/></form>";
}
else if(isset($_SESSION['username']) && $priviledge == 0){}
else
{
    $btn_addNew = "<p>Logga in för att lägga till nyheter.</p>";
}
function isAllowedToDelete($db, $id)
{
    $sql = "SELECT * FROM news WHERE id=? AND author=?";
    $params = array($id,$_SESSION['username']);
    $res = $db->queryAndFetch($sql, $params);
    if($db->RowCount() == 1)
    {
        return true;
    }
    return false;
}

if (isset($_GET['p']) && $_GET['p'] == "Lägg till")
{
	if($priviledge == 1 || $priviledge == 2)
	{
		$singleArticle = "<form id='form_addNew' method='post' enctype='multipart/form-data'>
		<input name='title' placeholder='Title' type='text'/><br/>
		<textarea name='content' placeholder='Innehåll' type='text' cols='50' rows='5'></textarea><br/>
		<label/>Av : $username</label><br/>
		<input type='submit' name='btn_addNew' value='Lägg till'/>
		</form>";
	}
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

}
else
{
	$params = array("");
	$sql = "SELECT * from news ORDER BY id DESC LIMIT 1;";
}

try
{
    //Show article or form for adding new
    if(!isset($singleArticle))
    {
        $res = $db->queryAndFetch($sql,$params);
        $content = \Michelf\Markdown::defaultTransform($res[0]->content);

        $singleArticle = "<article id='singeArticle'><h3 style=''>" . $res[0]->title ."</h3><hr style='width:80%;'/>";
        $singleArticle .= "<p>".  $content  ."</p>";
        $singleArticle .= "<p>By: ".   $res[0]->author ."</p>";
        $singleArticle .= "</article>";
    }
}
catch (Exception $e)
{
    $singleArticle = "<article id='singeArticle'><h3> Ingen nyhet hittades <hr/><br/> ".$e."</h3></article>";
}

if(isset($_POST['btn_addNew']))
{
	if($_POST['title'] != "" && $_POST['content'] != "")
	{
		if($priviledge == 1 || $priviledge == 2)
		{
		    
			$title 	     = strip_tags($_POST['title']);
			//first strip tags, then add <a>
			$content	 = strip_tags($_POST['content']);
			$content     = makeLinks($content);
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

if(isset($_GET['r']) && is_numeric($_GET['r']))
{
    /*remove news */
    // TODO : display popup before delete?

    $id = $_GET['r'];
    if(isAllowedToDelete($db,$id))
    {
        $sql = "DELETE FROM news WHERE id=?";
        $params = array($id);
        $db->ExecuteQuery($sql, $params);
    }
    header("Location: news.php");
}




echo "<div id='div_articles'>";
    echo $btn_addNew;
    echo getArticleSideBar($db, $offset, $limit);
    echo isset($singleArticle) ?  $singleArticle : "";
echo "</div>";
$nrOfRows = countAllRows($db, "news", false);
?>
</div>
</body>