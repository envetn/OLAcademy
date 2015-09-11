 <?php
/* 
 * INDEX FOR FUNCTIONS IN THIS FILE
 * linux_server()
 * makePost($db, $name, $text)
 * presentPost($db, $offset, $limit)
 * countAllRows($db, $table)
 * paging($limit, $offset, $nrOfRows, $numbers=5)
 * sqlDatetime()
 * dateTime()
 * validateText($text)
 * exceptions_error_handler($severity, $message, $filename, $lineno)
 * getArticleSideBar($db, $offset, $limit)
 * getExtensionOnUrl()
 * getUserPriviledge($db)
 * uploadImage($db)
 * getUserById($db)
 * showLoginLogout($db)
 * setRememberMe($user,$key,$db)
 * getUserByCookie($db)
 * displayErrorMessage($message)
 * makeLinks($text)
 * 
 */
 
function linux_server()
{
    return in_array(strtolower(PHP_OS), array("linux", "superior operating system"));
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
			$params = array($name, $text, dateTime()); //Prepare query
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
		$post .= "<div class='post'>
					<span class='name'>" . $name . " wrote:</span>
					<span class='date'>" . $date . "</span>
					<hr>
					<span class='text'>" . $text . "</span>
				</div>";
	}
	return $post;
}
function presentNews($db, $offset, $limit)
{
    $sql = "SELECT * FROM news LIMIT $offset, $limit"; 
    //why you no work with ??
    $res = $db->queryAndFetch($sql);
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
                    <span>Title: ".$row->title."</span>
                    <br/>
                    <span> ".$content."</span>
                    <hr/>
                    <span>Av:  ".$row->author."</span>
                  </div>";
    }
    return $news;
}
/* 
 * Count all rows in a database table
 *
 */
function countAllRows($db, $table)
{
	$sql = "SELECT count(*) as rows FROM $table";
	$result = $db->queryAndFetch($sql); //Execute query
	return $result[0]->rows;
}
/* 
 * Paging
 *
 */
function paging($limit, $offset, $nrOfRows, $numbers=5)
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
		if($j > 0) $paging .= "<a href='$_SERVER[PHP_SELF]?offset=0'>1... </a> \n"; //Link to first page
		if($offset > 0) $paging .= "<a href='$_SERVER[PHP_SELF]?offset=$prev'>&lt;</a> \n";//Link to previous page
		
		//Pages within range
		for($i = (0 + $j); $i < $num_page && $i < $numbers + $j; $i++)
		{
			$page_link = $i * $limit;
			if($i*$limit == $offset) $paging .= " <b>" . ($i+1) . "</b> \n"; 
			else $paging .= "<a href='$_SERVER[PHP_SELF]?offset=$page_link'>" . ($i+1) . "</a> \n";
		}
		if($nrOfRows > $offset + $limit) 
			$paging .= "<a href='$_SERVER[PHP_SELF]?offset=$next'>&gt;</a> \n";//Link to next page
		if($num_page > $numbers && $cur_page <= $num_page-ceil($numbers/2)) 
			$paging .= "<a href='$_SERVER[PHP_SELF]?offset=".($num_page-1)*$limit."'> ...$num_page</a> \n";//Link to last page
	}
	return $paging;
} 
/*
 * Returns all events 
 * within the current month
 */
function getCurrentMonthsEvents($db)
{
/*
 *  WHERE EXISTS 
        (
            SELECT * FROM registerd as r 
            WHERE events.id = r.eventId AND 
        )
 */
    $sql ="
        SELECT *
        FROM events 
        WHERE date BETWEEN ? AND ?
        ";
    $firstDay = (new DateTime('first day of this month'))->format('Y-m-d');
    $lastDay  = (new DateTime('last day of this month'))->format('Y-m-d');
     
    $params = array($firstDay, $lastDay);
    $result = $db->queryAndFetch($sql,$params);
    return $result;
}

function getRegisteredEvents($db, $username)
{
    $sql ="
        SELECT *
        FROM events as e 
        WHERE EXISTS 
        (
            SELECT * FROM registerd as r 
            WHERE e.id = r.eventId AND r.name = ?
        )
        ";
    $params = array($username);
    $result = $db->queryAndFetch($sql ,$params);
    return $result;
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
 * Also style text according to markdown.
 * ? Should we do that ?
 * Also add paging
 *
 */
function getArticleSideBar($db, $offset, $limit)
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
	$sql = "SELECT * FROM news ORDER BY added DESC LIMIT $offset, $limit";
	$res = $db->queryAndFetch($sql);
	$side_article = "<article id='side_article'><h4>Nyheter</h4>";

	foreach ($res as $key)
	{
		$side_article .= "<section>";
		$side_article .= "<a href='news.php?offset=".$offset."&p=".$key->id."'<h3>". $key->title ."</h3>";
		$side_article .= "<p class='date_p'>". $key->added . "</p>";
		$side_article .= "<p class='NewsContent_p'>". 
		  \Michelf\Markdown::defaultTransform(validateText($key->content)) ."</p>";
		$side_article .= "<p class='NewsBy_p'><b>Av: </b>". $key->author ."</p></a>";
	    if($privilege == 1) //only show users article
        {
           if($_SESSION['username'] == $key->author)
           {
               $side_article .= "<a id='article_remove' href='news.php?r=".$key->id."'>&#8649;  Ta bort  &#8647; </a>";
           }
        }
        else if($privilege == 2) // admin, show all
        {
            $side_article .= "<a id='article_remove' href='news.php?r=".$key->id."'>&#8649;  Ta bort  &#8647; </a>";
        }
		$side_article .= "</section><hr/>";
	}
	//add paging
	$nrOfRows = countAllRows($db, "news");
	$side_article .= paging($limit, $offset, $nrOfRows, $numbers=5);
	
	
	return $side_article .= "</article>";
}

function presentArticleSideBar($db,$offset,$limit)
{
    $privilege = getUserPriviledge($db);
    
    $sql = "SELECT * FROM news ORDER BY added DESC LIMIT $offset, $limit";
    $params = array($offset, $limit);
    $res = $db->queryAndFetch($sql,$params);
    
    $side_article = "<article id='side_article'><h4>Nyheter</h4>";

    foreach ($res as $key)
    {
        $content = \Michelf\Markdown::defaultTransform($key->content);
        $side_article .= "<section>";
        $side_article .= "<a href='news.php?offset=".$offset."&p=".$key->id."'<h3>". $key->title ."</h3>";
        $side_article .= "<p class='date_p'>". $key->added . "</p>";
        $side_article .= "<p class='NewsContent_p'>". validateText($content) ."</p>";
        $side_article .= "<p class='NewsBy_p'><b>Av: </b>". $key->author ."</p></a>";
        

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
	if(isset($_SESSION['username']) && isset($_SESSION['uid']) )
	{
		$sql = "SELECT Privilege FROM users WHERE id=? AND name=? LIMIT 1";
		$params = array($_SESSION['uid'],$_SESSION['username']);
		$res = $db->queryAndFetch($sql, $params);
		if($db->RowCount() == 1)
		{
		    return $res[0]->Privilege;
		}
	}
	return 0;
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
function showLoginLogout($db)
{
	$error = "";
	if(isset($_COOKIE['rememberme_olacademy']))
	{
		getUserByCookie($db);
	}
	else if(isset($_POST['login']) && !( isset($_SESSION['uid']) && isset($_SESSION['username']) ) )
	{
		$username = strip_tags($_POST['username']);
		$password = md5($_POST['passwd'] . "#@$");//$GLOBAL['salt_char']);
		$sql = "SELECT id,name FROM users WHERE name=? AND password=? LIMIT 1";

		$params = array($username, $password);
		$res = $db->queryAndFetch($sql, $params);

		if($db->RowCount() == 1)
		{
			if(( strlen($_POST['username']) > 1 && strlen($_POST['passwd']) > 2 ))
			{
				$_SESSION['uid'] = $res[0]->id;
				$_SESSION['username'] = $res[0]->name;

				//after successful logon
				//check if remember_me isset
				if(isset($_POST['remember_me']))
				{
					$SECRET_KEY = "!+?";
					setRememberMe($username,$SECRET_KEY,$db);
				}
			}
		}
		else
		{
			$error .= "<p style='color:red;'>Fel lösenord eller användarnamn </p>";
		}
	}
	else
	{}
	
	/*
	 * If User is found using cookie $_SESSION['uid']
	 * will be set, and condition below true.
	 * Maybe delete token from db?
	 */
	if(isset($_SESSION['uid']))
	{
		$form = "<form method='post'>Användare: " . $_SESSION['username'] . "&nbsp;&nbsp;&nbsp;<input type='submit' name='logout' value='Logga ut' /></form>";
		if(isset($_POST['logout']))
		{
			session_destroy();
			header("location:" .$_SERVER['PHP_SELF']."");
			setcookie("rememberme_olacademy","" ,time() - (86400 * 31));
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

/*
 * Set remember me cookie
 * Everytime the function is called
 * a new token is generated for the user
 * and stored in db
 */
function setRememberMe($user,$key,$db)
{
	// generate a token for storing in cookie
	$token = md5(uniqid($user, true));
	$SECRET_KEY = "!+?";
	$shaToken = hash_hmac('sha256', $token,$SECRET_KEY );
	$oneMonth = time() + (86400 * 30);
	setcookie('rememberme_olacademy', $token, $oneMonth);

	$sql = "UPDATE users SET token=? WHERE id=? AND name=? LIMIT 1";
	$params = array($shaToken,$_SESSION['uid'],$user);
	$db->ExecuteQuery($sql, $params);

}
/*
 * Gets the username and id by the cookies token.
 * Use sha256 hash and try to find
 * a match in db.
 */
function getUserByCookie($db)
{
	$shaToken = hash_hmac('sha256', $_COOKIE['rememberme_olacademy'],"!+?" );
	$sql = 'SELECT id,name FROM users WHERE token=? LIMIT 1';
	$params = array($shaToken);
	$res = $db->queryAndFetch($sql,$params);
	if($db->RowCount() == 1)
	{
		$_SESSION['uid'] = $res[0]->id;
		$_SESSION['username'] = $res[0]->name;
	}
}

function displayErrorMessage($message)
{
	return "<div class='youShallNotPassDiv'>
	<img class='youShallNotPassPicture' src='img/youShallNotPass.jpg'/>
	<div class='youShallNotPassDivP'><p>The Wizard says: </p> <p style='color:red'>$message </p></div>
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
?>