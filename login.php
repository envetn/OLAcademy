<?php
include("include/header.php");
include("include/database.php");
$db = new Database($GLOBAL['database']);

if(isset($_POST['login']))
{
	echo "login";
	$username = $_POST['username'];
// 	header("location: index.php");
}
?>
<form id='form_login' method='post'>
    <label>Username: </label><input type='text' name='username'/><br/>
    <label>Password: </label><input type='password' name='passwd'/><br/>
    <input type='submit' value='Login' name='login'/>
</form>