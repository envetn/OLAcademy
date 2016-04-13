<?php
include ('include/header.php');
/*
 * if(logged in)
 * show editable information
 * else
 * Possible to create new user
 */

define("MIN_PASSWD_LENGTH", 6);

$privilege = $user->getUserprivilege();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

function isPasswordsValid($password, $passwordRepeat)
{
	if (strlen($password) > MIN_PASSWD_LENGTH && $password === $passwordRepeat)
	{
		return true;
	}
	return false;
}

function updateUser($user, $newPassword = "")
{
	// validate
	$name = $_POST["username"];
	$lastname = $_POST["lastname"];
	$email = $_POST["email"];
	$user->updateUser($name, $email, $lastname,  $newPassword);
}

function getUserInformation($user)
{
	$id = $_SESSION["uid"];
	$values = array("id" => $id);
	$res = $user->fetchSingleEntryByValue($values);

	$form = "";
	if($_SESSION["changePassword"])
	{
	   $form .= "<p>Info att byta lösen från autogenerade</p>";
	}

	if ($user->rowCount() == 1)
	{
		$form .= "
            <form method='post' id='userInformation'>
             <label>Namn</label><input type='text' value='" . $res->name . "' name='username'/><br/>
             <label>Efternamn</label><input type='text' value='" . $res->lastname . "' name='lastname' /><br />
             <label>Email</label><input type='text' value='" . $res->email . "' name='email'/><br/>
             <label>Nuvarande lösenord</label><input type='password' value='' name='oldPassword'/><br/>

			 <hr>
             <h3> Ändra lösenord </h3>
             <label>Nytt lösenord</label><input type='password' value='' name='newPassword'/><br>
             <label>Uprepa nytt lösenord</label><input type='password' name='newPasswordRepeat' value=''/>
			 <hr>
        	<input type='submit' value='Spara' name='spara' class='btn btn-primary'>
            </form>
        ";
		return $form;
	}
}

function validateCreateUserPost($user)
{
	if (isset($_POST['spara']))
	{
		$username = strip_tags(ucfirst($_POST['username']));
		$lastname = strip_tags(ucfirst($_POST['lastname']));

		$password = $_POST['password'];
		$passwordRepeat = $_POST['passwordRepeat'];

		$email = strip_tags($_POST['email']);
		$priv = isset($_POST['Privilege']) ? $_POST['Privilege'] : "1";
		$date = date("Y-m-d H:i:s");

		if ($password === $passwordRepeat)
		{
			$values = array('email' => $email);
			$res = $user->fetchSingleEntryByValue($values);
			if ($res === null)
			{
				$password = password_hash($_POST['password'], PASSWORD_BCRYPT, array('cost' => 12));
				$params = array('name' => $username, 'password' => $password, 'email' => $email, 'Privilege' => $priv, 'regDate' => $date, 'lastname' => $lastname);
				$user->insertEntyToDatabase($params);

				$success = "Inserted into database";
			}
			else
			{
				$_SESSION['error'] = "<pre class=red>Användare med den emailen finns redan</pre>";
			}
		}
		else
		{
			$_SESSION['error'] = "<pre class=red>Lösenorden matchade inte</pre>";
		}
	}
}

if (isset($_SESSION['username']))
{
	// Logged in
	$userConf = getUserInformation($user);

	if (isset($_POST['spara']))
	{
		$email = $_POST['email'];

		if ($user->login($email, $_POST['oldPassword']))
		{
			if (validateStringPOST("newPassword") && validateStringPOST("newPasswordRepeat"))
			{
			    $newPassword = $_POST['newPassword'];
			    $newPasswordRepeat = $_POST['newPasswordRepeat'];

			    if(isPasswordsValid($newPassword, $newPasswordRepeat))
			    {
			        updateUser($user, $newPasswordRepeat);
			        populateInfo("Användar info och/eller Lösenord uppdaterat");
			    }
			    else 
			    {
			        populateError("Nya lösenorden matchade inte. Notera att lösenordet måste vara minst " . MIN_PASSWD_LENGTH . " tecken långt");
			    }
			}
			else 
			{
			    updateUser($user);
			    populateInfo("Användare uppdaterad");
			}
		}
		else
		{
		    populateError("Fel lösenord");
		}
	}
}
else if (isset($_GET['renew']))
{
	if (isset($_POST['send']))
	{
		$email = strip_tags($_POST['email']);
		$user->forgottenPassword($email);
	}

	$userConf = "
		<div class='row'>
		<form method='post'  id='userInformation'>
			<label>Email</label><input type='text' name='email' /><br />
			<input type='submit' value='Skicka' name='send' class='btn btn-primary'/>
		</form>
	</div>
	";
}
else
{
	//show createUser
	validateCreateUserPost($user);
	$userConf = "
	<div class='row'>
		<form method='post'  id='userInformation'>
			<label>Namn</label><input type='text' value='' name='username' /><br />
			<label>Efternamn</label><input type='text' value='' name='lastname' /><br />
			<label>Email</label><input type='text' name='email' /><br />
			<label>Lösenord</label><input type='password' value='' name='password' /><br />
				<label>Upprepa lösenord</label><input type='password' value='' name='passwordRepeat' /><br />
			<input type='submit' value='Spara' name='spara' class='btn btn-primary'/>
		</form>
	</div>
	";
}

/**
 * Temp fix for error where SESSION['info'] is cleared when using redirect (header(location: ...)
 * 
 * When use is successfully updated send redirect with extra parameter 'm'
 * 
 * Only print Message if SESSION['error'] is empty
 */
if(validateIntGET("m"))
{
    if($_GET["m"] == 1 && empty($_SESSION["error"]))
    {
        populateInfo("Användare uppdaterad");
    }
}
displayError();
displayInfo();

echo $userConf;
include("include/footer.php");