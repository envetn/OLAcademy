<style>
form {
	padding-left: 2%;
}

label {
	display: inline-block;
	width: 100px;
	text-align: left;
	padding-right: 10%;
}

input {
	padding-left: 2px
}
</style>

<?php
include ("include/header.php");
$db = new Database($GLOBAL['database']);

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
		$values = array('variable' => 'email', 'value' => $email);
		$res = $user->fetchSingleEntryByValue($values);
		if($res === null)
		{
			$password = password_hash($_POST['password'], PASSWORD_BCRYPT, array('cost'=>12));
			$sql = "INSERT INTO users (name, password, email, Privilege, regDate) VALUES(?,?,?,?,?)";
			$params = array($username,$password,$email,$priv,$date);
			$db->queryAndFetch($sql, $params);

			$_SESSION['success']= "Inserted into database";
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
echo
"
<div class='row'>

	<form method='post'>
		<label>Användarnamn</label><input type='text' value='' name='username' /><br />
		<label>Email</label><input type='text' name='email' /><br />
		<label>Lösenord</label><input type='password' value='' name='password' /><br />
			<label>Upprepa lösenord</label><input type='password' value='' name='passwordRepeat' /><br />
		<input type='submit' value='spara' name='spara' class='btn btn-primary'/>
	</form>
</div>
";
echo isset($_SESSION['error']) ? $_SESSION['error'] : "";
//echo isset($_SESSION['success']) ? $_SESSION['success'] : "";

?>


