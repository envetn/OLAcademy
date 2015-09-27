<?php
$pageId ="index";


include("include/header.php"); 
$userID = isset($_SESSION['uid']) ? $_SESSION['uid'] : false;
$username = isset($_SESSION['username']) ? $_SESSION['username']: "";
if (isset($_GET['highlighted']))
{
    $_SESSION['highlighted'] = $_GET['highlighted'];
}
elseif (isset($_SESSION['highlighted']))
{
	$_SESSION['highlighted'] = $_SESSION['highlighted'];
}
else
{
    $_SESSION['highlighted'] = 0;
}

// Post functions
if(isset($_POST['register']))
{
    $eventID = $_POST["eventID"];
	$bus = isset($_POST['bus']) ? $_POST['bus'] : "Nej";
	//check if already exists
    $sql = "SELECT * FROM registered WHERE userID=? and eventId=?";
    $params = array($userID, $eventID);
    $db->queryAndFetch($sql, $params);
	
    if($db->RowCount() >= 1)
    {
		$GLOBAL['error'] .= '<span class="error">Du är redan anmäld till denna träningen.</span>';
    }
	elseif(!$userID)
	{
		$GLOBAL['error'] .= '<span class="error">Du måste vara inloggad för att kunna anmäla dig.</span>';
	}
    else
    {
        $sql = 'INSERT INTO registered (userID, name, date, comment, bus, eventID) VALUES(?,?,?,?,?,?)';
        $params = array($userID,$username,$_POST['date'],$_POST['comment'],$bus,$eventID);
        $db->ExecuteQuery($sql, $params);
    }

}

if(isset($_GET['r']) && is_numeric($_GET['r']))
{
    /*remove registration */
    // TODO : display popup before delete?

    $id = $_GET['r'];
    if(isAllowedToDeleteReg($db,$id))
    {
        $sql = "DELETE FROM registered WHERE id=?";
        $params = array($id);
        $db->ExecuteQuery($sql, $params);
    }
    header("Location: index.php");
}

?>
<!--<div class='start_wrapper clearFix'>-->
<div class='row clearFix'>
<?php
echo '<article class="col-sm-4 col-sm-push-8 b">'. $GLOBAL['error'] .'<h1>Träningar</h1>' . presentEvent($db, $username) .' </article>';
echo '<article class="col-sm-8 col-sm-pull-4 b" style="width:60%"><h1>Gästbok</h1>'. presentPost($db, 0, 3) .' </article>';
echo '<div class="clearfix visible-xs-block"></div>';
echo '<article class="col-sm-8 col-sm-offset-0 b" style="width:60%"><h1>Nyheter</h1>'. presentNews($db, 0, 3) .' ></article>';
include("include/footer.php");
?>
</div>
