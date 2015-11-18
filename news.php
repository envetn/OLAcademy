<?php
$pageId = "news";
$pageTitle = " - Nyheter";
include ("include/header.php");

/* initialize variables */
$limit = 5; //Posts per page
$offset = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : 0; //Start index
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";
$newsObject = new NewsObject();

if (isset($_POST['btn_addNew']))
{
	if ($_POST['title'] != "" && $_POST['content'] != "")
	{
		if ($newsObject->isAllowedToDeleteEntry(""))
		{
			$title = strip_tags($_POST['title']);
			//first strip tags, then add <a>
			$content = strip_tags($_POST['content']);
			$content = makeLinks($content);
			$date = date("Y-m-d H:i:s");
			$author = $username;
			
			$params = array($title,$content,$author,$date);
			$newsObject->addSingleEntry($params);

			header("Location: news.php");
		}
	}
}

if (isset($_POST['btn_Edit']))
{
	if ($_POST['title'] != "" && $_POST['content'] != "")
	{
		if ($newsObject->isAllowedToDeleteEntry(""))
		{
			$title = strip_tags($_POST['title']);

			$content = strip_tags($_POST['content']);
			$content = makeLinks($content);
			$author = $username;
			$date = date("Y-m-d H:i:s");
			$id = strip_tags($_POST['id']);

			$params = array($title,$content,$author,$date,$id);
			$newsObject->editSingleEntryById($id, $params);
			header("Location: news.php?p=" . $id);
		}
	}
}

function getAddForm()
{
	$singleArticle = "<form id='form_addNew' method='post' enctype='multipart/form-data'>
	<input name='title' placeholder='Title' type='text'/><br/>
	<textarea name='content' placeholder='Innehåll' type='text' cols='50' rows='5'></textarea><br/>
	<label >Av : " . $_SESSION['username'] . "</label><br/>
	<input type='submit' name='btn_addNew' id='btn_addnew' value='Lägg till'/>
	</form>";
	return $singleArticle;
}

function removeNews($newsObject)
{
	if (isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$id = $_GET['id'];
		$newsObject->removeSingleEntryById($id);
		header("Location: news.php");
	}
}

function getEditForm($newsObject)
{
	$id = $_GET['id'];
	$res = $newsObject->fetchSingleEntryById($id);
	
	if ($res != null)
	{
		$singleArticle = "<form id='form_addNew' method='post' enctype='multipart/form-data'>
	    		<input name='id' value='" . $id . "' type='hidden'/>
	    		<input name='title' value='" . $res->title . "' type='text'/><br/>
	    		<textarea name='content' value='' type='text' cols='50' rows='5'>" . $res->content . "</textarea><br/>
	    		<label/>Av : " . $res->title . "</label><br/>
	    		<input type='submit' name='btn_Edit' id='btn_addnew' value='Spara'/>
	    		</form>";
	}
	return $singleArticle;
}

function getArticleSideBar($newsObject, $offset, $limit)
{
	$res = $newsObject->fetchEntryWithOffset($offset, $limit);
	
	$side_article = "";
	if ($newsObject->isAllowedToDeleteEntry("")) // only admin
	{
		$side_article .= "<form method='get'><input style='float:right;' class='btn btn-primary'type='submit' value='Lägg till' name='action'/></form>";
	}

	$side_article .= "<article id='side_article'><h2>Nyheter</h2>";
	$side_article .= presentNews($newsObject, $offset, $limit, true);
	$nrOfRows = $newsObject->countAllRows();
	$side_article .= "</article>";
	$side_article .= "<div class='paging'>".paging($limit, $offset, $nrOfRows, $numbers = 5)."</div>";
	
	return $side_article;
}
function validateSelectedPage($newsObject)
{
	if (isset($_GET['p']) && is_numeric($_GET['p']))
	{
		$id = $_GET['p'];
	}
	else
	{
		$id = - 1;
	}
	return $newsObject->fetchSingleEntryById($id);
}

function validateAction($newsObject)
{
	$singleArticle = "";
	if (isset($_GET["action"]) && $newsObject->isAllowedToDeleteEntry(""))
	{
		
		$choice = $_GET["action"];
		switch ($choice)
		{
			case "Lägg till" :
				$singleArticle = getAddForm();
				break;

			case "remove" :
				removeNews($newsObject);
				break;
					
			case "edit" :
				$singleArticle = getEditForm($newsObject);
				break;
		}
	}
	return $singleArticle;
}

try
{
	$singleArticle = validateAction($newsObject);
	
	if (strlen($singleArticle) < 1)
	{
		$res = validateSelectedPage($newsObject);
		
		$content = $res->content;
		
		$singleArticle = "<article id='singleArticle'><h3 style=''>" . $res->title . "</h3><hr style='width:80%;'/>";
		$singleArticle .= "<pre class='newsText'>" . $content . "</pre>";
		$singleArticle .= "<span id='author'>Skribent: " . $res->author . "</span>";
		$singleArticle .= "</article>";
	}
}
catch ( Exception $e )
{
	$singleArticle = "<article id='singeArticle'><h3> Ingen nyhet hittades </h3><hr/><br/> " . $e . "</article>";
}

echo "<div class='row'>";
echo "<div class='col-sm-8 col-sm-push-4 elementBox'>";
echo isset($singleArticle) ? $singleArticle : "";
echo "</div>";
echo "<div class='col-sm-4 col-sm-pull-8 elementBox'>";
echo getArticleSideBar($newsObject, $offset, $limit);
echo "</div>";
echo "</div>";
include ("include/footer.php");
?>
