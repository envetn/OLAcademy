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

function issetor(&$var, $default = false)
{
    return isset($var) ? $var : $default;
}

function presentPost($guestbookObject, $offset, $limit, $isAdmin)
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
                        <span class='guestbookDate'>" . $row->added . "</span>";
		if($isAdmin)
        {
        	$post .= "<a href='guestbook.php?r=".$row->id."'> <img src='img/cross.png' width=18px height=18px style='float:right'/></a>";
        }
        $post .= "</div>
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
		$content = $content = \Michelf\Markdown::defaultTransform($row->content);
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

function substrContent($content, $concatValue, $link = "")
{
	if(strlen($content) > $concatValue)
	{
		$http = '<a href="http';
		$strpos = strpos($content, $http);

		if($strpos != 0 && $strpos < $concatValue)
		{
			$concatValue = $strpos;

		}

		$subStrEnding = $link != ""
				? "<a href='$link'> Mer info</a>"
				: "...";
		$content =  substr($content, 0, $concatValue) . $subStrEnding;
	}
	return $content;
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

    $j = $numbers >= $num_page || $cur_page <= ceil($numbers/2)
    ? 0
    : $cur_page - ceil($numbers/2);
    if($cur_page > $num_page-ceil($numbers/2) && $num_page - $numbers > 0)
    {
    	$j = $num_page - $numbers;
    }

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
    $events = $eventObject->getEvents(date("Y-m-d"), date("Y-m-d", time()+6*86400));
    $highlightedDay = $_SESSION["highlighted"];
    $highlightedDayEvent = (isset($events[$highlightedDay])
    		? getHighlightedEvents($events[$highlightedDay])
    		: null);

    $eventPreview = getEventPreview($events);
    return createWeekCalendar($highlightedDayEvent, $highlightedDay, $eventPreview);
}

function createWeekCalendar($event, $highlighted, $preview)
{
    $output = "";
    $weekdays = array(1 => "Måndag", "Tisdag", "Onsdag", "Torsdag", "Fredag", "Lördag", "Söndag");
    $startDay = date("N");
    for($i = 0; $i < 7; $i++)
    {
        $currentDay = date("Y-m-d", time()+86400*$i);
        $output .= "<h4><a href='?highlighted=$currentDay'>";
        $output .= $weekdays[date("N", strtotime($currentDay))] . "</a></h4>";
        $output .= $currentDay == $highlighted ? $event : issetor($preview[$currentDay], "");
    }
    return $output;
}

function getHighlightedEvents($events)
{
    $html = "";
    foreach ($events as $event)
    {
        $eventData = $event['eventData'];
        $content = substrContent($eventData->info, 140, "calendar.php?event=" . $eventData->id);

        $html .= "
            <div class='eventPost'>
                <div class='eventHeader'>
                    <span class='eventName'>" .$eventData->eventName . "</span>
                    <span class='eventTime'>" .$eventData->startTime. "</span>
                </div>
                <span class='eventInfo'>" .$content. "</span>
            </div>
            <form method='POST' action='index.php'>
                <input type='hidden' name='eventID' value=" . $eventData->id . ">
                <input type='hidden' name='date' value=" . $eventData->eventDate . ">";

        $userIsRegistered = false;
        $userID = issetor($_SESSION["uid"]);
        if ($userID)
        {
            foreach($event['registered'] as $regUser)
            {
                if($regUser->userID === $userID)
                {
                    $userIsRegistered = true;
                }
            }
        }

        if(!$userIsRegistered)
        {
            $html .= "<input type='text' class='regInput' name='comment' placeholder='Kommentar'>";
            if($eventData->bus == 1 )
            {
                $html .= "<label class='busLabel'>Bussplats</label><input type='checkbox' name='bus' value='Ja' checked>";
            }
            $html .= "<br><button type='submit' class='btn btn-primary regInput' name='register' value='Anmäl'>Anmäl</button>";
        }

        $html .="</form>";
        $html .= count($event['registered']) > 0 ? getRegisteredUsersTable($event['registered'], $eventData->bus, $userID) : "";
        $html .=  "<hr/>";
    }
    return $html;
}

function getRegisteredUsersTable($regUsers, $bus, $userID)
{
    $html = "<table class='regTable'><tr><th>Anmälda</th><th>Kommentar</th>";
    if($bus == 1)
    {
        $html .= "<th colspan='2'>Buss</th></tr>";
    }

    foreach($regUsers as $regUser)
    {
        $html .= "<tr class='regTableRow'><td class='regTableName'>" . $regUser->name .
        "</td><td class='regTableComment'>" . substrContent($regUser->comment, 140) . "</td>";
        if($bus == 1)
        {
            $html .= "<td class='regTableBus'>" . $regUser->bus . "</td>";
        }

        $html .= "<td class='regTableDel'>";
        $html .= $regUser->userID === $userID ? "<a href='?r=".$regUser->id."'><img src='img/cross.png' width='18px' height='18px'></a>" : "";
        $html .="</td></tr>";
    }
    $html .= "</table>";
    return $html;
}

function getEventPreview($events)
{
    $eventPreview = array();
    foreach($events as $date => $day)
    {
        $eventPreview[$date] = "";
        foreach($day as $event)
        {
            $previewRegistered = count($event['registered']);
            $html = "
                <li class='register_preview'>
                    <span class='register_preview_content'>".$event['eventData']->eventName ." - ".$previewRegistered."
                       <img src='img/runner.png' alt='runner'/>
                    </span><br/>
                </li>";
            $eventPreview[$date] .= $html;
        }
    }
    return $eventPreview;
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

function showLoginLogout($user)
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
	        populateError("Fel lösenord eller email <a href='user.php?renew'>Glömt lösenord?</a>");
	    }
	    else
	    {
	        if($_SESSION["changePassword"] == 1)
	        {
	            header("location: user.php");
	        }
	        //header("location: ". $_SERVER["PHP_SELF"]);
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
                $lastname = isset($_SESSION["lastname"]) ? $_SESSION["lastname"] : "";
                $name = $username . " " . $lastname;
                $eventId = $_POST["eventID"];
                $bus = isset($_POST["bus"]) ? "Ja" : "Nej";
                $comment = isset($_POST["comment"]) ? makeLinks($_POST["comment"]) : "";

                $conditions = array('userID' => $userId, 'eventID' => $eventId);
                $res = $eventObject->getRegisteredByValue($conditions);

                if ($res == null)
                {
                    $params = array('userID' => $userId, 'name' => $name, 'date' => $_POST["date"], 'comment' => $comment, 'bus' => $bus, 'eventID' => $eventId);
                    $eventObject->registerUserToEvent($params);
                    header("location: ".$_SERVER['HTTP_REFERER']);
                }
            }
        }
    }
    else if(isset($_POST["unRegister"]))
    {
        if (! $user->isLoggedIn())
        {
            populateError("Du måste vara inloggad för att kunna avanmäla dig.");
        }
        else
        {
            if (validateIntPOST("eventID"))
            {
                $eventObject->unRegisterUserToEventByValue($_POST["eventID"]);
            }
        }
    }
    else if(isset($_POST["Edit"]))
    {
        $eventId = $_POST["eventID"];
        header("location: event.php?event=" . $eventId);
    }
    else if(isset($_POST["Remove"]))
    {
    	$eventId = $_POST["eventID"];
    	$eventObject->removeEventAndRegisteredById($eventId);
    	populateError("Träning borttagen");
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

function populateInfo($message)
{
    if(($_SESSION["info"]) == "")
    {
        $_SESSION["info"] = "<pre class='info'>$message</pre>";
    }
}

function displayInfo()
{
    if(strlen($_SESSION["info"]) > 1)
    {
        echo $_SESSION["info"];
        $_SESSION["info"] = "";
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
	echo "<pre>" . print_r($value, true) . "</pre>";
}

function isCaptchaValid()
{
    if (empty($_SESSION['captcha_code']) || $_SESSION['captcha_code'] != $_POST['captcha_code'])//strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0)
    //if ($_POST['captcha_code'] != 4)
    {
        return false;
    }
    else
    {
        return true;
    }
}

function getCaptchaForm()
{
    return '
        <td><img src="Captcha.php?rand="'.rand().'" id="captchaimg" ><br>
        <label for="message">Captcha :</label> <br> <input
        id="captcha_code" name="captcha_code" type="text"> <br> Generera ny bild <a href="javascript: refreshCaptcha();"> Refresh</a>
        </td><br/>';
    /*return '
    <div class="gb_fields"><label for ="captcha_code">2 + 2 = &nbsp;</label><input id="catpcha_code" name="captcha_code" type="text"></div>

    ';*/
}

function printIfContent($value)
{
    if(validateStringPOST($value))
    {
        return $_POST[$value];
    }
    return "";
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

function createRSSFeed()
{
    $xmlfile = fopen("rssfeed.xml", "w") or die("Unable to open file!");
    $content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<rss version=\"2.0\">
<channel> 
<title>OLAcademy RSS Feed</title>
<link>olacademy.se</link>
<description>Idrottsakademins OL-sektion</description>"; 
    $eventObject = new EventObject();
    $events = $eventObject->getEvents(date("Y-m-d"), date("Y-m-d", time()+2*86400));
    foreach ($events as $eventDays)
    {
	foreach ($eventDays as $event)
	{	
	    $event = $event['eventData'];
	    $content .= "
<item>
    <title>$event->eventDate - $event->eventName $event->startTime</title>
    <link>https://olacademy.se</link>
    <guid>https://olacademy.se/calendar.php?event=$event->id</guid>
    <pubDate>".date("r")."</pubDate>
    <description>$event->info</description>
</item>
";
	}
    }
    $content .= "
</channel>
</rss>
";

    fwrite($xmlfile, $content);
    fclose($xmlfile);
}
?>
