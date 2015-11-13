<?php
$pageId ="news";
$pageTitle = "- Nyheter";
include("include/header.php");

/*initialize variables */
$limit       = 5; //Posts per page
$offset      = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : 0; //Start index
$privilege  = $user->getUserPrivilege();
$username    = isset($_SESSION['username']) ? $_SESSION['username']: "";

function getAddNewButton($privilege)
{
    $btn_addNew = "";
    if($privilege == 1 || $privilege == 2)
    {
        $btn_addNew = "<form method='get'><input type='submit' value='Lägg till' name='new'/></form>";
    }
    else if($privilege == 0){}
    else
    {
        $btn_addNew = "<p>Logga in för att lägga till nyheter.</p>";
    }
    return $btn_addNew;
}

function isAllowedToDeleteNews($db, $id, $privilege)
{
    if($privilege == 2)
    {
        $sql = "SELECT * FROM news WHERE id=? LIMIT 1";
        $params = array($id);
    }
    else
    {
        $sql = "SELECT * FROM news WHERE id=? AND author=? LIMIT 1";
        $params = array($id,$_SESSION['username']);
    }
    
    $res = $db->queryAndFetch($sql, $params);
    if($db->RowCount() == 1)
    {
        return true;
    }
    return false;
}


if (isset($_GET['new']) && $_GET['new'] == "Lägg till")
{
	if($privilege == 1 || $privilege == 2)
	{
		$singleArticle = "<form id='form_addNew' method='post' enctype='multipart/form-data'>
		<input name='title' placeholder='Title' type='text'/><br/>
		<textarea name='content' placeholder='Innehåll' type='text' cols='50' rows='5'></textarea><br/>
		<label/>Av : $username</label><br/>
		<input type='submit' name='btn_addNew' id='btn_addnew' value='Lägg till'/>
		</form>";
	}
	else
	{
	    $singleArticle = "";
	}
}

if(isset($_GET['p']) && is_numeric($_GET['p']))
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


if(isset($_POST['btn_addNew']))
{
	if($_POST['title'] != "" && $_POST['content'] != "")
	{
		if($privilege == 1 || $privilege == 2)
		{
			$title 	     = strip_tags($_POST['title']);
			//first strip tags, then add <a>
			$content	 = strip_tags($_POST['content']);
			$content     = makeLinks($content);
			$date		 = date("Y-m-d H:i:s");
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

if(isset($_POST['btn_Edit']))
{
    if($_POST['title'] != "" && $_POST['content'] != "")
    {
        if($privilege == 1 || $privilege == 2)
        {

            $title 	     = strip_tags($_POST['title']);
            //first strip tags, then add <a>
            $content	 = strip_tags($_POST['content']);
            $content     = makeLinks($content);
            $author 	 = $username;
            $date		 = date("Y-m-d H:i:s");
            $id          = strip_tags($_POST['id']);
            if(isset($_FILES))
            {
                uploadImage($db);
            }
            $sql = "UPDATE news SET title=?, content=?, author=?, added=? WHERE id=?";
            $params = array($title, $content, $author,$date, $id);
            $db->ExecuteQuery($sql, $params);
            header("Location: news.php");
        }
    }
}

if(isset($_GET['e']) && is_numeric($_GET['e']))
{
    $id = $_GET['e'];
    if(isAllowedToDeleteNews($db,$id, $privilege))
    {
        // get values
        $sql = "SELECT * FROM news WHERE id=? LIMIT 1";
        $params = array($id);
        $res = $db->queryAndFetch($sql, $params);
        
        if($db->RowCount() == 1)
        {
    		$singleArticle = "<form id='form_addNew' method='post' enctype='multipart/form-data'>
    		<input name='id' value='".$id."' type='hidden'/>
    		<input name='title' value='".$res[0]->title."' type='text'/><br/>
    		<textarea name='content' value='' type='text' cols='50' rows='5'>".$res[0]->content."</textarea><br/>
    		<label/>Av : ".$res[0]->title."</label><br/>
    		<input type='submit' name='btn_Edit' id='btn_addnew' value='Spara'/>
    		</form>";
        }
    }
}

if(isset($_GET['r']) && is_numeric($_GET['r']))
{
    $id = $_GET['r'];
    if(isAllowedToDeleteNews($db,$id, $privilege))
    {
        $sql = "DELETE FROM news WHERE id=?";
        $params = array($id);
        $db->ExecuteQuery($sql, $params);
    }
    header("Location: news.php");
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


echo "<div class='row clearFix'>";
    echo "<div class='col-sm-8 b'>";
    echo isset($singleArticle) ?  $singleArticle : "";
    echo "</div>";
    echo "<div class='col-sm-4 b'>";
    echo getArticleSideBar($db,$user, $offset, $limit);
    echo "</div>";
echo "</div>";
$nrOfRows = countAllRows($db, "news", false);
?>
