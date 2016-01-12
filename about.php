<?php
$pageId = "about";
$pageTitle =" - Om";
include ("include/header.php");

$user = new User();
$about = new AboutObject();

$res = $about->fetchAllEntries();
function parselist($name)
{
	$i = 0;
	$data = "";
	$hasNext = true;
	while($hasNext)
	{
		if(isset($_POST[$name."_".$i]))
		{
			$data .= $_POST[$name."_".$i];
			$i++;
			if(isset($_POST[$name."_".$i]))
			{
				$data  .= "@";
			}
			else
			{
				$hasNext = false;
			}
		}
		else
		{
			$hasNext = false;
		}
	}
	return $data;
}

if(isset($_POST["generalInfo"])  && $user->getUserPrivilege() === "2")
{
	//some validation..
	$generalInfo = $_POST["generalInfo"];
	$offerInfo = parselist("listInfo");

	$additionalInfo = $_POST["additionalInfo"];
	$externalLinks = parselist("externalLinks");

	$params = array("generalInfo"=>$generalInfo, "offerInfo"=>$offerInfo, "additionalInfo" => $additionalInfo);
	$condition = array("id" => 1);
	$about->editSingleEntry($params, $condition);
	header("location: about.php");
}

if (isset($_GET["edit"]) && $user->getUserPrivilege() === "2")
{
	// Show edit fields
	$content = $about->parseDataForEdit($res);
	$aboutText = "<h2> Allmän info text</h2><form method='post'>";
	$aboutText .= "<textarea rows='8' cols='50' name='generalInfo'>". $content['generalInfo'] ."</textarea><br/><hr/>";
	foreach($content["offerInfo"] as $info)
	{
		$aboutText .= $info . "<br/>";
	}
	$aboutText .= "<h3>Extra info text</h3>";
	$aboutText .= "<textarea rows='8' cols='50'name='additionalInfo'>". $content['additionalInfo'] ."</textarea><br/><hr/>";
	$aboutText .= "<h3> Extra info Länkar</h3>";

	foreach($content["externalLinks"] as $info)
	{
		$aboutText .= $info . "<br/>";
	}
	$aboutText .= "<input type='submit' value='submit'></form>";
}
else
{
	// Show about text
	$content = $about->parseData($res);
	$aboutText = "<h2> Allmän info text</h2>
	<pre class='newsText'>
	" . $content['generalInfo'] . "<br/><hr/>

	<h3> Info om vad som erbjuds</h3>
	" . $content['offerInfo'] . "<br/><hr/>

	<h3> Extra info text</h3>
	" . $content['additionalInfo'] . "<br/><hr/>

	<h3> Extra info Länkar</h3>
	" . $content['externalLinks'];

	if ($user->getUserPrivilege() === "2")
	{
		$aboutText .= "<br/><hr/><a href='about.php?edit'> Editera innehåll</a>";
	}
	$aboutText .= "</pre>";
}

echo "<div class='row'>";
echo $aboutText;
echo "</div>";

include ("include/footer.php");