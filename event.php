<?php
$pageTitle = "- Träningar";
include ("include/header.php");
$eventObject = new EventObject();

if( ! isset( $_SESSION["Previous_page"] ) )
{
	// How to validate which page you are comming from?
	// If the user refreshes the page, the HTTP_REFERER will be restored
	// Also, if user enters url to event manually HTTP_REFERER is null
	$_SESSION["Previous_page"] = isset( $_SERVER["HTTP_REFERER"] ) ? $_SERVER["HTTP_REFERER"] : 'http://localhost/Webb/Olacademy/calendar.php';
}

function validateDay($day)
{
	if( ! is_numeric( $day ) )
	{
		return date( 'Y-m-d' );
	}

	if( strlen( $day ) == 1 ) // 8 -> 08
	{
		$day = "0" . $day;
	}

	$day = date( 'Y-m' ) . "-" . $day;
	return $day;
}

function fetchEventAndValidateGET($eventObject)
{
	$singleEvent = "";
	if( validateIntGET( "event" ) )
	{
		$values = array("id" => $_GET["event"] );
		$res = $eventObject->fetchSingleEntryByValue( $values );

		if( $res != null )
		{
			$reccurance = $res->reccurance == '1' ? "checked" : "";
			$bus = $res->bus == '1' ? "checked" : "";
			$data = array("eventName" => $res->eventName, "info" => $res->info, "startTime" => $res->startTime, "eventDate" => $res->eventDate, "bus" => $bus,
					"reccurance" => $reccurance, "button" => "btn_edit", "action" => "Updatera" );

			$singleEvent .= generateEventForm( $data );
			$singleEvent .= " <input name='id' value='" . $res->id . "'type='hidden'/>";
		}
	}
	else if( validateIntGET( "a" ) )
	{
		$date = isset( $_GET["day"] ) ? $date = validateDay( $_GET["day"] ) : $date = date( 'Y-m-d' );
		$time = date( 'H:i:s', time() - date( 'Z' ) );

		$data = array("startTime" => $time, "eventDate" => $date, "button" => "btn_add", "action" => "Spara" );
		$singleEvent .= generateEventForm( $data );
	}
	return $singleEvent;
}

function generateEventForm($data)
{
	$form = "<form id='form_addNew' method='post' enctype='multipart/form-data'>";
	$form .= "<input name='eventName' placeholder='Träning' value='" . is_set( $data, "eventName" ) . "'  type='text'/><br/>";
	$form .= "<input name='info' placeholder='Information' value='" . is_set( $data, "info" ) . "'  type='text'/><br/>";
	$form .= "<input name='startTime' value='" . is_set( $data, "startTime" ) . "'  type='time'/><br/>";
	$form .= "<input name='date' value='" . is_set( $data, "eventDate" ) . "' type='datetime-local'/><br/>";
	$form .= "<label>Buss: </label><input type='checkbox' name='bus' value='bus' " . is_set( $data, "bus" ) . " class='checkbox_bus'/><br/>";
	$form .= "<label>Återkommande: </label><input class='checkbox_bus' type='checkbox' name='reccurance' value='reccurance' " . is_set( $data, "reccurance" ) . "/><br/>";
	$form .= "<input class='btn btn-primary' type='submit' name='btn_event' id='btn_edit' value='" . is_set( $data, "action" ) . "'/>";

	return $form;
}

function is_set($data, $value)
{
	if( isset( $data[$value] ) )
	{
		return $data[$value];
	}
	return "";
}

function validateEventParams()
{
	if( validateStringPOST( "eventName" ) && validateStringPOST( "info" ) && validateStringPOST( "startTime" ) )
	{
		$eventName = strip_tags( $_POST["eventName"] );
		$info = strip_tags( $_POST["info"] );
		$startTime = strip_tags( $_POST["startTime"] );
		$date = strip_tags( $_POST["date"] );
		$reccurance = isset( $_POST["reccurance"] ) ? 1 : 0;
		$bus = isset( $_POST["bus"] ) ? 1 : 0;

		$params = array('eventName' => $eventName, 'info' => $info, 'startTime' => $startTime, 'eventDate' => $date, 'reccurance' => $reccurance, 'bus' => $bus );
		return $params;
	}
	else
	{
		return null;
	}
}

function validateEventAction($eventObject)
{
	if( validateStringPOST( "btn_event" ) )
	{
		$choice = $_POST["btn_event"];
		$params = validateEventParams();
		$success = false;

		if( $params != null )
		{
			if( $choice === "Updatera" )
			{
				$condition["id"] = validateIntPOST( "id" ) ? $_POST["id"] : - 1;
				$success = $eventObject->editSingleEntry( $params, $condition );
			}
			else if( $choice === "Spara" )
			{
				$success = $eventObject->insertEntyToDatabase( $params );
			}
		}

		if( $success )
		{
			return "<h4 id='infoHead'>Träning sparad </h4>";
		}
		else
		{
			populateError( "Det blev något fel, försök igen senare" );
		}
	}
}

if( $user->isAdmin() )
{
	$singleEvent = "";

	$singleEvent .= validateEventAction( $eventObject );
	$singleEvent .= "<h4 id='infoHead'> <a href='" . $_SESSION["Previous_page"] . "'> Tillbaka</a></h4>";
	$singleEvent .= fetchEventAndValidateGET( $eventObject );
	echo $singleEvent;
}
else
{
	populateError( "Du har inte behörighet att se sidan" );
}

echo "<div class='row clearFix'>";
displayError();
