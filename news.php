<?php
include("include/header.php");

$sql = "SELECT * FROM news";
$res = $db->queryAndFetch($sql);
$side_article = "<article id='side_article'><h4>Nyheter</h4>";
$listOfArticle[0] = "";
$latestArticle = 0;
$i=0;
foreach ($res as $key)
{
	//sidebar
	$side_article .= "<section>";
	$side_article .= "<a href='news.php?p=".$key->id."'<h3>". $key->title ."</h3>";
	$side_article .= "<p class='date_p'>". $key->added . "</p>";
	$side_article .= "<p>". validateText($key->content) ."</p>";
	$side_article .= "<p>". $key->author ."</p></a>";
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
?>
<style>



</style>
<div id='asdasdasd' style='width:80%'>
<?php echo isset($side_article)  ?  $side_article  : "";?>
<?php echo isset($singleArticle) ?  $singleArticle : "";?>
</div>
