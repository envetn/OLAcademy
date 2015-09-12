<?php
$pageId ="index";

include("include/header.php"); 

$user = isset($_SESSION['uid']) ? $_SESSION['uid'] : false;
$username    = isset($_SESSION['username']) ? $_SESSION['username']: "";
// Post functions
/* if(isset($_POST['register']))
{

    $eventId = $_POST["register"];
    //check if already exists
    $sql = "SELECT * FROM registerd WHERE name=? and eventId=?";
    $params = array($username, $eventId);
    $db->queryAndFetch($sql, $params);
    if($db->RowCount() >= 1)
    {
        // do something
    }
    else
    {
        $sql = 'INSERT INTO registerd (name, date, other, eventId) VALUES(?,?,?,?)';
        $params = array($username,date("Y-m-d"),"",$eventId);
        $db->ExecuteQuery($sql, $params);
    }
    

} */
?>



<!--<div class='start_wrapper clearFix'>-->
<div class='row clearFix' style="margin-top:50px;">
<?php
echo '<article class="col-sm-4 col-sm-push-8 b"><h1>Anmälan</h1>' . presentEvent($db) .' </article>';
echo '<article class="col-sm-8 col-sm-pull-4 b" style="width:60%"><h1>Gästbok</h1>'. presentPost($db, 0, 3) .' </article>';
echo '<article class="col-sm-8 b" style="width:60%"><h1>Nyheter</h1>'. presentNews($db, 0, 3) .' ></article>';


include("include/footer.php");
?>

</div>
