<?php
if(isset($_POST['login']))
{
    $username = strip_tags($_POST['username']);
    $password = md5($_POST['passwd'] . $GLOBAL['salt_char']);
    $sql = "SELECT * FROM users WHERE name=? AND password=? LIMIT 1";

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
if(isset($_SESSION['uid']))
{
	$form = "Användare: " . $_SESSION['username'] . "&nbsp;&nbsp;&nbsp;<input type='submit' value='Logga ut' onClick=\"window.location='logout.php'\"/>";
}
else
{
    $form = "<div id='div_login'><form id='form_login' method='post'>
              <input type='text' name='username' placeholder='Användarnamn'>
              <input type='password' name='passwd' placeholder='Lösenord'>
              <input id='login_submit'type='submit' value='Login' name='login'>
         </form>
         </div>";
        
}
echo $form;
?>

