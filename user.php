<?php
include ('include/header.php');

/*
 * if(logged in)
 * show editable information
 * else
 * Possible to create new user
 */

$privilege = $user->getUserprivilege();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

function validatePasswords($password, $passwordRepeat, $user)
{
	global $GLOBAL;
	$salt = $GLOBAL['salt_char'];

	if ($password === $passwordRepeat)
	{
		// update user with new pw
		updateUser($user, $password);
	}
	else
	{
		$_SESSION['error'] = "<pre class=red>Lösenorden matchade inte</pre>";
	}
}

function updateUser($user, $newPassword = "")
{
	// validate
	$name = $_POST['username'];
	$email = $_POST['email'];
	$user->updateUser($name, $email, $newPassword);
}

function getUserInformation($user)
{
	$id = $_SESSION['uid'];
	$values = array('id' => $id);
	$res = $user->fetchSingleEntryByValue($values);

	if ($user->rowCount() == 1)
	{
		$form = "
            <form method='post' id='userInformation'>
             <label>Användarnamn</label><input type='text' value='" . $res->name . "' name='username'/><br/>
             <label>Email</label><input type='text' value='" . $res->email . "' name='email'/><br/>
             <label>Nyvarande lösenord</label><input type='password' value='' name='oldPassword'/><br/>

        	 <div id='changePassword'>
             <p> Ändra lösenord </p></br>
             <label>Nytt lösenordet</label><input type='password' value='' name='newPassword'/><br/>
             <label>Uprepa nytt lösenord</label><input type='password' name='newPasswordRepeat' value=''/>
            </div>
        	<input type='submit' value='spara' name='spara' class='btn btn-primary'/>
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

		$password = $_POST['password'];
		$passwordRepeat = $_POST['passwordRepeat'];

		$email = strip_tags($_POST['email']);
		$priv = isset($_POST['Privilege']) ? $_POST['Privilege'] : "0";
		$date = date("Y-m-d H:i:s");

		if ($password === $passwordRepeat)
		{
			$values = array('email' => $email);
			$res = $user->fetchSingleEntryByValue($values);
			if ($res === null)
			{
				$password = password_hash($_POST['password'], PASSWORD_BCRYPT, array('cost' => 12));
				$params = array('name' => $username, 'password' => $password, 'email' => $email, 'Privilege' => $priv, 'regDate' => $date);
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
		global $GLOBAL;
		$salt = $GLOBAL['salt_char'];
		$email = $_POST['email'];

		if ($user->login($email, $_POST['oldPassword']))
		{
			if ((isset($_POST['newPassword']) && isset($_POST['newPasswordRepeat'])) && (strlen($_POST['newPassword'])) > 5 && (strlen($_POST['newPasswordRepeat'])) > 5)
			{
				$newPassword = $_POST['newPassword'];
				$newPasswordRepeat = $_POST['newPasswordRepeat'];
				validatePasswords($newPassword, $newPasswordRepeat, $user);
				$success = "Lösenord uppdaterat";

			}
			else
			{
				updateUser($user);
				$success = "Användare uppdaterad";
			}
		}
		else
		{
			$_SESSION['error'] = "<pre class=red>Failed</pre>";
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
			<label>Användarnamn</label><input type='text' value='' name='username' /><br />
			<label>Email</label><input type='text' name='email' /><br />
			<label>Lösenord</label><input type='password' value='' name='password' /><br />
				<label>Upprepa lösenord</label><input type='password' value='' name='passwordRepeat' /><br />
			<input type='submit' value='spara' name='spara' class='btn btn-primary'/>
		</form>
	</div>
	";
}
echo isset($_SESSION['error']) ? $_SESSION['error'] : "";
echo isset($success) ? $success : "";
echo $userConf;
