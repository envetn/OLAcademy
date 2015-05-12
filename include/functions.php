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




?>
