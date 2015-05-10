<?php
include("include/header.php");

$sql = "SELECT * FROM news";
$res = $db->queryAndFetch($sql);
$article = "<h4>Nyheter</h4><article>";
foreach ($res as $key)
{
	validateText();
	$article .= "<section>";
	$article .= "<a href='news.php?p=".$key->id."'<h3>". $key->title ."</h3>";
	$article .= "<p class='date_p'>". $key->added . "</p>";
	$article .= "<p>". $key->content ."</p>";
	$article .= "<p>". $key->author ."</p></a>";
	$article .= "</section><hr/>";
}

function validateText()
{
	//if there is no white space in the first 150chars. Then cast error or do asdasd-sadasd
}
?>
<style>
h3
{
    margin:0px;
    padding:0px;
}

h3
{
}
.date_p
{
    font-size:12px;
}
article
{
    width:50%;
    margin:1%;
}

article p
{
    width:70%;
}
article:hover
{

}
article a
{
    text-decoration:none;
    color:black;
}

section
{
    border:1px solid black;
}
</style>
<h4>Nyheter</h4>

<?php echo $article;?>
