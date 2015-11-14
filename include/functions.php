 <?php
/*
 * INDEX FOR FUNCTIONS IN THIS FILE
 * linux_server()
 * makePost($db, $name, $text)
 * presentPost($db, $offset, $limit)
 * countAllRows($db, $table)
 * paging($limit, $offset, $nrOfRows, $numbers=5)
 * getCurrentMonthsEvents($db)
 * dateTime()
 * validateText($text)
 * exceptions_error_handler($severity, $message, $filename, $lineno)
 * getArticleSideBar($db, $offset, $limit)
 * getExtensionOnUrl()
 * getUserprivilege($db)
 * uploadImage($db)
 * getUserById($db)
 * showLoginLogout($db)
 * displayErrorMessage($message)
 * makeLinks($text)
 *
 */
 include("src/User/User.php");
 /*
  * GLOBALS
  */
 $GLOBAL['salt_cookie']					= "!+?";
 $GLOBAL['salt_char']                   = "#@$";
function linux_server()
{
    return in_array(strtolower(PHP_OS), array("linux", "superior operating system"));
}

function exceptions_error_handler($severity, $message, $filename, $lineno)
{
	if (error_reporting() == 0)
	{
		return;
	}
	if (error_reporting() & $severity)
	{
		throw new ErrorException($message, 0, $severity, $filename, $lineno);
	}
}


/* 
 * Insert post in guestbook
 *
 */
function makePost($db, $name, $text)
{
	// Remove html tags
	$name = strip_tags($name);
	$text = strip_tags($text);
	//Make clickable links
	$text = makeLinks($text);
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
			$params = array($name, $text, date("Y-m-d H:i:s")); //Prepare query
			$db->ExecuteQuery($sql, $params, false); //Execute query
			header('Location: ' . $_SERVER['PHP_SELF']); //Refresh page
		}
	}
}
/* 
 * Present posts from table and prepare paging 
 *
 */
function presentPost($db, $offset, $limit)
{
	$sql = "SELECT * FROM posts ORDER BY ID DESC LIMIT $offset, $limit"; //Prepare SQL code
	$result = $db->queryAndFetch($sql); //Execute query
	$post = "";
	//Output data of each row
	foreach($result as $row)
	{
		$name = $row->name;
		$text = $row->text;
		$date = $row->date;
		$text = nl2br($text); //Insert line breaks where newlines (\n) occur in the string:
		//Create html code for each row
		$post .= "<div class='guestbookPost'>
					<div class='guestbookHeader'>
						<span class='guestbookName'>" . $name . "</span>
						<span class='guestbookDate'>" . $date . "</span><hr>
					</div>
					<p class='guestbookText'>" . $text . "</p>
				</div>";
	}
	return $post;
}


function presentNews($newsObject, $offset, $limit)
{
    $res = $newsObject->getNewsWithOffset($offset, $limit);
    $news = "";
    foreach($res as $row)
    {
        $content = $row->content;
        if(strlen($content) > 150)
        {
            $content =  substr($content,0, 150). "<a href='news.php?offset=0&p=$row->id'> ... Läs mer</a>";
        }
        else
        {
            $content .= "<a href='news.php?offset=0&p=$row->id'>&nbsp; Läs mer</a>";
        }
        $news .= "<div class='index_news'>
                    <span class='guestbookName'>Title: ".$row->title."</span>
                    <br/>
                    <span> ".$content."</span>
                    <br/>
                    <span class='guestbookDate'>Av:  ".$row->author."</span>
                     <hr/>
                  </div>";
    }
    return $news;
}
/*
 * Paging
 *
 */
function paging($limit, $offset, $nrOfRows, $numbers=5, $currentUrl)
{
	$prev = $offset - $limit; //Previous page
	$next = $offset + $limit; //Next page
	$num_page = ceil($nrOfRows/$limit); //Total pages
	$cur_page = $offset/$limit + 1; //Current page
	$paging = "";
	
	//Pages out of range
	$j = $numbers >= $num_page || $cur_page <= ceil($numbers/2) ? 0 : $cur_page - ceil($numbers/2);
	if($cur_page > $num_page-ceil($numbers/2) && $num_page - $numbers > 0) $j = $num_page - $numbers;
	//Print links
	if($nrOfRows > $limit)
	{
		if($j > 0) 
		{
			$paging .= "<a href='$_SERVER[PHP_SELF]?offset=0$currentUrl'>1... </a> \n"; //Link to first page
		}
		if($offset > 0) 
		{
			$paging .= "<a href='$_SERVER[PHP_SELF]?offset=$prev'>&lt;</a> \n";//Link to previous page
		}
		
		//Pages within range
		for($i = (0 + $j); $i < $num_page && $i < $numbers + $j; $i++)
		{
			$page_link = $i * $limit;
			if($i*$limit == $offset) 
			{
				$paging .= " <b>" . ($i+1) . "</b> \n";
			}
			else 
			{
				$paging .= "<a href='$_SERVER[PHP_SELF]?offset=$page_link$currentUrl'>" . ($i+1) . "</a> \n";
			}
		}
		if($nrOfRows > $offset + $limit)
		{
			$paging .= "<a href='$_SERVER[PHP_SELF]?offset=$next$currentUrl'>&gt;</a> \n";//Link to next page
		}
		if($num_page > $numbers && $cur_page <= $num_page-ceil($numbers/2))
		{
			$paging .= "<a href='$_SERVER[PHP_SELF]?offset=".($num_page-1)*$limit."$currentUrl'> ...$num_page</a> \n";//Link to last page
		}
	}
	return $paging;
} 

/*
 - * Count all rows in a database table
 - *
 - */
function countAllRows($db, $table)
{
	$sql = "SELECT count(*) as rows FROM $table";
	$result = $db->queryAndFetch($sql); //Execute query
	return $result[0]->rows;
}
	
function presentEvent($username, $eventObject)
{
	$events = $eventObject->getWeeklyEvents();
	
	$text = "";
	for ($i=0;$i<7;$i++)
	{
		$registered = $eventObject->getNrOfRegistered(date("Y-m-d", time()+($i * 86400))); //7 db requests. Optimize?
		$weekDay = date("N", time()+($i * 86400));
		switch ($weekDay)
		{
		    case "1":
			$text.= "<h4><a href='?highlighted=$i' >Måndag</a>";
			break;
		    case "2":
			$text.= "<h4><a href='?highlighted=$i' >Tisdag</a>";
			break;
		    case "3":
			$text.= "<h4><a href='?highlighted=$i' >Onsdag</a>";
			break;
		    case "4":
			$text.= "<h4><a href='?highlighted=$i' >Torsdag</a> ";
			break;
		    case "5":
			$text.= "<h4><a href='?highlighted=$i' >Fredag</a>";
			break;
		    case "6":
			$text.= "<h4><a href='?highlighted=$i' >Lördag</a>";
			break;
		    case "7":
			$text.= "<h4><a href='?highlighted=$i' >Söndag</a>";
			break;
		    default:
			$text .= "-<br>";
		}
		$text .= "<span style='margin-left:2%;'>$registered</span><img src='img/runner.png'></h4>";

		if ($_SESSION['highlighted'] == $i)
		{
			foreach ($events as $key)
			{
				if ($key->date == date("Y-m-d", time()+($i * 86400)))
				{
					// Get registered users to event
					$registeredUsersTable = '<table style="width:100%"><th>Anmälda</th><th>Bussplats</th><th>Kommentar</th>';
					$registeredUsers = $eventObject->getRegisteredById($key->id);
					$registered = false;

					foreach ($registeredUsers as $user)
					{
						$registeredUsersTable .= "<tr><td>" . $user->name . "</td><td>" . $user->bus . "</td><td>" . $user->comment . "</td><td>";
						$userID = isset($_SESSION['uid']) ? $_SESSION['uid'] : false;
						if ($user->userID === $userID && !$registered)
						{
							$registeredUsersTable .= "<a href='?r=$user->id' ><img src='img/cross.png' width=18px height=18px></a>";
							$registered = true;
						}
						$registeredUsersTable .= "</td></tr>";
					}
					$registeredUsersTable .= "</table><hr>";

					$text .=
					"<form method='POST' action='index.php'>
						<input type='hidden' name='eventID' value=" . $key->id . ">
						<input type='hidden' name='date' value=" . date("Y-m-d", time()+($i * 86400)) . ">
						<strong><u>" .$key->eventName . "</u></strong><br>
						" .$key->info. "<br>
						<br>";
					if(!$registered)
					{
						$text .= 
						"<button type='submit' class='btn btn-primary' name='register' value='Anmäl'>Anmäl</button>
						<label for='bus'>Plats i bussen</label>
						<input type='checkbox' name='bus' value='Ja' checked><br>
						<label for='comment'>Kommentar</label>
						<input type='text' name='comment'>";
					}

					$text .="</form>";
					$text .= $registeredUsersTable;

				}
			}
		}
	}
	return $text;
}

function validateText($text)
{
	if(strlen($text) > 150)
	{
		$text = substr($text, 0, 150) . "...";
	}
	return $text;
	//if there is no white space in the first 150chars. Then cast error or do asdasd-sadasd
}

/*
 * Get text after '?' in the url
 * Good in case you want to know
 * what GET varaibles are in the url
 */
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

function uploadImage($db)
{
	var_dump($_FILES);
}

/*
 * Get login/logout form.
 * Return different forms depending
 * on if the user is logged in
 * or not.
 *
 * If there exists a username
 * and hashed passwd that matches the
 * input value, grant user access.
 */
function showLoginLogout($user, $salt_char)
{
	$error = "";
	if(isset($_COOKIE['rememberme_olacademy']))
	{
		$user->getUserByCookie();
	}
	else if(isset($_POST['login']) && !( isset($_SESSION['uid']) && isset($_SESSION['username']) ) )
	{
		$email = strip_tags($_POST['email']);
		$password = md5($_POST['passwd'] . $salt_char);

		if(!$user->login($email,$password))
		{
			$error .= "<p style='color:red;'>Fel lösenord eller email </p>";
		}
		else
		{
			header("location: ". $_SERVER['PHP_SELF']);
		}
	}
	else
	{}
	
	if(isset($_SESSION['uid']))
	{
		$form = "<form method='post' class='navbar-form navbar-right'><a href='user.php'>Användare: " . $_SESSION['username'] . "</a>&nbsp;&nbsp;&nbsp;<button type='submit' class='btn btn-primary' name='logout'>Logout</button></form>";
		if(isset($_POST['logout']))
		{
			$user->logout();
			header("location: index.php");
		}
	}
	else
	{
		$form = $user->getLoginForm();
	}

	return $error . $form;
}

function displayErrorMessage($message)
{
	return "<div class='youShallNotPassDiv'>
	<img class='youShallNotPassPicture' src='img/Error.gif'/>
	<div class='youShallNotPassDivP'><p>Det blev något fel: </p> <p style='color:red'>$message </p></div>
	</div>";
}

/*
 * Makes clickable links
 *
 *
 */
function makeLinks($text)
{
	preg_match_all('/http\:\/\/[\w\d\-\~\^\.\/]{1,}/',$text,$results);
	foreach($results[0] as $value)
	{
		$link = '<a href="' . $value . '" target="_blank">' . $value . '</a>';
		$text = preg_replace('/' . preg_quote($value,'/') . '/',$link,$text);
	}
	echo $text;
	return $text;
}

/* Log caught exceptions
 * In my opinion it is good to catch
 * some exception and save them inside log file.
 * In that case user will not be able to see
 * if s/he generated an unexpected error.
 */
function logError($message)
{
	try
	{
		$logFile = fopen("Error/error.log", "a");
		fwrite($logFile,  "\r [ ". date('Y-m-d H:i:s') ." ] - ". $message);
		fclose($logFile);
	}
	catch(Exception $e)
	{
		var_dump($e . "<br/> " . $message);
	}
}
function dump($value)
{
	echo "<div style='background:white'>";
	print_r($value);
	echo "</div>";
}

function getUrlPath()
{
	return $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];
}
?>
