<?php
$pageId = "news";
$pageTitle = " - Nyheter";
include ("include/header.php");

/* initialize variables */
$limit = 5; //Posts per page
$offset = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : 0; //Start index
$newsObject = new NewsObject();

if (isset($_POST['btn_addNew']))
{
	if (validateStringPOST("title") && validateStringPOST("content") )
	{
		if ($newsObject->isAllowedToDeleteEntry(""))
		{
			$params = validateParameters();
			$imagePath = $newsObject->uploadImage(false);
			if($imagePath != null)
			{
				$params['image'] = $imagePath;
			}
			$newsObject->insertEntyToDatabase($params);
			header("location: news.php");
		}
	}
}

if (isset($_POST['btn_Edit']))
{
	if (validateStringPOST("title") && validateStringPOST("content"))
	{
		if ($newsObject->isAllowedToDeleteEntry(""))
		{

			$params = validateParameters();
			$id = strip_tags($_POST['id']);
			if(validateIntPOST("id"))
			{
				$condition["id"] = $_POST["id"];

				$newsObject->editSingleEntry($params, $condition);
				header("Location: news.php?p=" . $id);
			}
		}
	}
}

function validateParameters()
{
	$title = strip_tags($_POST["title"]);
	$content = strip_tags($_POST["content"]);
	$content = makeLinks($content);
	$date = date("Y-m-d H:i:s");
	$author = isset($_SESSION["username"]) ? $_SESSION["username"] : "";

	$params = array("title"=>$title,"content"=>$content, "author" => $author, "added"=>$date);
	return $params;
}

function getAddForm()
{
	$singleArticle = "<form id='form_addNew' method='post' enctype='multipart/form-data'>
	<input name='title' placeholder='Title' type='text'/><br/>
	<textarea name='content' placeholder='Innehåll' type='text' cols='50' rows='5'></textarea><br/>
	<label >Av : " . $_SESSION['username'] . "</label><br/>
	<input name='uploaded_file' type='file'/><br />
	<input type='submit' name='btn_addNew' id='btn_addnew' value='Lägg till'/>
	</form>";
	return $singleArticle;
}

function removeNews($newsObject)
{
	if (validateIntGET("id"))
	{
		if($newsObject->isAllowedToDeleteEntry())
		{
			$newsObject->removeSingleEntryById($_GET["id"]);
			header("Location: news.php");
		}
	}
}

function getEditForm($newsObject)
{
	if(validateIntGET("id"))
	{
		$id = $_GET["id"];
		$condition = array('id' => $_GET["id"]);

		$res = $newsObject->fetchSingleEntryByValue($condition);
		$singleArticle = "";
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
	return "";

}

function getArticleSideBar($newsObject, $offset, $limit)
{
	$res = $newsObject->fetchEntryWithOffset($offset, $limit);

	$side_article = "";
	if ($newsObject->isAllowedToDeleteEntry("")) // only admin
	{
		$side_article .= "<form method='get' id='addNews'><input style='float:right;' class='btn btn-primary'type='submit' value='Lägg till' name='action'/></form>";
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
	$values = array();
	if (validateIntGET("p"))
	{
		$values = array("id" => $_GET["p"]);
	}

	return $newsObject->fetchSingleEntryByValue($values);
}

function validateAction($newsObject)
{
	$singleArticle = "";
	if (validateStringGET("action") && $newsObject->isAllowedToDeleteEntry(""))
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

	if (empty($singleArticle))
	{
		$res = validateSelectedPage($newsObject);
		$content = \Michelf\Markdown::defaultTransform($res->content); // Need to form text in someway..

		$singleArticle = "<article id='singleArticle'><h3 style=''>" . $res->title . "</h3><hr style='width:80%;'/>";
		$singleArticle .= "<pre class='newsText'>" . $content . "</pre>";
		$singleArticle .= strlen($res->image) > 1 ? "<img src='$res->image'/>" : "";
		$singleArticle .= "<span id='author'>Skribent: " . $res->author . "</span>";

		$singleArticle .= "</article>";
	}
}
catch ( Exception $e )
{
	$singleArticle = "<article id='singeArticle'><h3> Ingen nyhet hittades </h3><hr/><br/> " . $e . "</article>";
}

echo isset($_SESSION['error']) ? $_SESSION['error'] : "";
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
