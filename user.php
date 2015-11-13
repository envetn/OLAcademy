<style>
label { display: inline-block; width:100px; text-align: left; }
input{margin-bottom:1%; }​
</style>
<?php
include('include/header.php');
/*
 * if(logged in)
 *      show editable information
 * else
 *      Possible to create new user
 */
$privilege  = getUserprivilege($db);
$username    = isset($_SESSION['username']) ? $_SESSION['username']: "";
function getUserInformation($db, $username)
{
    $sql = "SELECT name, email FROM users WHERE name=? LIMIT 1";
    $params = array($username);
    $res = $db->queryAndFetch($sql, $params);
    
     if($db->RowCount() == 1)
    {
        $form = 
        "
            <form action='post'>
             <label>Användarnamn</label><input type='text' value='".$res[0]->name."'/><br/>
             <label>Email</label><input type='text' value='".$res[0]->email."'/><br/>
             <label>Kukstorlek</label><input type='password' value=''/><br/>
             <label>Kukstorlek</label><input type='password' value=''/>
            </form>
        ";
        return $form;
    }
}
if(isset($_SESSION['username']))
{
    // Logged in
    echo getUserInformation($db, $_SESSION['username']);
    
}
else
{
    //show createUser
}