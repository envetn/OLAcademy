<?php
$pageId = "guestbook";
$pageTitle = "- Gästbok";
include ("include/header.php");

$username = isset($_SESSION["username"]) ? $_SESSION["username"] : "";
$lastname = isset($_SESSION["lastname"]) ? $_SESSION["lastname"] : "";
$limit = 7; //Posts per page
$offset = validateIntGET("offset") ? $_GET["offset"] : 0; //Start index
$guestbookObject = new GuestbookObject();

$captchaFirst = rand(0,10);
$captchaSecond = rand(0,10);

function printIfContent()
{
    if(validateStringPOST("text"))
    {
        return $_POST["text"];
    }
    return "";
}

function isCaptchaValid()
{
    if(validateIntPOST("captcha"))
    {
        $captcha = strip_tags($_POST["captcha"]);
        $sumOfCaptcha = $_POST["captchaFirst"] + $_POST["captchaSecond"];
        if($captcha == $sumOfCaptcha)
        {
            return true;
        }
    }
    return false;
}

function makePost($guestbookObject, $logged_in)
{
    if(isCaptchaValid() || $logged_in)
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
                populateError("Text must not exceed " . $max_text_length . " characters.");
            }
            elseif (strlen($name) > $max_name_length)
            {
                populateError("Name must not exceed " . $max_name_length . " characters.");
            }
            else
            {
                $params = array('name' => $name, 'text' => $text);
                $guestbookObject->insertEntyToDatabase($params);
                header("location: guestbook.php");
            }
        }
        else if (empty($name) || empty($text))
        {
            populateError("Fyll i alla fält.");
        }
    }
    else
    {
        populateError("Fel kontrollkod");
    }
}

if (isset($_POST["submit"]))
{
	makePost($guestbookObject, $user->isLoggedIn());
}

if (!$user->isLoggedIn())
{
	$captcha = '<label>
		    <input type="hidden" value='.$captchaFirst.' name="captchaFirst"/>
		    <input type="hidden" value='.$captchaSecond.' name="captchaSecond"/>
		    ' . $captchaFirst . ' + ' . $captchaSecond . ' = 
		</label>
		<input type="text" name="captcha" size="5"/><br/>';
}
else $captcha = '';

$postForm = '<div class="col-sm-4 col-sm-pull-8 elementBox">
	<h2>Gästbok</h2>
	<form action="' . $_SERVER["PHP_SELF"] . '" method="POST">
		<label>Namn:<br><input type="text" name="name" value="' . $username ." ". $lastname . '" size="20"/></label><br>
		<label>Text:<br><textarea name="text" rows="8" cols="40">'. printIfContent() . '</textarea></label><br>
		' . $captcha . '
		
		<label><input type="submit" class="btn btn-primary" name="submit" value="Skicka"/></label><br>
	</form>
</div>';

displayError();
echo "<div class='row'>";
echo "<div class='col-sm-8 col-sm-push-4 elementBox'>";
echo presentPost($guestbookObject, $offset, $limit);
$nrOfRows = $guestbookObject->countAllRows();
echo "<div class='paging'>" . paging($limit, $offset, $nrOfRows, $numbers = 5) . "</div>";
echo "</div>";
echo $postForm;
echo "</div>";

include ("include/footer.php");
?>
