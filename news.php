<?php
$pageId = "news";
$pageTitle = "- Nyheter";
include ("include/header.php");

/* initialize variables */
$limit = 5; //Posts per page
$offset = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : 0; //Start index
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";
$newsObject = new NewsObject($db);

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
	    		<input name='title' value='" . $res[0]->title . "' type='text'/><br/>
	    		<textarea name='content' value='' type='text' cols='50' rows='5'>" . $res[0]->content . "</textarea><br/>
	    		<label/>Av : " . $res[0]->title . "</label><br/>
	    		<input type='submit' name='btn_Edit' id='btn_addnew' value='Spara'/>
	    		</form>";
	}
	return $singleArticle;
}

function getArticleSideBar($newsObject, $offset, $limit)
{
	$res = $newsObject->getNewsWithOffset($offset, $limit);
	
	$side_article = "";
	if ($newsObject->isAllowedToDeleteEntry("")) // only admin
	{
		$side_article .= "<form method='get'><input style='float:right; margin-top:7px;' class='btn btn-primary'type='submit' value='Lägg till' name='action'/></form>";
	}

	$side_article .= "<article id='side_article'><h4>Nyheter</h4>";
	$side_article .= presentNews($newsObject, $offset, $limit, true);
/*	foreach ( $res as $key )
	{
		$title = $key->title;
		$added = $key->added;
		$content = \Michelf\Markdown::defaultTransform(validateText($key->content));
		$author = $key->author;
		
		$side_article .= "<a href='news.php?offset=" . $offset . "&p=" . $key->id . "'" . $key->title . ">";
		$side_article .= "<div class='sidebar'>";
		$side_article .= "<div class='sidebarHeader'>";
		$side_article .= "<span class='sidebarTitle'>" . $title . "</span>";
		$side_article .= "<span class='sidebarDate'>" . $added . "</span><hr>";
		$side_article .= "</div>";
		
		$side_article .= "<p class='guestbookText'>" . $content . "</p>";
		$side_article .= "<p class='NewsBy_p'><b>Av: </b>" . $author . "</p></a>";
		
		if ($newsObject->isAllowedToDeleteEntry("")) // admin, show all
		{
			$side_article .= "<a id='article_remove' href='news.php?action=remove&id=" . $key->id . "'><img src='img/cross.png' width=18px height=18px></a>";
			$side_article .= "<a id='article_remove' href='news.php?action=edit&id=" . $key->id . "'><img src='img/edit.jpg' width=18px height=18px></a>";
		}
		$side_article .= "</div>";
	}*/
	$nrOfRows = $newsObject->countAllRows();
	$side_article .= "</article>";
	$side_article .= paging($limit, $offset, $nrOfRows, $numbers = 5, "");
	
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
		
		$content = \Michelf\Markdown::defaultTransform($res[0]->content);
		
		$singleArticle = "<article id='singeArticle'><h3 style=''>" . $res[0]->title . "</h3><hr style='width:80%;'/>";
		$singleArticle .= "<p>" . $content . "</p>";
		$singleArticle .= "<p>By: " . $res[0]->author . "</p>";
		$singleArticle .= "</article>";
	}
}
catch ( Exception $e )
{
	$singleArticle = "<article id='singeArticle'><h3> Ingen nyhet hittades </h3><hr/><br/> " . $e . "</article>";
}

echo "<div class='row clearFix'>";
echo "<div class='col-sm-8 elementBox'>";
echo isset($singleArticle) ? $singleArticle : "";
echo "</div>";
echo "<div class='col-sm-4 elementBox'>";
echo getArticleSideBar($newsObject, $offset, $limit);
echo "</div>";
echo "</div>";
$nrOfRows = countAllRows($db, "news");
include ("include/footer.php");
?>
