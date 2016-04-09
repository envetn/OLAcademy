<?php
$pageId = "admin";
$pageTitle = " - Admin";
include ("include/header.php");
$eventObject = new EventObject();
$newsObject = new NewsObject();

$privilege = $user->getUserprivilege();

if (isset($_SESSION["Previous_page"]))
{
	unset($_SESSION["Previous_page"]);
}

function tryToEditUser($user)
{
	if (validateIntPOST("userId") && validateIntPOST("privilege") )
	{
		try
		{
			$privilege = (int) $_POST["privilege"];
			$id = (int)$_POST["userId"];
			return $user->updateUsersPrivilege($privilege, $id);
		}
		catch (Exception $e)
		{
			//logError("< " . $_SESSION['uid'] . "  tryToEditUser > Error: " . $e . "\n");
			dump($e);
			return false;
		}
	}
}

function tryToRemoveEvent($eventObject)
{
	if (isset($_POST['eventId']) && is_numeric($_POST['eventId']))
	{
		try
		{
			$id = $_POST['eventId'];
			return $eventObject->removeEventAndRegisteredById($id);
		}
		catch (Exception $e)
		{
			//logError("< " . $_SESSION['uid'] . "  tryToRemoveEvent > Error: " . $e . "\n");
			dump($e);
			return false;
		}
	}
}

function tryToRemoveUser($user)
{
	if (validateIntPOST("userId"))
	{
		try
		{
			return $user->removeSingleEntryById($condition = array("id" => (int) $_POST["userId"]));
		}
		catch (Exception $e)
		{
			logError("< " . $_SESSION['uid'] . "  tryToRemoveUser > Error: " . $e . "\n");
			return false;
		}
	}
}

function getTableTitleOfPosts($newsObject)
{
    $offset = isset($_GET['offset']) && is_numeric($_GET['offset']) ? $_GET['offset'] : 0;
    $limit = 20;

    $news = "<a href='news.php?action=Lägg+till'> < Lägg till > </a><br/><form method='get'><input type='hidden' name='c' value='0'/><input type='text' name='search' placeholder='Sök Nyhet'/><button class='btn btn-primary'>Sök </button></form>";
    if (validateStringGET("search"))
    {
        $news .= searchForNews($_GET["search"], $newsObject);
    }

    $orderby = validateStringGET("sort") ?  $_GET["sort"] : "added";
    $res = $newsObject->fetchEntryWithOffset($offset, $limit, $orderby);
	
	$news .= "<table class='tableContent'><tr class='admin_rowHead'>
	    <th><a href='admin.php?c=0&sort=title'> Title &#8595;</a> </th>
	    <th><a href='admin.php?c=0&sort=author'> Av &#8595;</a> </th>
	    <th><a href='admin.php?c=0&sort=added'> Tillagd &#8595;</a> </th>
	    <th>Ta bort</th></tr>";
	foreach ( $res as $row )
	{
	    $news .= getNewsForm($row);
	}
	$news .= "</table>";
	$nrOfRows = $newsObject->countAllRows();
	$news .= "<div class='paging_div'>" . paging($limit, $offset, $nrOfRows, 5, "&c=0") . "</div>";

	return $news;
}

function getNewsForm($row)
{
    $title = $row->title;
    if (strlen($title) > 20)
    {
        $title = substr($title, 0, 20) . " ...";
    }
    if (strlen($title) > 20)
    {
        $title = substr($title, 0, 20) . " ...";
    }
    
    $news = "
        <tr class='admin_rowContent'>
            <td><a class='admin_news_remove' href='news.php?offset=0&p=" . $row->id . "'><span>" . $title . "</span></a></td>
            <td><span>" . $row->author . "</span></td>
            <td>" . $row->added . "</td>
            <td>
                <a class='admin_news_remove' href='admin.php?r=" . $row->id . "'><img src='img/cross.png' width=18px height=18px></a>
                <a class='admin_news_remove' href='news.php?action=edit&id=" . $row->id . "'><img src='img/edit.png' width=18px height=18px></a>
            </td>
        </tr>";
    return $news;
}

function searchForNews($search, $newsObject)
{
    if (strlen($search) > 1)
    {
        $search = strip_tags($search);
        $condition = array("title" => $search);

        $newsObject->useWildCard();
        $res = $newsObject->fetchAllEntriesByValue($condition, null, true);
        if($res != null)
        {
            $result = "<table class='tableContent' id='searchResult'><tr class='admin_rowHead'><th class=''>Title</th><th>Av</th><th>Tillagd</th><th>Ta bort</th></tr>";
            foreach($res as $row)
            {
                $result .= getNewsForm($row);
            }

            return $result . "</table>";
        }
        populateError("Nyhet hittades inte");
        return null;
    }
    populateError("Fyll i sökfältet.");
}

function getTableEvents($eventObject)
{
	if (isset($_GET["sort"]) || isset($_GET['showAll']) )
	{
		$orderby = validateStringGET("sort") ? $_GET["sort"] : "eventDate";
		$res = $eventObject->fetchAllEntries($orderby);
	}
	else
	{
		$res = $eventObject->getWeeklyEvents();
	}

	$htmlEvents = "<h3 id='tableHead'>Veckans träningar</h3>
			<a href='event.php?a=1'> < Lägg till > </a>
			<a href='admin.php?c=1&showAll=true'> < Visa alla > </a>
			<table class='tableContent'>
     		<tr class='admin_rowHead'>
        		<th>Event</th><th>Info</th>
				<th><a href='admin.php?c=1&sort=startTime'> När &#8595;</a> </th>F
				<th><a href='admin.php?c=1&sort=eventDate'> Datum &#8595;</a> </th>
				<th>Anmälda</th>
				<th><a href='admin.php?c=1&sort=reccurance'> Återkommande &#8595;</a></th>
				<th><a href='admin.php?c=1&sort=bus'> Buss &#8595;</a></th>
				<th>Edit</th>
    		</tr>";
	foreach ( $res as $events )
	{
		$name = $events->eventName;
		$info = $events->info;
		if (strlen($info) > 40)
		{
			$info = substr($info, 0, 40) . "<a href='event.php?event=$events->id'> ... </a>";
		}
		if (strlen($name) > 40)
		{
			$name = substr($name, 0, 40) . "<a href='event.php?event=$events->id'> ... </a>";
		}
		$values = array('eventID' => $events->id);
		$registered = $eventObject->getNumberOfRegisteredByValue($values);
		$reccurance = $events->reccurance == '1' ? "Ja" : "Nej";
		$bus = $events->bus == '1' ? "Ja" : "Nej";
		$htmlEvents .= "<tr class='admin_rowContent'>
                            <td><span>" . $name . "</span></td>
                            <td><span>" . $info . "</span></td>
                            <td><span>" . $events->startTime . "</span></td>
                            <td><span>" . $events->eventDate . "</span></td>
                            <td><span><a href='admin.php?c=3&event=" . $events->id . "'>" . $registered . " - Visa</a></span></td>
                            <td><span>" . $reccurance . "</span></td>
                            <td><span>" . $bus . "</span></td>
                            <td>
                            	<form method='post' class='admin_form_event'>
                            	<input type='hidden' name='eventId' value='" . $events->id . "' />
                            	<input type='image' src='img/cross.png' border='0' width=18px height=18px alt='Submit'  name='removeEvent_1' value='" . $events->id . "'/>
                                <a class='admin_news_remove' href='event.php?event=" . $events->id . "'><img src='img/edit.png' width=18px height=18px alt='remove'/></a>
                                </form>
                            </td>
                        </tr>";
	}
	return $htmlEvents . "</table>";
}

function getTableRegisteredUsers($eventObject)
{
	if (validateIntGET("event"))
	{
		$eventId = (int) $_GET['event'];
		$values = array('id' => $eventId);

		$event = $eventObject->fetchSingleEntryByValue($values);

		$regValues = array('eventId' => $eventId);
		$registeredUsers = $eventObject->getRegisteredByValue($regValues);
		if ($event != null)
		{
			$registeredUsersTable = "<h3 id='tableHead'>Anmälda till : $event->eventName - $event->eventDate </h3></a>";
			$registeredUsersTable .= '<table class="tableContent"><th>Anmälda</th><th>Bussplats</th><th>Kommentar</th>';
			if ($registeredUsers != null)
			{

				foreach ( $registeredUsers as $regUser )
				{
					$registeredUsersTable .= "<tr class='admin_rowContent'><td>" . $regUser->name . "</td><td>" . $regUser->bus . "</td><td>" . $regUser->comment . "</td><td>";
					$registeredUsersTable .= "<a href='?r=$regUser->id' ><img src='img/cross.png' width=18px height=18px></a>";
					$registeredUsersTable .= "</td></tr>";
				}
			}
			$registeredUsersTable .= "</table><hr>";
			return $registeredUsersTable;
		}
	}
	return "";
}

function searchForUser($search, $user)
{
	if (strlen($search) > 1)
	{
		$name = strip_tags($search);
		$res = $user->fetchUserByName($name);
		if ($res != null)
		{
			$result = "<table class='tableContent' id='searchResult'><tr class='admin_rowHead'><th>Namn</th><th>email</th><th>Rättighet</th><th>Registrerad</th><th>Edit</th><tr>";
			$result .= userForm($res);

			return $result . "</table>";
		}
		populateError("Användare: " . $search . " hittades inte");
	}
	else
	{
		populateError("Fyll i sökfältet.");
	}
}

function getTableUsers($user)
{
	$res = $user->fetchUserEntries();
	$htmlUsers = "<h3 id='tableHead'>Användare</h3><a href='user.php'> < Lägg till > </a> <form method='get'><input type='hidden' name='c' value='2'/><input type='text' name='search' placeholder='Sök användare'/><button class='btn btn-primary'>Sök </button></form>";
	if (validateStringGET("search"))
	{
		$htmlUsers .= searchForUser($_GET["search"], $user);
	}
	// Maybe not show all users?
	$htmlUsers .= "<table class='tableContent'>
    <tr class='admin_rowHead'>
        <th>Namn</th><th>email</th><th>Rättighet</th><th>Registrerad</th><th>Edit</th>
    <tr>";
	foreach ( $res as $key )
	{
		$htmlUsers .= userForm($key);
	}
	return $htmlUsers . "</table>";
}

function userForm($user)
{
	//<a href='user.php?user=id'>
	$userTable = "<tr class='admin_rowContent'>
                            <td><span>" . $user->name . "</span></td>
                            <td><span>" . $user->email . "</span></td>";
	$userTable .= generateSelect($user->Privilege);
	$userTable .= "<td>" . $user->regDate . "</td>
                            <td>
								<form method='post'>
								<input type='hidden' name='userId' value='" . $user->id . "' />
								<input type='image' src='img/cross.png' border='0' width=18px height=18px alt='Submit'  name='removeUser_1' value='Click me'>
                                <input type='image' src='img/edit.png'  border='0' width=18px height=18px alt='Submit'  name='editUser_2' value='Click me2'>
								</form>
                            </td>
                        </tr>";
	return $userTable;
}

function generateSelect($privilege)
{
	$html = "<td> <select class='dropdownPrivilege' name='privilege'>";
	switch ($privilege)
	{
		case 2 :
			$html .= "<option value='2' selected='selected' > Admin </option> <option value='1'> Användare </option><option value='0' > Cockatrice </option>";
			break;

		case 1 :
			$html .= "<option value='2' > Admin </option> <option value='1' selected='selected' > Användare </option><option value='0' > Cockatrice </option>";
			break;

		case 0 :
			$html .= "<option value='2' > Admin </option> <option value='1'> Användare </option><option value='0' selected='selected'  > Cockatrice </option>";
			break;
	}
	$html .= " 	</select> </td>";
	return $html;
}

function getTablesAndValidateGET($newsObject, $htmlAdmin, $eventObject, $user) // need a better solution than this
{
	if (validateIntGET("c"))
	{
		$choice = strip_tags($_GET['c']);
		switch ($choice)
		{
			case 0 :
				$htmlAdmin = getTableTitleOfPosts($newsObject);
				break;
			case 1 :
				$htmlAdmin = getTableEvents($eventObject);
				break;
			case 2 :
				$htmlAdmin = getTableUsers($user);
				break;
			case 3 :
				$htmlAdmin = getTableRegisteredUsers($eventObject);
				break;
			default :
				$htmlAdmin = "<h4 id='infoHead'> Välj från menun till höger </h4>";
				break;
		}
	}
	else
	{
		$htmlAdmin = "<h4 id='infoHead'> Välj från menun till höger </h4>";
	}
	return $htmlAdmin . "</div>";
}

if ($privilege === "2")
{
	$sideBar = "<div id='sidebar'><ul>
                <li><a href='" . $_SERVER['PHP_SELF'] . "?c=0'>Nyheter</a></li>
                <li><a href='" . $_SERVER['PHP_SELF'] . "?c=1'>Träningar</a></li>
                <li><a href='" . $_SERVER['PHP_SELF'] . "?c=2'>Användare</a></li>
               </ul></div>";
	$htmlAdmin = "<div id='content'>";

	if (validateIntPOST("removeUser_1_x"))
	{
		if (tryToRemoveUser($user))
		{
			$htmlAdmin .= "<h4 id='infoHead'>Användare borttagen </h4>";
		}
		else
		{
			$htmlAdmin .= "<h4 id='infoHead'> ... </h4>";
		}
	}
	else if (validateIntPOST("editUser_2_x"))
	{
		if (tryToEditUser($user))
		{
			$htmlAdmin .= "<h4 id='infoHead'> Användare uppdaterad</h4>";
		}
		else
		{
			$htmlAdmin .= "<h4 id='infoHead'>Det blev något fel, användaren uppdaterades inte </h4>";
		}
	}
	else if (validateIntPOST("removeEvent_1_x"))
	{
		if (tryToRemoveEvent($eventObject))
		{
			$htmlAdmin .= "<h4 id='infoHead'> Träning borttagen</h4>";
		}
		else
		{
			$htmlAdmin .= "<h4 id='infoHead'>Det blev något fel.</h4>";
		}
	}

	$htmlAdmin .= getTablesAndValidateGET($newsObject, $htmlAdmin, $eventObject, $user);
	displayError();
	echo "<div class='row clearFix'>";
	echo "<div style='clear:both; overflow: hidden;'>";
	echo $sideBar;
	echo $htmlAdmin;
	echo "</div>";
}
else
{
	header("Location: index.php");
}
include ("include/footer.php");