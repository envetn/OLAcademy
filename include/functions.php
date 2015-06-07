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
/*
 * Returns datetime 
 * in SQL format
 * 
 */
function sqlDatetime()
{
	return date('Y-m-d', time());
}

function dateTime()
{
	return date("Y-m-d H:i:s");
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
 * Returns a sidebar with latest news.
 * Sorted by date added.
 * Also add paging
 *
 */
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
/*
 * Return priviledge connected to
 * the logged in user.
 * Returns 
 * 0 - normal user or not logged in
 * 1 - Higher user
 * 2 - Almighty admin user
 */
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

function uploadImage($db)
{
	var_dump($_FILES);
}


/*
 * Return username
 * based on sessionId
 * 
 */
function getUserById($db)
{
	if(isset($_SESSION['uid']))
	{
		$sql = "SELECT name FROM users WHERE id=? LIMIT 1";
		$params = array($_SESSION['uid']);
		
		$res = $db->queryAndFetch($sql,$params);
		return $res[0]->name;
	}
	return "";
	
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
function showLoginLogout($db)
{
	$error = "";
	
	if(isset($_POST['login']) && ( strlen($_POST['username']) > 1 && strlen($_POST['passwd']) > 2 ) )
	{
		$username = strip_tags($_POST['username']);
		$password = md5($_POST['passwd'] . "#@$");//$GLOBAL['salt_char']);
		$sql = "SELECT * FROM users WHERE name=? AND password=? LIMIT 1";

		$params = array($username, $password);
		$res = $db->queryAndFetch($sql, $params);
		if($db->RowCount() == 1 && !( isset($_SESSION['uid']) && isset($_SESSION['username']) ) )  
		{
			$_SESSION['uid'] = $res[0]->id;
			$_SESSION['username'] = $res[0]->name;
			//after successful logon check if remember_me isset
			if(isset($POST['login']))
			{
				$cookie_name = $_SESSION['username'] . " " . "olacademy";
				$value = md5($_SESSION['username'] . "!+?");//$GLOBAL['salt_char_cookie']);
				//set cookie
				setcookie($cookie_name, $value, time() + (86400 * 14), + something, "/"); // valid for 14 days.
				var_dump($_COOKIE);
			}
		}
		else
		{
			$error .= "<p style='color:red;'>Fel lösenord eller användarnamn </p>";
		}
	}
	//also check cookie if remember_me was set
	if(isset($_SESSION['uid']))
	{
		//$form = "Användare: " . $_SESSION['username'] . "&nbsp;&nbsp;&nbsp;<input type='submit' value='Logga ut' onClick=\"window.location='logout.php'\"/>";
		$form = "<form method='post'>Användare: " . $_SESSION['username'] . "&nbsp;&nbsp;&nbsp;<input type='submit' name='logout' value='Logga ut' /></form>";
		if(isset($_POST['logout'])) 
		{
			session_destroy();
			header("location:" .$_SERVER['PHP_SELF']."");
		}
	}
	else
	{
		$form = "<form id='form_login' method='post'>
				  <input type='text' name='username' placeholder='Användarnamn' size='10'/>
				  <input type='password' name='passwd' placeholder='Lösenord' size='10'/>
				  <input type='checkbox' name='remember_me' value='remember_me'/>
				  <input id='login_submit'type='submit' value='Login' name='login'>
			 </form>";
			
	}
	return $error . $form;
}
function displayErrorMessage($message)
{
	return "<div class='youShallNotPassDiv'>
	<img class='youShallNotPassPicture' src='img/youShallNotPass.jpg'/>
	<div class='youShallNotPassDivP'><p>The Wizard says: </p> <p style='color:red'>$message </p></div>
	</div>";
}
?>