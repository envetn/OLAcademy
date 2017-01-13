<?php
$pageId = "index";
include ("include/header.php");

$username = isset($_SESSION["username"]) ? $_SESSION["username"] : "";
$eventObject = new EventObject();
$newsObject = new NewsObject();
$guestbookObject = new GuestbookObject();

$eventObject->updateEvents();

if (validateStringGET("highlighted"))
{
    $_SESSION["highlighted"] = $_GET["highlighted"];
}
elseif (isset($_SESSION["highlighted"]))
{
    $_SESSION["highlighted"] = $_SESSION["highlighted"];
}
else
{
    $_SESSION["highlighted"] = date("Y-m-d");
}

registerUserToEvent($user, $eventObject);

if (validateIntGET("r"))
{
    $id = $_GET["r"];
    if ($eventObject->isAllowedToDeleteEntry($id))
    {
        $eventObject->unRegisterUserToEvent($id);
    }
    header("Location: index.php");
}

//'. /*$GLOBAL["error"]*/ .'
displayError();
echo '<div class="row">';
echo '<article class="col-sm-4 col-sm-push-8 elementBox"><h1>Träningar</h1>' . presentEvent($username, $eventObject) . ' </article>';
echo '<article class="col-sm-8 col-sm-pull-4 elementBox"><h1>Nyheter</h1>' . presentNews($newsObject, 0, 3, false);
echo '<br><h1>Gästbok</h1>' . presentPost($guestbookObject, 0, 3, false) . ' </article>';
echo '</div>';
include ("include/footer.php");
?>

