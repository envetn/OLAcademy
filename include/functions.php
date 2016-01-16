 <?php

/*
 *Global
*/
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
 * Present posts from table
 *
 */
function presentPost($guestbookObject, $offset, $limit)
{
	$result = $guestbookObject-> fetchEntryWithOffset($offset, $limit);
	$post = "";

	foreach($result as $row)
	{
		$text = $row->text;
		$post .=
				"<div class='guestbookPost'>
					<div class='guestbookHeader'>
						<span class='guestbookName'>" . $row->name . "</span>
						<span class='guestbookDate'>" . $row->added . "</span>
					</div>
					<pre class='guestbookText'>" . $text . "</pre>
				</div>";
	}
	return $post;
}


function presentNews($newsObject, $offset, $limit, $showEdit)
{
    $res = $newsObject->fetchEntryWithOffset($offset, $limit);
    $news = "";
    foreach($res as $row)
    {
    	$content = $row->content;
        if(strlen($content) > 400)
        {
            $content =  substr($content, 0, 400) . " ...";
        }
        $news .=
				"<div class='newsPost'>
					<a href='news.php?offset=$offset&amp;p=$row->id'><span class='boxLink'></span></a>


        			<div class='newsHeader'>
						<span class='newsTitle'>".$row->title."</span>";
		if ($showEdit && $newsObject->isAllowedToDeleteEntry("")) // admin, show all
		{
			$news .= "<a class='newsEdit' href='news.php?action=edit&amp;id=" . $row->id . "'><img src='img/edit.png' width=18px height=18px></a>";
			$news .= "<a class='newsEdit' href='news.php?action=remove&amp;id=" . $row->id . "'><img src='img/cross.png' width=18px height=18px></a>";
		}
		$news .="<span class='newsAdded'>" . $row->added . " </span>
                </div>
                <pre class='newsText'>" . $content . "</pre>
                </div>";

    }
    return $news;
}

/*
 * Paging
 *
 */
function paging($limit, $offset, $nrOfRows, $numbers=5, $currentUrl="") // admin.php uses currentUrl
{
	$prev = $offset - $limit;
	$next = $offset + $limit;
	$num_page = ceil($nrOfRows/$limit);
	$cur_page = $offset/$limit + 1;
	$paging = "";

	$j = $numbers >= $num_page || $cur_page <= ceil($numbers/2) ? 0 : $cur_page - ceil($numbers/2);
	if($cur_page > $num_page-ceil($numbers/2) && $num_page - $numbers > 0) $j = $num_page - $numbers;

	if($nrOfRows > $limit)
	{
		if($j > 0)
		{
			$paging .= "<a href='$_SERVER[PHP_SELF]?offset=0$currentUrl'><span class='pageLink'>&#8810;</span></a> \n"; //Link to first page
		}
		if($offset > 0)
		{
			$paging .= "<a href='$_SERVER[PHP_SELF]?offset=$prev'><span class='pageLink'>&lt;</span></a> \n";//Link to previous page
		}

		//Pages within range
		for($i = (0 + $j); $i < $num_page && $i < $numbers + $j; $i++)
		{
			$page_link = $i * $limit;
			if($i*$limit == $offset)
			{
				$paging .= "<span id='pageActive'>" . ($i+1) . "</span> \n";
			}
			else
			{
				$paging .= "<a href='$_SERVER[PHP_SELF]?offset=$page_link$currentUrl'><span class='pageLink'>" . ($i+1) . "</span></a> \n";
			}
		}
		if($nrOfRows > $offset + $limit)
		{
			$paging .= "<a href='$_SERVER[PHP_SELF]?offset=$next$currentUrl'><span class='pageLink'>&gt;</span></a> \n";//Link to next page
		}
		if($num_page > $numbers && $cur_page <= $num_page-ceil($numbers/2))
		{
			$paging .= "<a href='$_SERVER[PHP_SELF]?offset=".($num_page-1)*$limit."$currentUrl'><span class='pageLink'> &#8811;</span></a> \n";//Link to last page
		}
	}
	return $paging;
}

function presentEvent($username, $eventObject)
{
	$events = $eventObject->getWeeklyEvents();
	$regUsers = $eventObject->fetchAllRegistered();
	$text = "";
	for ($i=0;$i<7;$i++)
	{
		$values = array('date' => date("Y-m-d", time()+($i * 86400)));
		$nrOfRegistered = $eventObject->getNumberOfRegisteredByValue($values);
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
		$text .= "<span class='runner'>$nrOfRegistered<img src='img/runner.png' alt='runner'></span></h4>";

		if ($_SESSION["highlighted"] == $i)
		{
			foreach ($events as $key)
			{
				if ($key->eventDate == date("Y-m-d", time()+($i * 86400)))
				{
					// Get registered users to event
					$registeredUsersTable = "<table class='regTable'><tr><th>Anmälda</th><th>Kommentar</th>";
					if($key->bus == 1)
					{
						$registeredUsersTable .= "<th colspan='2'>Buss</th></tr>";
					}
					$values = array("eventID" => $key->id);
					$registered = false;

					if($regUsers != null)
					{
						foreach($regUsers as $regUser)
						{
							if($regUser->eventID == $key->id)
							{
								$registeredUsersTable .= "<tr class='regTableRow'><td class='regTableName'>" . $regUser->name .
								"</td><td class='regTableComment'>" . substr($regUser->comment, 0, 140) . "</td>";
								if($key->bus == 1)
								{
									$registeredUsersTable .= "<td class='regTableBus'>" . $regUser->bus . "</td>";
								}

								$userID = isset($_SESSION["uid"]) ? $_SESSION["uid"] : false;
								if ($regUser->userID === $userID)
								{
									$registeredUsersTable .= "<td class='regTableDel'><a href='?r=".$regUser->id."'><img src='img/cross.png' width='18px' height='18px'></a></td>";
									$registered = true;
								}
								else
								{
									$registeredUsersTable .= "<td class='regTableDel'></td>";
								}
								$registeredUsersTable .= "</tr>";
							}
						}
					}

					$registeredUsersTable .= "</table>";

					$text .=
						"<div class='eventPost'>
							<div class='eventHeader'>
								<span class='eventName'>" .$key->eventName . "</span>
								<span class='eventTime'>" .$key->startTime. "</span>
							</div>
							<span class='eventInfo'>" .$key->info. "</span>
						</div>";

					$text .=
						"<form method='POST' action='index.php'>
							<input type='hidden' name='eventID' value=" . $key->id . ">
							<input type='hidden' name='date' value=" . date("Y-m-d", time()+($i * 86400)) . ">";

					if(!$registered)
					{
						$text .= "<input type='text' class='regInput' name='comment' placeholder='Kommentar'>";
						if($key->bus == 1 )
						{
							$text .= "<label class='busLabel'>Bussplats</label><input type='checkbox' name='bus' value='Ja' checked><br>";
						}
						else
						{
							$text .= "<br>";
						}

						$text .= "<button type='submit' class='btn btn-primary regInput' name='register' value='Anmäl'>Anmäl</button>";
					}

					$text .="</form>";
					if(count($regUsers) > 0)
					{
						$text .= $registeredUsersTable;
					}
					$text .=  "<hr>";
				}
			}
		}
		else
		{
			foreach ($events as $key)
			{
				if ($key->eventDate == date("Y-m-d", time()+($i * 86400)))
				{
					$text .= "<li style='padding-left:5%; list-style-type: none;'><span style='padding-left:2%;' >".$key->eventName ."</span><br/></li>";
				}
			}
		}
	}
	return $text;
}

function makeLinks($text)
{
	//Makes clickable links
	$text = preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1" target="_blank">$1</a>', $text);
	return $text;
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
		preg_match("/\?.*/", $_SERVER["HTTP_REFERER"], $result);
		$extension = implode($result);
	}
	catch(Exception $e)
	{
		$extension = $e;
	}
	return $extension;
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
	if(isset($_COOKIE["rememberme_olacademy"]))
	{
		$user->getUserByCookie();
	}
	else if(isset($_POST["login"]) && !( isset($_SESSION["uid"]) && isset($_SESSION["username"]) ) )
	{
		$email = strip_tags($_POST["email"]);

		if(!$user->login($email,$_POST["passwd"]))
		{
			populateError("Fel lösenord eller email <a href='user.php?renew'>Glömt lösenord ?</a>");
		}
		else
		{
			header("location: ". $_SERVER["PHP_SELF"]);
		}
	}
	else if(isset($_POST["Registera"]))
	{
		header("location: user.php");
	}

	if(isset($_SESSION["uid"]))
	{
		$form = "<form method='post' class='navbar-form navbar-right'><a href='user.php'>" . $_SESSION["username"] . "</a>&nbsp;&nbsp;&nbsp;<button type='submit' class='btn btn-primary' name='logout'>Logout</button></form>";
		if(isset($_POST["logout"]))
		{
			$user->logout();
			header("location: index.php");
		}
	}
	else
	{
		$form = $user->getLoginForm();
	}

	return $form;
}
function registerUserToEvent($user, $eventObject)
{

	if (isset($_POST["register"]))
	{
		if (! $user->isLoggedIn())
		{
			populateError("Du måste vara inloggad för att kunna anmäla dig.");
		}
		else
		{

			if (validateIntPOST("eventID"))
			{
				$userId = isset($_SESSION["uid"]) ? $_SESSION["uid"] : false;
				$username = isset($_SESSION["username"]) ? $_SESSION["username"] : "";

				$eventId = $_POST["eventID"];
				$bus = isset($_POST["bus"]) ? "Ja" : "Nej";
				$comment = isset($_POST["comment"]) ? makeLinks($_POST["comment"]) : "";

				$conditions = array('userID' => $userId, 'eventID' => $eventId);
				$res = $eventObject->getRegisteredByValue($conditions);

				if ($res == null)
				{
					$params = array('userID' => $userId, 'name' => $username, 'date' => $_POST["date"], 'comment' => $comment, 'bus' => $bus, 'eventID' => $eventId);
					$eventObject->registerUserToEvent($params);
				}
			}
		}
	}
}

function displayErrorMessage($message)
{
	return "<div class='youShallNotPassDiv'>
	<img class='youShallNotPassPicture' src='img/Error.gif'/>
	<div class='youShallNotPassDivP'><p>Det blev något fel: </p> <p style='color:red'>$message </p></div>
	</div>";
}


/* Log caught exceptions
 * In my opinion it is good to catch
 * some exception and save them inside log file.
 * In that case user will not be able to see
 * if s/he generated an unexpected error.
 */

function displayError()
{
	if(strlen($_SESSION["error"]) > 1)
	{
		echo $_SESSION["error"];
		$_SESSION["error"] = "";
	}
}

function populateError($message)
{
	if(($_SESSION["error"]) == "")
	{
		$_SESSION["error"] = "<pre class='error'>$message</pre>";
	}
}

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

function validateIntGET($value)
{
	if(isset($_GET[$value]) && is_numeric($_GET[$value]))
	{
		return true;
	}
	return false;
}

function validateStringGET($value)
{
	if(isset($_GET[$value]) && strlen($_GET[$value]) > 1)
	{
		return true;
	}
	return false;
}

function validateIntPOST($value)
{
	if(isset($_POST[$value]) && is_numeric($_POST[$value]))
	{
		return true;
	}
	return false;
}

function validateStringPOST($value)
{
	if(isset($_POST[$value]) && strlen($_POST[$value]) > 1)
	{
		return true;
	}
	return false;
}

function getUrlPath()
{
	return $_SERVER["PHP_SELF"] . "?" . $_SERVER["QUERY_STRING"];
}
?>
