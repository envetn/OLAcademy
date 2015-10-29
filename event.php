
<?php
$pageTitle = " - Träningar";
include ("include/header.php");
$privilege = getUserprivilege ( $db );
if(!isset($_SESSION['Previous_page']))
{
	// How to validate which page you are comming from?
	// If the user refreshes the page, the HTTP_REFERER will be restored
	// Also, if user enters url to event manually HTTP_REFERER is null
	$_SESSION['Previous_page'] =  isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'http://localhost/Webb/Olacademy/calendar.php' ;
}
function validateDay($day)
{
	if(!is_numeric($day))
	{
		return date('Y-m-d');
	}

	if(strlen($day) == 1) // 8 -> 08
	{
		$day = "0" . $day;
	}
	
	$day = date('Y-m') . "-" . $day;
	return $day;
}

function getEventAndValidateGET($db)
{
	$singleEvent ="";
	if (isset ( $_GET ['e'] ) && is_numeric ( $_GET ['e'] ))
	{
		$eventId = $_GET ['e'];
		$res = getSingleEvent($db,$eventId);

		if ($db->RowCount () == 1)
		{
			// could use a function for this..
			$checked = $res[0]->reccurance == '1' ? "checked" : "";
			$singleEvent .= "<form id='form_addNew' method='post' enctype='multipart/form-data'>
    		<input name='id' value='" . $res[0]->id . "' 				type='hidden'/>
    		<input name='eventName' value='" . $res[0]->eventName . "'  type='text'/><br/>
    		<input name='info' value='" . $res[0]->info . "' 			type='text'/><br/>
    		<input name='startTime' value='" . $res[0]->startTime . "'  type='time'/><br/>
    		<input name='date' value='" . $res[0]->date . "' 	    	type='datetime-local'/><br/>
    		<label> Återkommande varje vecka: <input id='checkbox' type='checkbox' name='reccurance' value='reccurance' ".$checked."/></label>
    		<input class='btn btn-primary' type='submit' name='btn_edit' id='btn_edit' value='Spara'/>
    		</form>";
		}
	}
	else if(isset ( $_GET ['a'] ) && is_numeric ( $_GET ['a'] ))
	{
		$date = isset($_GET['day']) ? $date = validateDay($_GET['day']) : $date = date('Y-m-d');  

		$time = date('H:i:s', time() - date('Z'));
		$singleEvent .= "<form id='form_addNew' method='post' enctype='multipart/form-data'>
    		<input name='eventName' value='' placeholder='Träning' type='text'/><br/>
    		<input name='info' value='' 	 placeholder='Information'		type='text'/><br/>
    		<input name='startTime' value='' placeholder='".$time."'  type='time'/><br/>
    		<input name='date' value='".$date."' 	    	type='datetime-local'/><br/>
			<label>Återkommande varje vecka: <input type='checkbox' name='reccurance' value='reccurance'></label>
    		<input class='btn btn-primary' type='submit' name='btn_add' id='btn_edit' value='Spara'/>
    		</form>";
	}
	return $singleEvent;
}

function tryToEditEvent($db)
{
	try
	{
		$sql = "UPDATE events SET eventName=?, info=?, startTime=?, date=?, reccurance=? WHERE id=?";
		$eventName   = strip_tags($_POST['eventName']);
		$info 	     = strip_tags($_POST['info']);
		$startTime   = strip_tags($_POST['startTime']);
		$date		 = strip_tags($_POST['date']);
		$id		 	 = is_numeric($_POST['id']) ? strip_tags($_POST['id']) : -1;
		$reccurance = isset($_POST['reccurance']) ? 1 : 0;

		$params = array($eventName, $info, $startTime, $date, $reccurance, $id );
		$db->ExecuteQuery($sql, $params);
		return true;
	}
	catch(Exception $e)
	{
		logError("< " . $_SESSION['uid'] . "  tryToEditEvent > Error: " . $e . "\n");
		return false;
	}
}

function tryToAddEvent($db)
{
	try
	{ // Todo: validate parameters before insert
		$sql = "INSERT INTO events (info, date, startTime, eventName, reccurance) VALUES(?,?,?,?,?)";
		$eventName   = strip_tags($_POST['eventName']);
		$info 	     = strip_tags($_POST['info']);
		$startTime   = strip_tags($_POST['startTime']);
		$date		 = strip_tags($_POST['date']);
		$reccurance = isset($_POST['reccurance']) ? 1 : 0;
		$params = array($info, $date, $startTime, $eventName, $reccurance);

		$db->ExecuteQuery($sql, $params);
		return true;
	}
	catch(Exception $e)
	{
		logError("< " . $_SESSION['uid'] . "  tryToAddEvent > Error: " . $e . "\n");
		return false;
	}
	
}

if ($privilege === "2") // Shall someone else be able to add??
{
	$singleEvent = "";
	if(isset($_POST['btn_edit']))
	{
		if(tryToEditEvent($db))
		{
			$singleEvent .= "<h4 id='infoHead'>Träning sparad </h4>";
		}
		else
		{
			$singleEvent .= displayErrorMessage("Kopplingen till databasen försvann");
		}
	}
	else if(isset($_POST['btn_add']))
	{
		if(tryToAddEvent($db))
		{
			$singleEvent .= "<h4 id='infoHead'>Träning sparad </h4>";
		}
		else
		{
			$singleEvent .= displayErrorMessage("Kopplingen till databasen försvann");
		}
	}
	print_r($_SESSION['Previous_page']);
	$singleEvent .= "<h4 id='infoHead'> <a href='".$_SESSION['Previous_page']."'> Tillbaka</a></h4>";
	$singleEvent .= getEventAndValidateGET($db);
	
}
echo "<div class='row clearFix'>";

if(isset($singleEvent) && strlen($singleEvent) > 2)
{
    echo $singleEvent;
}
else
{
	echo displayErrorMessage("Admin somnade");
}