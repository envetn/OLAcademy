<?php
$pageId = "admin";
$pageTitle = " - Admin";
include ("include/header.php");
$eventObject = new EventObject();
$newsObject = new NewsObject();

$privilege = $user->getUserprivilege();

if (isset($_SESSION['Previous_page']))
{
	unset($_SESSION['Previous_page']);
}

function tryToEditUser($user)
{
	if (isset($_POST['userId']) && is_numeric($_POST['userId']))
	{
		try
		{
			$userId = $_POST['userId'];
			$privilege = $_POST['privilege'];
			return $user->updateUsersPrivilege($privilege, $userId);
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
			return $eventObject->removeSingleEntryById($id);
		}
		catch (Exception $e)
		{
			// 			logError("< " . $_SESSION['uid'] . "  tryToRemoveEvent > Error: " . $e . "\n");
			dump($e);
			return false;
		}
	}
}

function tryToRemoveUser($user)
{
	if (isset($_POST['userId']) && is_numeric($_POST['userId']))
	{
		try
		{
			$id = $_POST['userId'];
			return $user->removeSingleEntryById($id);
		}
		catch (Exception $e)
		{
			//logError("< " . $_SESSION['uid'] . "  tryToRemoveUser > Error: " . $e . "\n");
			dump($e);
			return false;
		}
	}
}

function getTableTitleOfPosts($newsObject)
{
	$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
	$limit = 20;
	$res = $newsObject->fetchEntryWithOffset($offset, $limit);

	$news = "<h3 class='tableHead'>Nyheter</h3><a href='news.php?action=Lägg+till'> < Lägg till > </a><table class='tableContent'>
    <tr class='admin_rowHead'>
        <th class=''>Title</th><th>Av</th><th>Tillagd</th><th>Ta bort</th>
    <tr>";
	foreach ( $res as $row )
	{
		$title = $row->title;
		$author = $row->author;
		if (strlen($title) > 20)
		{
			$title = substr($title, 0, 20) . " ...";
		}
		if (strlen($title) > 20)
		{
			$title = substr($title, 0, 20) . " ...";
		}

		$news .= "
                    <tr class='admin_rowContent'>
                        <td><span>" . $title . "</span></td>
                        <td><span>" . $row->author . "</span></td>
                       	<td>" . $row->added . "</td>
                        <td>
                            <a class='admin_news_remove' href='admin.php?r=" . $row->id . "'><img src='img/cross.png' width=18px height=18px></a>
                            <a class='admin_news_remove' href='news.php?action=edit&id=" . $row->id . "'><img src='img/edit.png' width=18px height=18px></a>
                            <a class='admin_news_remove' href='news.php?offset=0&p=" . $row->id . "'>Show</a>
                        </td>
                    </tr>";
	}
	$news .= "</table>";
	$nrOfRows = $newsObject->countAllRows();
	$news .= "<div class='paging_div'>" . paging($limit, $offset, $nrOfRows, 5, "&c=0") . "</div>";

	return $news;
}

function getTableEvents($eventObject)
{
	if (isset($_GET['showAll']))
	{
		$res = $eventObject->fetchAllEntries("eventDate");
	}
	else
	{
		$res = $eventObject->getWeeklyEvents();
	}

	$htmlEvents = "<h3 id='tableHead'>Veckans träningar</h3><a href='event.php?a=1'> < Lägg till > </a><a href='" . getUrlPath() . "&showAll=true'> < Visa alla > </a><table class='tableContent'>
     <tr class='admin_rowHead'>
        <th>Event</th><th>Info</th><th>När</th><th>Datum</th><th>Anmälda</th><th>Återkommande</th><th>Buss</th><th>Edit</th>
    </tr>";
	foreach ( $res as $events )
	{
		$info = $events->info;
		if (strlen($info) > 40)
		{
			$info = substr($info, 0, 40) . "<a href='event.php?s=$events->id'> ... </a>";
		}
		$values = array('id' => $events->id);
		$registered = $eventObject->getNumberOfRegisteredByValue($values);
		$reccurance = $events->reccurance == '1' ? "Ja" : "Nej";
		$bus = $events->bus == '1' ? "Ja" : "Nej";
		$htmlEvents .= "<form method='post'>
							<tr class='admin_rowContent'>
        					<input type='hidden' name='eventId' value='" . $events->id . "' />
                            <td><span>" . $events->eventName . "</span></td>
                            <td><span>" . $info . "</td>
                            <td><span>" . $events->startTime . "</span></td>
                            <td><span>" . $events->eventDate . "</span></td>
                            <td><span><a href='admin.php?c=3&event=" . $events->id . "'>" . $registered . " - Visa</a></span></td>
                            <td><span>" . $reccurance . "</span></td>
                            <td><span>" . $bus . "</span></td>
                            <td><span>
                                <input type='image' src='img/cross.png' border='0' width=18px height=18px alt='Submit'  name='removeEvent_1' value='" . $events->id . "'/>
                                <a class='admin_news_remove' href='event.php?e=" . $events->id . "'><img src='img/edit.png' width=18px height=18px></a>
                            </td></span>
                        </tr>
                       </form>";
	}
	return $htmlEvents . "</table>";
}

function getTableRegisteredUsers($eventObject)
{
	if (isset($_GET['event']) && is_numeric($_GET['event']))
	{

		$eventId = $_GET['event'];
		$values = array('id' => $eventId);

		$event = $eventObject->fetchSingleEntryByValue($values);

		$regValues = array('eventID' => $eventId);
		$registeredUsers = $eventObject->getRegisteredByValue($regValues);
		if ($event != null)
		{
			$registeredUsersTable = "<h3 id='tableHead'>Anmälda till : $event->eventName - $event->eventDate </h3></a>";
			$registeredUsersTable .= '<table class="tableContent"><th>Anmälda</th><th>Bussplats</th><th>Kommentar</th>';

			foreach ( $registeredUsers as $regUser )
			{
				$registeredUsersTable .= "<tr class='admin_rowContent'><td>" . $regUser->name . "</td><td>" . $regUser->bus . "</td><td>" . $regUser->comment . "</td><td>";
				$registeredUsersTable .= "<a href='?r=$regUser->id' ><img src='img/cross.png' width=18px height=18px></a>";
				$registeredUsersTable .= "</td></tr>";
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
		$res = $user->fetchUserByName($search);
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
	if (isset($_GET['search']))
	{
		$htmlUsers .= searchForUser($_GET['search'], $user);
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
	$userTable = "<form method='post'>
						<tr class='admin_rowContent'>
                            <input type='hidden' name='userId' value='" . $user->id . "' />
                            <td><span><a href='user.php?user=id'>" . $user->name . "</a></span></td>
                            <td><span>" . $user->email . "</span></td>";
	$userTable .= generateSelect($user->Privilege);
	$userTable .= "<td>" . $user->regDate . "</td>
                            <td>
								<input type='image' src='img/cross.png' border='0' width=18px height=18px alt='Submit'  name='removeUser_1' value='Click me'>
                                <input type='image' src='img/edit.png'  border='0' width=18px height=18px alt='Submit'  name='editUser_2' value='Click me2'>
                            </td>
                        </tr></form>";
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
	if (isset($_GET['c']) && is_numeric(($_GET['c'])))
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

	if (isset($_POST['removeUser_1_x'])) // Where does x come from?
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
	else if (isset($_POST['editUser_2_x']))
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
	else if (isset($_POST['removeEvent_1_x']))
	{
		if (tryToRemoveEvent($eventObject))
		{
			$htmlAdmin .= "<h4 id='infoHead'> Träning borttagen</h4>";
		}
		else
		{
			$htmlAdmin .= "<h4 id='infoHead'>Det blev något fel, träniongen uppdaterades inte </h4>";
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
include("include/footer.php");