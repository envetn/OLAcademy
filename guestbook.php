<?php
$pageId = "guestbook";
$pageTitle = "- Gästbok";
include ("include/header.php");

$username = isset($_SESSION["username"]) ? $_SESSION["username"] : printIfContent("name");
$lastname = isset($_SESSION["lastname"]) ? $_SESSION["lastname"] : "";
$limit = 7; //Posts per page
$offset = validateIntGET("offset") ? $_GET["offset"] : 0; //Start index
$guestbookObject = new GuestbookObject();

function makePost($guestbookObject, $logged_in)
{
    $name = strip_tags($_POST["name"]);
    $text = strip_tags($_POST["text"]);
    $text = makeLinks($text);
    $max_text_length = 2000;
    $max_name_length = 50;

    if (!$logged_in && strtolower($_POST["control"]) != "blå")
    {
        populateError("Fel kontrollssvar. Gissa på blå");
    }
    elseif (empty($name) || empty($text))
    {
        populateError("Fyll i alla fält.");
    }
    elseif (strlen($text) > $max_text_length)
    {
        populateError("Texten får inte överstiga " . $max_text_length . " tecken.");
    }
    elseif (strlen($name) > $max_name_length)
    {
        populateError("Namn får inte överstiga " . $max_name_length . " tecken.");
    }
    else
    {
        $params = array('name' => $name, 'text' => $text);
        $guestbookObject->insertEntyToDatabase($params);
        header("location: guestbook.php");
    }
}

if (isset($_POST["submit"]))
{
    makePost($guestbookObject, $user->isLoggedIn());
}
else if($user->isAdmin() && validateIntGET("r"))
{
    $condition = array("id" => $_GET["r"]);
    if(!$guestbookObject->removeSingleEntryById($condition))
    {
        populateError("Misslyckades ta bort inlägg med id:" . $condition["id"]);
    }
    else
    {
        populateInfo("Tog bort inlägg med id: " . $condition["id"]);
    }
}

$captcha = !$user->isLoggedIn() ? '<div class="gb_fields"><label>Kontrollfråga: Vilken färg har havet?</label><br><input type="text" name="control" size="20"/></div>' : "";
$postForm = '
    <div class="col-sm-4 col-sm-pull-8 elementBox">
        <h2>Gästbok</h2>
        <form action="' . $_SERVER["PHP_SELF"] . '" method="POST">
            <div class="gb_fields"><label>Namn:</label><br><input type="text" name="name" value="' .  $username ." ". $lastname . '" size="20"/></div>
            <div class="gb_fields"><label>Text:</label><br><textarea name="text" rows="8" cols="40">'. printIfContent("text") . '</textarea></div>
            ' . $captcha . '
            <div class="gb_fields"><input type="submit" class="btn btn-primary" name="submit" value="Skicka"/></div>
        </form>
    </div>';


displayError();
displayInfo();
echo "<div class='row'>";
echo "<div class='col-sm-8 col-sm-push-4 elementBox'>";
echo presentPost($guestbookObject, $offset, $limit, $user->isAdmin());
$nrOfRows = $guestbookObject->countAllRows();
echo "<div class='paging'>" . paging($limit, $offset, $nrOfRows, $numbers = 5) . "</div>";
echo "</div>" . $postForm . "</div>";

include ("include/footer.php");
