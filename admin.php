<style>

</style>
<?php
$pageTitle = " - Admin";
include("include/header.php");
$privilege  = getUserprivilege($db);
/*
 * Functions for the options
 */
function getTableTitleOfPosts($db)
{
    $sql = "SELECT title,id,author FROM news LIMIT 10";

    $res = $db->queryAndFetch($sql);
    $news = "<h3 id='tableHead'>Nyheter</h3><a href='news.php?new=Lägg+till'> < Lägg till > </a><table id='tableContent'>
    <tr>
        <th>Title</th><th>Av</th><th>Ta bort</th>
    <tr>";
    foreach($res as $row)
    {
        $title = $row->title;
        $author = $row->author;
        if(strlen($title) > 20)
        {
            $title =  substr($title,0, 20). " ...";
        }
        if(strlen($title) > 20)
        {
            $title =  substr($title,0, 20). " ...";
        }
        
        $news .= "
                    <tr>
                        <td><span>".$title."</span></td>
                        <td><span>".$row->author."</span></td>
                        <td>
                            <a class='admin_news_remove' href='admin.php?r=".$row->id."'><img src='img/cross.png' width=18px height=18px></a>
                            <a class='admin_news_remove' href='news.php?e=".$row->id."'><img src='img/edit.jpg' width=18px height=18px></a>
                            <a class='admin_news_remove' href='news.php?offset=0&p=".$row->id."'>Show</a>
                        </td>
                    </tr>";
    }
    $news .= "</table>";
    return $news;
}

function getTableEvents($db)
{
    $res = getEvents($db);
    $htmlEvents = "<h3 id='tableHead'>Veckans träningar</h3><a href='event.php?a=1'> < Lägg till > </a><table id='tableContent'>
    <tr>
        <th>Event</th><th>Info</th><th>När</th><th>Datum</th><th>Edit</th>
    <tr>";
    foreach($res as $event)
    {
    	$info = $event->info;
    	if(strlen($info) > 40)
		{
			$info =  substr($info,0, 40). "<a href='event.php?s=$event->id'> ... </a>";
		}
        $htmlEvents .= "<tr>
                            <td>".$event->eventName."</td>
                            <td>".$info."</td>
                            <td>".$event->startTime."</td>
                            <td>".$event->date."</td>
                            <td>
                                <a class='admin_news_remove' href='admin.php?r=".$event->id."'><img src='img/cross.png' width=18px height=18px></a>
                                <a class='admin_news_remove' href='event.php?e=".$event->id."'><img src='img/edit.jpg' width=18px height=18px></a>
                                <a class='admin_news_remove' href='event.php?offset=0&p=".$event->id."'>Show</a>
                            </td>
                        </tr>";
    }
    return $htmlEvents . "</table>";
}

function getTableUsers($db)
{
    $sql = "SELECT id,name,email,Privilege,regDate FROM users";
    $res = $db->queryAndFetch($sql);
    // Maybe not show all users?
    $htmlUsers = "<h3 id='tableHead'>Användare</h3><a href='createUser.php'> < Lägg till > </a><table id='tableContent'>
    <tr>
        <th>Namn</th><th>email</th><th>Rättighet</th><th>Registrerad</th><th>Edit</th>
    <tr>";
    foreach($res as $users)
    {
		$htmlUsers .= "<form method='post'><tr>
                            <input type='hidden' name='userId' value='".$users->id."' />
                            <td><input type='text' name='username' value='".$users->name."' /></td>
                            <td>".$users->email."</td>
                            <td><input type='text' name='privilege' value='".$users->Privilege."' /></td>
                            <td>".$users->regDate."</td>
                            <td>
								<input type='image' src='img/cross.png' border='0' width=18px height=18px alt='Submit'  name='editUser_1' value='Click me'>
                                <input type='image' src='img/edit.jpg'  border='0' width=18px height=18px alt='Submit'  name='editUser_2' value='Click me2'>
                            </td>
                        </tr></form>";
    }
    return $htmlUsers . "</table>";
}

function getTablesAndValidateGET($db, $htmlAdmin)
{
	if(isset($_GET['c']) && is_numeric(($_GET['c'])) )
	{
		$choice = strip_tags($_GET['c']);
		switch($choice)
		{
			case 0:
				$htmlAdmin = getTableTitleOfPosts($db);
				break;
			case 1:
				$htmlAdmin = getTableEvents($db);
				break;
			case 2:
				$htmlAdmin = getTableUsers($db);
				break;
			default :
				$htmlAdmin = "<h4 id='infoHead'> Välj från menun till höger </h4>";
				break;
		}
		return $htmlAdmin . "</div>";
	}
}

function tryToRemoveUser($db)
{
	if(isset($_POST['userId']) && is_numeric($_POST['userId']) )
	{
		try
		{
			$sql = "DELETE FROM users WHERE id=? LIMIT 1";
			$userId = $_POST['userId'];
			$params = array($userId);
			$db->ExecuteQuery($sql,$params);
			return true;
		}
		catch(Exception $e)
		{
			logError("< " . $_SESSION['uid'] . "  tryToRemoveUser > Error: " . $e . "\n");
			return false;
		}
	}
}

function tryToEditUser($db)
{
	if(isset($_POST['userId']) && is_numeric($_POST['userId']) )
	{
		try
		{
			$sql = "UPDATE users SET name=?, Privilege=? WHERE id=? LIMIT 1";
			$userId    = $_POST['userId'];
			$name 	   = $_POST['username'];
			$Privilege = $_POST['privilege'];
			$params = array($name, $Privilege, $userId);
			echo $userId;
			$db->ExecuteQuery($sql,$params);
			return true;
		}
		catch(Exception $e)
		{
			logError("< " . $_SESSION['uid'] . "  tryToEditUser > Error: " . $e . "\n");
			return false;
		}
	}
}

if($privilege === "2")
{
	$sideBar = "<div id='sidebar'><ul>
                <li><a href='".$_SERVER['PHP_SELF'] . "?c=0'>Nyheter</a></li>
                <li><a href='".$_SERVER['PHP_SELF'] . "?c=1'>Träningar</a></li>
                <li><a href='".$_SERVER['PHP_SELF'] . "?c=2'>Användare</a></li>
               </ul></div>";
    $htmlAdmin = "<div id='content'>";
    if(isset($_POST['editUser_1_x'])) // Where does x come from?
    {
        if(tryToRemoveUser($db))
        {
        	$htmlAdmin .= "<h4 id='infoHead'>Användare borttagen </h4>";
        }
        else
        {
        	$htmlAdmin .= "<h4 id='infoHead'> ... </h4>";
        }
    }
    else if(isset($_POST['editUser_2_x']))
    {
        if(tryToEditUser($db))
        {
        	$htmlAdmin .= "<h4 id='infoHead'> User updated </h4>";
        }
        else
        {
        	$htmlAdmin .= "<h4 id='infoHead'> Not updated... </h4>";
        }
    }

    $htmlAdmin .= getTablesAndValidateGET($db, $htmlAdmin);
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