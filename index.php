<?php
include("include/header.php"); 

$user = isset($_SESSION['uid']) ? $_SESSION['uid'] : false;
$username    = isset($_SESSION['username']) ? $_SESSION['username']: "";
// Post functions
if(isset($_POST['register']))
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
    

}
?>

<article id="start_news"><h1>Nyheter</h1></article>

<article id="start_calendar"><h1>Kalender</h1></article>

<article id="start_gb"><h1>Gästbok</h1><?php echo presentPost($db, 0, 3); ?></article>

<?php
if($user != false)
{
    $res = getCurrentMonthsEvents($db);
    $registredEvents = getRegisteredEvents($db,$username);

    $registration =
       "<article style='width:80%; overflow: hidden; /* '>
        <aside id='start_registration' style='float:left; width:40%;'>
        <h1>Anmälan</h1>
        <p> Träningar denna månaden </p>
        <form action =".$_SERVER["PHP_SELF"]." method='POST'>
        <input type='hidden' name='id' value=".$user."/>
        <select name='register'/> <option value='0'>choose wisely</option>";
        foreach ($res as $key)
        {
            $registration .= "<option value='$key->id'>".$key->eventName." - " . $key->date ."</option>";
        }
    $registration .="
        <input type='submit' name='submit' value='Anmäl'/>
        </form>";
        
    $registration .= "</aside>";
    $registration .= "<div style='float:right; width:40%;'><h1>Anmälda</h1><table>";
        foreach($registredEvents as $events) 
        {
            $registration .= "<li>" . $events->eventName . " - " . $events->info . " - " . $events->startTime . " - " . substr($events->date,-5) . "</li>";
        }
    $registration .= "</table></div></article>";
            
    echo $registration;
}





include("include/footer.php");
?>
