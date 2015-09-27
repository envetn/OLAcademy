<?php
$pageTitle = " - Admin";
include ("include/header.php");
$privilege = getUserprivilege ( $db );
if ($privilege === "2")
{
	if (isset ( $_GET ['e'] ) && is_numeric ( $_GET ['e'] ))
	{
		$eventId = $_GET ['e'];
		$res = getSingleEvent($db,$eventId);
		if ($db->RowCount () == 1)
		{
			$singleEvent = "<form id='form_addNew' method='post' enctype='multipart/form-data'>
    		<input name='id' value='" . $res[0]->id . "' 				type='hidden'/>
    		<input name='eventName' value='" . $res[0]->eventName . "'  type='text'/><br/>
    		<input name='info' value='" . $res[0]->info . "' 			type='text'/><br/>
    		<input name='startTime' value='" . $res[0]->startTime . "'  type='time'/><br/>
    		<input name='date' value='" . $res[0]->date . "' 	    	type='datetime-local'/><br/>
    		<input type='submit' name='btn_Edit' id='btn_addnew' value='Spara'/>
    		</form>";
		}
	}
}
echo "<div class='row clearFix'>";
if(isset($singleEvent))
{
    echo $singleEvent;
}