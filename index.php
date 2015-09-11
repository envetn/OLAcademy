<?php
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



<div id='start_wrapper'>
<?php
echo '<article id="start_register"><h1>Anmälan</h1>' . presentEvent($db) .' </article>';
echo '<article id="start_gb" style="width:60%"><h1>Gästbok</h1>'. presentPost($db, 0, 3) .' </article>';
echo '<article id="start_news" style="width:60%"><h1>Nyheter</h1>'. presentNews($db, 0, 3) .' ></article>';

function presentEvent($db)
{
	$events = getEvents($db);
	$text = "";
	for ($i=0;$i<7;$i++)
	{
	
		$weekDay = date("N", time()+($i * 24 * 60 * 60));
		switch ($weekDay)
		{
		    case "1":
			echo "<h4>Måndag</h4>";
			break;
		    case "2":
			echo "<h4>Tisdag</h4>";
			break;
		    case "3":
			echo "<h4>Onsdag</h4>";
			break;
		    case "4":
			echo "<h4>Torsdag</h4>";
			break;
		    case "5":
			echo "<h4>Fredag</h4>";
			break;
		    case "6":
			echo "<h4>Lördag</h4>";
			break;
		    case "7":
			echo "<h4>Söndag</h4>";
			break;
		    default:
			echo "-<br>";
		}
	
		foreach ($events as $key)
		{
			if ($key->date == date("Y-m-d", time()+($i * 24 * 60 * 60)))
			echo $key->id. " ".$key->eventName." - " . $key->date . "<br>";
		}	
	}
}
include("include/footer.php");
?>

</div>
