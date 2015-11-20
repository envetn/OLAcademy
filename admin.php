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
			dump($_POST);
			$userId = $_POST['userId'];
			$privilege = $_POST['privilege'];
			return $user->updateUsersPrivilege($privilege,$userId);

		}
		catch ( Exception $e )
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
		catch ( Exception $e )
		{
			logError("< " . $_SESSION['uid'] . "  tryToRemoveEvent > Error: " . $e . "\n");
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
		catch ( Exception $e )
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
	
	$news = "<h3 id='tableHead'>Nyheter</h3><a href='news.php?action=Lägg+till'> < Lägg till > </a><table class='tableContent'>
    <tr>
        <th>Title</th><th>Av</th><th>Tillagd</th><th>Ta bort</th>
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
                    <tr>
                        <td><span>" . $title . "</span></td>
                        <td><span>" . $row->author . "</span></td>
                       	<td>" . $row->added . "</td>
                        <td>
                            <a class='admin_news_remove' href='admin.php?r=" . $row->id . "'><img src='img/cross.png' width=18px height=18px></a>
                            <a class='admin_news_remove' href='news.php?e=" . $row->id . "'><img src='img/edit.png' width=18px height=18px></a>
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
    <tr>
        <th>Event</th><th>Info</th><th>När</th><th>Datum</th><th>Anmälda</th><th>Återkommande</th><th>Buss</th><th>Edit</th>
    </tr>";
	foreach ( $res as $events )
	{
		$info = $events->info;
		if (strlen($info) > 40)
		{
			$info = substr($info, 0, 40) . "<a href='event.php?s=$events->id'> ... </a>";
		}
		$registered = $eventObject->getNrOfRegisteredById($events->id);
		$reccurance = $events->reccurance == '1' ? "Ja" : "Nej";
		$bus = $events->bus == '1' ? "Ja" : "Nej";
		$htmlEvents .= "<form method='post'>
							<tr>
        					<input type='hidden' name='eventId' value='" . $events->id . "' />
                            <td>" . $events->eventName . "</td>
                            <td>" . $info . "</td>
                            <td>" . $events->startTime . "</td>
                            <td>" . $events->eventDate . "</td>
                           	<td><a href='admin.php?c=3&event=" . $events->id . "'>" . $registered . " - Visa</a></td>
                            <td>" . $reccurance . "</td>
                            <td>" . $bus . "</td>
                            <td>
                                <input type='image' src='img/cross.png' border='0' width=18px height=18px alt='Submit'  name='removeEvent_1' value='" . $events->id . "'/>
                                <a class='admin_news_remove' href='event.php?e=" . $events->id . "'><img src='img/edit.png' width=18px height=18px></a>
                            </td>
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
		$values = array('variable' => 'id', 'value' => $id);
		$event = $eventObject->fetchSingleEntryByValue($values);
		
		$registeredUsers = $eventObject->getRegisteredById($eventId);
		if ($event != null)
		{
			$registeredUsersTable = "<h3 id='tableHead'>Anmälda till : $event->eventName - $event->eventDate </h3></a>";
			$registeredUsersTable .= '<table class="tableContent"><th>Anmälda</th><th>Bussplats</th><th>Kommentar</th>';
			
			foreach ( $registeredUsers as $regUser )
			{
				$registeredUsersTable .= "<tr><td>" . $regUser->name . "</td><td>" . $regUser->bus . "</td><td>" . $regUser->comment . "</td><td>";
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
	$res = $user->fetchUserByName($search);
	$result = "<table class='tableContent'><tr><th>Namn</th><th>email</th><th>Rättighet</th><th>Registrerad</th><th>Edit</th><tr>";
	foreach ( $res as $key )
	{
		$result .= "<form method='post'><tr>
                            <input type='hidden' name='userId' value='" . $key->id . "' />
                            <td><a href='user.php?user=id'>" . $key->name . "</a></td>
                            <td>" . $key->email . "</td>";
		$result .= generateSelect($key);
		$result .= "<td>" . $key->regDate . "</td>
                            <td>
								<input type='image' src='img/cross.png' border='0' width=18px height=18px alt='Submit'  name='removeUser_1' value='Click me'>
                                <input type='image' src='img/edit.png'  border='0' width=18px height=18px alt='Submit'  name='editUser_2' value='Click me2'>
                            </td>
                        </tr></form>";
	}
	return $result . "</table>";
}

function getTableUsers($user)
{
	
	$res = $user->fetchUserEntries();
	$htmlUsers = "<h3 id='tableHead'>Användare</h3><a href='createUser.php'> < Lägg till > </a> <form method='get'><input type='hidden' name='c' value='2'/><input type='text' name='search' placeholder='Sök användare'/><button class='btn btn-primary'>Sök </button></form>";
	if (isset($_GET['search']))
	{
		$htmlUsers .= searchForUser($_GET['search'], $user);
	}
	// Maybe not show all users?
	$htmlUsers .= "<table class='tableContent'>
    <tr>
        <th>Namn</th><th>email</th><th>Rättighet</th><th>Registrerad</th><th>Edit</th>
    <tr>";
	foreach ( $res as $key )
	{
		$htmlUsers .= "<form method='post'><tr>
                            <input type='hidden' name='userId' value='" . $key->id . "' />
                            <td><a href='user.php?user=id'>" . $key->name . "</a></td>
                            <td>" . $key->email . "</td>";
		$htmlUsers .= generateSelect($key);
		$htmlUsers .= "<td>" . $key->regDate . "</td>
                            <td>
								<input type='image' src='img/cross.png' border='0' width=18px height=18px alt='Submit'  name='removeUser_1' value='Click me'>
                                <input type='image' src='img/edit.png'  border='0' width=18px height=18px alt='Submit'  name='editUser_2' value='Click me2'>
                            </td>
                        </tr></form>";
	}
	return $htmlUsers . "</table>";
}

function generateSelect($user)
{
	$html = "<td> <select class='dropdownPrivilege' name='privilege'>";
	switch ($user->Privilege)
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
				$htmlAdmin = getTableRegisteredUsers( $eventObject);
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
			$htmlAdmin .= "<h4 id='infoHead'> User updated </h4>";
		}
		else
		{
			$htmlAdmin .= "<h4 id='infoHead'> zNot updated... </h4>";
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
			$htmlAdmin .= "<h4 id='infoHead'> Not removed... </h4>";
		}
	}
	
	$htmlAdmin .= getTablesAndValidateGET($newsObject, $htmlAdmin, $eventObject, $user);
	echo "<div class='row clearFix'>";
	echo "<div style='clear:both; overflow: hidden;'>";
	echo $sideBar;
	echo $htmlAdmin;
	echo "</div>";
}
else
{
	echo displayErrorMessage("BEGONE!!!");
}