<style>
label {
	display: inline-block;
	width: 100px;
	text-align: left;
	padding-right: 10%;
}

input {
	margin-bottom: 1%;
	padding-left: 2px
}

#changePassword {
	border: 1px solid black;
	width: 40%;
	padding: 1%;
}
​
</style>
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
	$values = array('variable' => 'id', 'value' => $id);
	$res = $user->fetchSingleEntryByValue($values);

	if ($user->rowCount() == 1)
	{
		$form = "
            <form method='post'>
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
if (isset($_SESSION['username']))
{
	// Logged in
	echo getUserInformation($user);

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
			}
			else
			{
				updateUser($user);
			}
		}
		else
		{
			$_SESSION['error'] = "<pre class=red>Failed</pre>";
		}
	}
}
else
{
	//show createUser
}
echo isset($_SESSION['error']) ? $_SESSION['error'] : "";
echo isset($_SESSION['success']) ? $_SESSION['success'] : "";