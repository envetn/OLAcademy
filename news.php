<?php
include("include/header.php");

$sql = "SELECT * FROM news";
$res = $db->queryAndFetch($sql);
$listOfArticle[0] = "";
$latestArticle = 0;
$side_article = "<article id='side_article'><h4>Nyheter</h4>";
foreach ($res as $key)
{
	//sidebar
	$side_article .= "<section>";
	$side_article .= "<a href='news.php?p=".$key->id."'<h3>". $key->title ."</h3>";
	$side_article .= "<p class='date_p'>". $key->added . "</p>";
	$side_article .= "<p>". validateText($key->content) ."</p>";
	$side_article .= "<p><b>Av: </b>". $key->author ."</p></a>";
	$side_article .= "</section><hr/>";
	
	//main
	//might be to much if there are many news
	//TODO: Stupid idea, the page is reloaded everytime a article is clicked. Better call db everytime a article is clicked.
	$listOfArticle[$key->id]['title']     = $key->title;
	$listOfArticle[$key->id]['content']   = $key->content;
	$listOfArticle[$key->id]['Author']	  = "";
	$listOfArticle[$key->id]['date'] 	  = "";
	
	$latest = $key->id;//not a very good solution
	
	
}
$side_article .= "</article>";

if(isset($_GET['p']) && is_numeric($_GET['p']))
{
	$p = $_GET['p'];
	try 
	{
		$singleArticle = "<article id='singeArticle'><h3 style=''>" . $listOfArticle[$p]['title'] ."</h3>";
		$singleArticle .= "<p>".  $listOfArticle[$p]['content'] ."</p>";
		$singleArticle .= "</article>";
	}
	catch (Exception $e) 
	{
		$singleArticle = "<article id='singeArticle'><h3> Ingen nyhet hittades</h3></article>";
	}
}
else
{
	$singleArticle = "<article id='singeArticle'><h3 style=''>" . $listOfArticle[$latestArticle + 1]['title'] ."</h3>";
	$singleArticle .= "<p>".  $listOfArticle[$latestArticle + 1]['content'] ."</p>";
	$singleArticle .= "</article>";
}



if(isset($_POST['btn_addNews']))
{
	if($_POST['title'] != "" && $_POST['content'] != "" && $_POST['author'] != "" )
	{
		//Gather content and send to database
	}
}
?>
<style>
#form_addNew
{
	border:1px solid black;
	float:right;
	width:60%;
	margin-top:4%;
	margin-right:4%;
	padding:2%;
}
#form_addNew input, textarea
{
	width:90%;
}

</style>
<div id='div_articles'>
<?php echo isset($side_article)  ?  $side_article  : "";?>
<?php  echo isset($singleArticle) ?  $singleArticle : "";?>
<form id='form_addNew' method='post'>
	<input name='title' placeholder='Title' type='text'/><br/>
	<textarea name='content' placeholder='Innehåll' type='text' cols='50' rows='5'></textarea><br/>
	<input name='author' placeholder='Av' type='text'/><br/>
	<input type='submit' name='btn_addNews' value='Lägg till'/>
</form>
</div>


</body>