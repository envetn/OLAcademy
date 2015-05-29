<?php
function linux_server()
{
    return in_array(strtolower(PHP_OS), array("linux", "superior operating system"));
}


// Insert post in guestbook
function makePost($db, $name, $text)
{
	// Remove html tags
	$name = strip_tags($name);
	$text = strip_tags($text);

	//Make clickable links
	preg_match_all('/http\:\/\/[\w\d\-\~\^\.\/]{1,}/',$text,$results);
	foreach($results[0] as $value)
	{
		$link = '<a href="' . $value . '" target="_blank">' . $value . '</a>';
		$text = preg_replace('/' . preg_quote($value,'/') . '/',$link,$text);
	}
	//Control message length
	$max_text_length = 2000;
	$max_name_length = 50;
	if (strlen($text) > $max_text_length)
	{
		$GLOBALS['error'] = "<pre class=red>Text must not exceed " . $max_text_length . " characters.</pre>";
	}
	elseif (strlen($name) > $max_name_length)
	{
		$GLOBALS['error'] = "<pre class=red>Name must not exceed " . $max_name_length . " characters.</pre>";
	}
	else
	{
		// Check if all fields are filled
		if(empty($name) or empty($text))
		{
			$GLOBALS['error'] = "<pre class=red>Fyll i alla fält.</pre>";
		}
		else
		{
			$sql = "INSERT INTO posts (name, text, date) VALUES(?,?,?)"; //Prepare SQL code
			$params = array($name, $text, date('Y-m-d H:i:s')); //Prepare query
			$db->ExecuteQuery($sql, $params, false); //Execute query
			header('Location: ' . $_SERVER['PHP_SELF']); //Refresh page
		}
	}
}


//Present posts from table and prepare paging
function presentPost($db, $offset, $limit) 
{
	$sql = "SELECT * FROM posts ORDER BY ID DESC LIMIT $offset, $limit"; //Prepare SQL code
	$result = $db->queryAndFetch($sql); //Execute query

	//Output data of each row
	foreach($result as $row)
	{		
		$name = $row->name;
		$text = $row->text;
		$date = $row->date;
		$text = nl2br($text); //Insert line breaks where newlines (\n) occur in the string:
		
		//Create html code for each row
		$post = "<div class='post'>
					<span class='name'>" . $name . " wrote:</span>
					<span class='date'>" . $date . "</span>
					<hr>
					<span class='text'>" . $text . "</span>
				</div>";
		echo $post;
		
	
	}
}

function datetime()
{
	return date('Y-m-d', time());
}

function validateText($text)
{
	if(strlen($text) > 300)
	{
		$text = substr($text, 0, 100) . "...";
	}
	return $text;
	//if there is no white space in the first 150chars. Then cast error or do asdasd-sadasd
}

function exceptions_error_handler($severity, $message, $filename, $lineno) {
	if (error_reporting() == 0) {
		return;
	}
	if (error_reporting() & $severity) {
		throw new ErrorException($message, 0, $severity, $filename, $lineno);
	}
}

function getArticleSideBar($db)
{
	$privilege = getUserPriviledge($db);

	
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
	//var_dump(getExtensionOnUrl());
	
	
	$side_article .= "<h4 style='#text-align: center;'>
					<a href='".$decrease."'><-Prev</a>
					 &nbsp&nbsp &nbsp&nbsp
					<a href='".$increase."'>Next-></a></h4>";
	
	foreach ($res as $key)
	{
		$side_article .= "<section>";
		$side_article .= "<a href='news.php?page=".$page."&p=".$key->id."'<h3>". $key->title ."</h3>";
		$side_article .= "<p class='date_p'>". $key->added . "</p>";
		$side_article .= "<p class='NewsContent_p'>". validateText($key->content) ."</p>";
		$side_article .= "<p class='NewsBy_p'><b>Av: </b>". $key->author ."</p></a>";
		if($privilege == 1 || $privilege == 2)
		{
			$side_article .= "<a id='article_remove' href='news.php?r=".$key->id."'>&#8649;  Ta bort  &#8647; </a>";
		}
		$side_article .= "</section><hr/>";
	}

	return $side_article .= "</article>";;
}

function getExtensionOnUrl()
{
	try 
	{
		preg_match("/\?.*/", $_SERVER['HTTP_REFERER'], $result);
		$extension = implode($result);
	}
	catch(Exception $e)
	{
		$extension = $e;
	}
	
	return $extension;
}


function getUserPriviledge($db)
{
	if(isset($_SESSION['username']))
	{
		$sql = "SELECT Privilege FROM users WHERE id=? LIMIT 1";
		$params = array($_SESSION['uid']);
		$res = $db->queryAndFetch($sql, $params);
		//TODO : is this right?
		return $res[0]->Privilege;
	}
	return 0;
}

function displayErrorMessage($message)
{
	if(preg_match("/parse/", getcwd()))
	{
		return "<div class='youShallNotPassDiv'>
		<img class='youShallNotPassPicture' src='../userImg/youShallNotPass.jpg'/>
		<div class='youShallNotPassDivP'><p>The Wizard says: </p> <p style='color:red'>$message </p></div>
		</div>";
	}
	else
	{
		return "<div class='youShallNotPassDiv'>
		<img class='youShallNotPassPicture' src='userImg/youShallNotPass.jpg'/>
		<div class='youShallNotPassDivP'><p>The Wizard says: </p> <p style='color:red'>$message </p></div>
		</div>";
	}
}
?>
