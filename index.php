<?php
$pageId ="index";
include("include/header.php");
$user = isset($_SESSION['uid']) ? $_SESSION['uid'] : false;
$username    = isset($_SESSION['username']) ? $_SESSION['username']: "";
// Post functions
if(isset($_POST['register']))
{
    echo $_POST['register'];
    $eventID = $_POST["eventID"];
    //check if already exists
    $sql = "SELECT * FROM registered WHERE name=? and eventId=?";
    $params = array($username, $eventID);
    $db->queryAndFetch($sql, $params);
    if($db->RowCount() >= 1)
    {
		$GLOBAL['error'] .= '<span class="error">Du är redan anmäld till denna träningen.</span>';
    }
	elseif($username == "")
	{
		$GLOBAL['error'] .= '<span class="error">Du måste vara inloggad för att kunna anmäla dig.</span>';
	}
    else
    {
        $sql = 'INSERT INTO registered (name, date, other, eventId) VALUES(?,?,?,?)';
        $params = array($username,date("Y-m-d"),"",$eventID);
        $db->ExecuteQuery($sql, $params);
    }
}
?>
<!--<div class='start_wrapper clearFix'>-->
<div class='row clearFix'>
<?php
echo '<article class="col-sm-4 col-sm-push-8 b">'. $GLOBAL['error'] .'<h1>Anmälan</h1>' . presentEvent($db, $username) .' </article>';
echo '<article class="col-sm-8 col-sm-pull-4 b" style="width:60%"><h1>Gästbok</h1>'. presentPost($db, 0, 3) .' </article>';
echo '<div class="clearfix visible-xs-block"></div>';
echo '<article class="col-sm-8 col-sm-offset-0 b" style="width:60%"><h1>Nyheter</h1>'. presentNews($db, 0, 3) .' ></article>';
include("include/footer.php");
?>
</div>