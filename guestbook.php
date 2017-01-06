<?php
$pageId = "guestbook";
$pageTitle = "- Gästbok";
include ("include/header.php");

$username = isset($_SESSION["username"]) ? $_SESSION["username"] : printIfContent("name");
$lastname = isset($_SESSION["lastname"]) ? $_SESSION["lastname"] : "";
$limit = 7; //Posts per page
$offset = validateIntGET("offset") ? $_GET["offset"] : 0; //Start index
$guestbookObject = new GuestbookObject();
$timeStart = time();

function makePost($guestbookObject, $logged_in)
{
    //log to file for debugging
    $myfile = fopen("logfile.txt", "a");
    $timeStop = time();
    $timeSpent = $timeStop - $_POST["timeStart"];
    $dateIp = date("Y-m-d H:i:s",time()) . "        " . $_SERVER['REMOTE_ADDR'] . "        " . $timeSpent . "       ";
    if($timeSpent > 10 || $logged_in) //(isCaptchaValid() || $logged_in)
    {
        if (validateStringPOST("name") && validateStringPOST("text"))
        {
            $name = strip_tags($_POST["name"]);
            $text = strip_tags($_POST["text"]);
            $text = makeLinks($text);
            $max_text_length = 2000;
            $max_name_length = 50;
            if (strlen($text) > $max_text_length)
            {
                $dateIp .= "Failed - faulty text length\n";
                populateError("Text must not exceed " . $max_text_length . " characters.");
            }
            elseif (strlen($name) > $max_name_length)
            {
                $dateIp .= "Failed - faulty name length\n";
                populateError("Name must not exceed " . $max_name_length . " characters.");
            }
            else
            {
                $params = array('name' => $name, 'text' => $text);
                $guestbookObject->insertEntyToDatabase($params);
                $dateIp .= "Success\n";
                header("location: guestbook.php");
            }
        }
        else if (empty($name) || empty($text))
        {
            $dateIp .= "Failed - field empty\n";
            populateError("Fyll i alla fält.");
        }
    }
    else
    {
        populateError("Du fyllde i formuläret på under 10 sekunder. Är du en bot? Försök att sakta ner.");
        $dateIp .= "Failed - Too fast too furious\n";
        //populateError("Fel kontrollkod");
        //$dateIp .= "Failed - wrong control code\n";
    }

    fwrite($myfile, $dateIp);
    fclose($myfile);
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
$captcha = ""; //!$user->isLoggedIn() ? getCaptchaForm() : "";

$postForm = '<div class="col-sm-4 col-sm-pull-8 elementBox">
    <h2>Gästbok</h2>
    <form action="' . $_SERVER["PHP_SELF"] . '" method="POST">
        <div class="gb_fields"><label>Namn:</label><br><input type="text" name="name" value="' .  $username ." ". $lastname . '" size="20"/></div>
        <div class="gb_fields"><label>Text:</label><br><textarea name="text" rows="8" cols="40">'. printIfContent("text") . '</textarea></div>
        ' . $captcha . '
        <div class="gb_fields"><input type="submit" class="btn btn-primary" name="submit" value="Skicka" onclick="return validate();"/></div>
        <input type="hidden" name="timeStart" value="' . $timeStart . '">
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
