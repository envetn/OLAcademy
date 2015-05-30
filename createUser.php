<?php
include("include/header.php");

if(isset($_POST['user']))
{
    echo "Created user";
    $username = strip_tags($_POST['username']);
    $password = md5($_POST['passwd'] . $GLOBAL['salt_char']);
    $email = isset($_POST['Email']) ? $_POST['Email'] : "123@123.com";
    $priv = isset($_POST['Privilege']) ? $_POST['Privilege'] : "0";
    $date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO users (name, password, email, Privilege, regDate) VALUES(?,?,?,?,?)";
    $params = array($username, $password, $email, $priv, $date);
    $db->queryAndFetch($sql, $params, true);
}
?>
<form id='form_login' method='post'>
<label>name: </label><input type='text' name='username'/><br/>
<label>Password: </label><input type='password' name='passwd'/><br/>
<label>Email</label><input type='text' name='Email'/><br/>
<label>Privilege: </label><input type='text' name='Privilege'/><br/>
<input type='submit' value='create User' name='user'/>
</form>