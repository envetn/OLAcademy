<?php
include("include/header.php");

$pageId ="index";
$userId = isset($_SESSION["uid"]) ? $_SESSION["uid"] : false;
$username = isset($_SESSION["username"]) ? $_SESSION["username"]: "";

$eventObject = new EventObject();
$newsObject = new NewsObject();
$guestbookObject = new GuestbookObject();

$eventObject->updateEvents();
if (isset($_GET["highlighted"]))
{
    $_SESSION["highlighted"] = $_GET["highlighted"];
}
elseif (isset($_SESSION["highlighted"]))
{
	$_SESSION["highlighted"] = $_SESSION["highlighted"];
}
else
{
    $_SESSION["highlighted"] = 0;
}

// Post functions
if(isset($_POST["register"]))
{
    $eventId = $_POST["eventID"];
	$bus = isset($_POST["bus"]) ? $_POST["bus"] : "Nej";
	$comment = $_POST["comment"];
	$comment = makeLinks($comment);

	if(!$user->isLoggedIn())
	{
		populateError("Du måste vara inloggad för att kunna anmäla dig.");
	}
    else
    {
    	$conditions = array('userID' => $userId, 'eventID' => $eventId);
    	$res = $eventObject->getRegisteredByValue($conditions);
    	if($res == null)
    	{
    		$params = array('userID' => $userId, 'name' => $username, 'date' => $_POST["date"], 'comment' => $comment, 'bus' => $bus,'eventID' => $eventId);
    		$eventObject->registerUserToEvent($params);
    	}
    }
}

if(isset($_GET["r"]) && is_numeric($_GET["r"]))
{
    $id = $_GET["r"];
    if($eventObject->isAllowedToDeleteEntry($id))
    {
    	$eventObject->unRegisterUserToEvent($id);
    }
    header("Location: index.php");
}

//'. /*$GLOBAL["error"]*/ .'
displayError();
echo '<div class="row">';
echo '<article class="col-sm-4 col-sm-push-8 elementBox"><h1>Träningar</h1>' . presentEvent($username, $eventObject) .' </article>';
echo '<article class="col-sm-8 col-sm-pull-4 elementBox"><h1>Nyheter</h1>'. presentNews($newsObject, 0, 3, false);
echo '<br><h1>Gästbok</h1>'. presentPost($guestbookObject, 0, 3) .' </article>';
echo '</div>';
include("include/footer.php");
?>

