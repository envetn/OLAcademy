<?php
include("include/header.php"); 

$pageId ="index";
$userID = isset($_SESSION['uid']) ? $_SESSION['uid'] : false;
$username = isset($_SESSION['username']) ? $_SESSION['username']: "";

$eventObject = new EventObject($db);
$newsObject = new NewsObject($db);
$guestbookObject = new GuestbookObject($db);

$eventObject->updateEvents();
if (isset($_GET['highlighted']))
{
    $_SESSION['highlighted'] = $_GET['highlighted'];
}
elseif (isset($_SESSION['highlighted']))
{
	$_SESSION['highlighted'] = $_SESSION['highlighted'];
}
else
{
    $_SESSION['highlighted'] = 0;
}

// Post functions
if(isset($_POST['register']))
{
    $eventID = $_POST["eventID"];
	$bus = isset($_POST['bus']) ? $_POST['bus'] : "Nej";
	//check if already exists
    $sql = "SELECT * FROM registered WHERE userID=? and eventId=?";
    $params = array($userID, $eventID);
    $db->queryAndFetch($sql, $params);
	
    if($db->RowCount() >= 1)
    {
		$_SESSION['error'] = '<pre class=red>Du är redan anmäld till denna träningen.</pre>';
    }
	elseif(!$userID)
	{
		$_SESSION['error'] = '<pre class=red>Du måste vara inloggad för att kunna anmäla dig.</pre>';
	}
    else
    {
        $sql = 'INSERT INTO registered (userID, name, date, comment, bus, eventID) VALUES(?,?,?,?,?,?)';
        $params = array($userID,$username,$_POST['date'],$_POST['comment'],$bus,$eventID);
        $db->ExecuteQuery($sql, $params);
    }

}

if(isset($_GET['r']) && is_numeric($_GET['r']))
{

    $id = $_GET['r'];
    if($eventObject->isAllowedToDeleteEntry($id))
    {
    	$eventObject->removeSingleRegistered($id);
    }
    header("Location: index.php");
}


//'. /*$GLOBAL['error']*/ .'
echo isset($_SESSION['error']) ? $_SESSION['error'] : "";
echo '<div class="row clearFix">';
echo '<article class="col-sm-4 col-sm-push-8 elementBox"><h1>Träningar</h1>' . presentEvent($username, $eventObject) .' </article>';
echo '<article class="col-sm-8 col-sm-pull-4 elementBox"><h1>Gästbok</h1>'. presentPost($guestbookObject, 0, 3);
echo '<h1>Nyheter</h1>'. presentNews($newsObject, 0, 3, false) .' </article>';
echo '</div>';
include("include/footer.php");
?>

