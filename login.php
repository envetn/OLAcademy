<?php
if(isset($_POST['login']))
{
	echo "login";
	$username = $_POST['username'];
    $password = md5($_POST['passwd']);
    
    $sql = "SELECT * FROM users WHERE username=? AND password=? LIMIT 1";
    $params = array($username, $password);
    $res = $db->queryAndFetch($sql, $params);
    if($db->RowCount() == 1)
    {
        foreach ($res as $key)
        {
            $_SESSION['uid'] = $key->id;
            $_SESSION['username'] = $key->name;
            break;
        }
    }
}


?>
<form id='form_login' method='post'>
    <label>Username: </label><input type='text' name='username'/>
    <label>Password: </label><input type='password' name='passwd'/>
    <input type='submit' value='Login' name='login'/>
</form>


<!--  <form id='form_login' method='post'>
    <label>name: </label><input type='text' name='username'/><br/>
    <label>Password: </label><input type='password' name='passwd'/><br/>
    <input type='submit' value='create User' name='user'/>
</form>

if(isset($_POST['user']))
{
    echo "Created user";
    $username = $_POST['username'];
    $password = md5($_POST['passwd'] . $GLOBAL['salt_char']);
    $email = "123@123.com";
    $priv = "1";
    $date = date("Y-m-d H:i:s");
    
    $sql = "INSERT INTO users (name, password, email, Privilege, regDate) VALUES(?,?,?,?,?)";
    $params = array($username, $password, $email, $priv, $date);
    $db->queryAndFetch($sql, $params, true);
}

-->