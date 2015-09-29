<?php
$pageTitle = " - Träningar";
include ("include/header.php");
$privilege = getUserprivilege ( $db );

function getEventAndValidateGET($db)
{
	$singleEvent ="";
	if (isset ( $_GET ['e'] ) && is_numeric ( $_GET ['e'] ))
	{
		$eventId = $_GET ['e'];
		$res = getSingleEvent($db,$eventId);

		if ($db->RowCount () == 1)
		{
			$singleEvent .= "<form id='form_addNew' method='post' enctype='multipart/form-data'>
    		<input name='id' value='" . $res[0]->id . "' 				type='hidden'/>
    		<input name='eventName' value='" . $res[0]->eventName . "'  type='text'/><br/>
    		<input name='info' value='" . $res[0]->info . "' 			type='text'/><br/>
    		<input name='startTime' value='" . $res[0]->startTime . "'  type='time'/><br/>
    		<input name='date' value='" . $res[0]->date . "' 	    	type='datetime-local'/><br/>
    		<input class='btn btn-primary' type='submit' name='btn_edit' id='btn_edit' value='Spara'/>
    		</form>";
		}
	}
	else if(isset ( $_GET ['a'] ) && is_numeric ( $_GET ['a'] ))
	{
		$date = date('Y-m-d');
		$singleEvent .= "<form id='form_addNew' method='post' enctype='multipart/form-data'>
    		<input name='eventName' value='' placeholder='Träning' type='text'/><br/>
    		<input name='info' value='' 	 placeholder='Information'		type='text'/><br/>
    		<input name='startTime' value='' placeholder='Tid'  type='time'/><br/>
    		<input name='date' value='".$date."' 	    	type='datetime-local'/><br/>
    		<input class='btn btn-primary' type='submit' name='btn_add' id='btn_addnew' value='Spara'/>
    		</form>";
	}
	return $singleEvent;
}

function tryToEditEvent($db)
{
	try
	{
		$sql = "UPDATE events SET eventName=?, info=?, startTime=?, date=? WHERE id=?";
		$eventName   = strip_tags($_POST['eventName']);
		$info 	     = strip_tags($_POST['info']);
		$startTime   = strip_tags($_POST['startTime']);
		$date		 = strip_tags($_POST['date']);
		$id		 	 = is_numeric($_POST['id']) ? strip_tags($_POST['id']) : -1;

		$params = array($eventName, $info, $startTime, $date, $id );
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
	{
		$sql = "INSERT INTO events (info, date, startTime, eventName) VALUES(?,?,?,?)";
		$eventName   = strip_tags($_POST['eventName']);
		$info 	     = strip_tags($_POST['info']);
		$startTime   = strip_tags($_POST['startTime']);
		$date		 = strip_tags($_POST['date']);

		$params = array($info, $date, $startTime, $eventName);
		$db->ExecuteQuery($sql, $params);
		return true;
	}
	catch(Exception $e)
	{
		logError("< " . $_SESSION['uid'] . "  tryToAddEvent > Error: " . $e . "\n");
		return false;
	}
	
}

if ($privilege === "2") // ?Shall someone else be able to add?
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
			$singleEvent .= "<h4 id='infoHead'>Träning tillagd </h4>";
		}
		else
		{
			$singleEvent .= displayErrorMessage("Kopplingen till databasen försvann");
		}
	}
	$singleEvent .= getEventAndValidateGET($db);
}
echo "<div class='row clearFix'>";

if(strlen($singleEvent) > 2)
{
    echo $singleEvent;
}
else
{
	echo displayErrorMessage("Admin somnade");
}