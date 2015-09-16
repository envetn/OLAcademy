<?php
include("include/config.php");
include("include/src/Database/database.php");
$db = new Database($GLOBAL['database']);
$privilege = userPriviledgeAdmin($db);

if(isset($_GET['p']) && is_numeric($_GET['p']))
{
	$nid = $_GET['p'];
}
else
{
	$nid = 1;
}
if(isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] >= 0)
{
	$page = $_GET['page'];
}
else
{
	$page = 0;
}
$increase = "news.php?page=".($page+5)."&p=".$nid;

if( ($page-5) > -1)
{
	$decrease = "news.php?page=".($page-5)."&p=".$nid; //add check so 0 is the lowest, and that its impossible to go futher than existing article
}
else
{
	$decrease = "news.php?page=".($page)."&p=".$nid;
}


$sql = "SELECT * FROM news ORDER BY added DESC LIMIT $page , 5 ";
$params = array($page);
$res = $db->queryAndFetch($sql,$params);

$side_article = "<article id='side_article'><h4>Nyheter</h4>";
//add paging

$side_article .= "<h3 style='text-align: center;'>
					<a href='".$decrease."'>&#8647;</a>
					 &nbsp&nbsp &nbsp&nbsp
					<a href='".$increase."'>&#8649;</a></h3>";

foreach ($res as $key)
{
	$side_article .= "<section>";
	$side_article .= "<a href='news.php?page=".$page."&p=".$key->id."'<h3>". $key->title ."</h3>";
	$side_article .= "<p class='date_p'>". $key->added . "</p>";
	$side_article .= "<p class='NewsContent_p'>". validateText($key->content) ."</p>";
	$side_article .= "<p class='NewsBy_p'><b>Av: </b>". $key->author ."</p></a>";
	if($privilege)
	{
		$side_article .= "<a id='article_remove' href='news.php?r=".$key->id."'>&#8649;  Ta bort  &#8647; </a>";
	}
	$side_article .= "</section><hr/>";
}

$side_article .= "</article>";
echo $side_article;