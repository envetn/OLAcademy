<?php
$pageId = "calendar";
$pageTitle = "- kalender";
include ("include/header.php");
$eventObject = new EventObject();

function showSingleCalendarEvent($user, $eventObject)
{
	if( validateIntGET( "event" ) )
	{
		$condition["id"] = $_GET["event"];
		$event = $eventObject->fetchSingleEntryByValue( $condition );
		$createdBy = $user->fetchUsernameById($event->createdBy);
		
		$singleEvent = "<div class='eventPost' id='calendarRegisterEvent'>
							<div class='eventHeader'>
								<span class='eventName'>" . $event->eventName . "</span>
								<span class='eventTime'>" . $event->startTime . "</span>
							</div>
							<span class='eventInfo'>" . $event->info . "<br>Av: " .$createdBy. " </span>
							<form method='POST' style='padding:7px;' >
								<input type='hidden' name='eventID' value=" . $event->id . ">
								<input type='hidden' name='date' value=" . $event->eventDate . ">";

		if( $user->isLoggedIn() )
		{
			$userId = isset( $_SESSION["uid"] ) ? $_SESSION["uid"] : false;
			$regCondition["userID"] = $userId;
			$regCondition["eventID"] = $event->id;

			$res = $eventObject->getRegisteredByValue( $regCondition );
			if( $res == null )
			{
				$singleEvent .= "<button type='submit' class='btn btn-primary regInput' name='register' value='Anmäl'>Anmäl</button>";
			}
			else
			{
			    $singleEvent .= "<span class='info'> Du är redan anmäld</span>
							<button type='submit' class='btn btn-primary regInput' name='unRegister' value='Avanmäl'>Avanmäl</button>";
			}

			if($user->isAllowedToEditEvent($event->createdBy))
			{
			    $singleEvent .= "<button type='submit' style='float:right' class='btn btn-primary regInput' name='Edit' value='Edit'>Editera</button>";
			}

			$singleEvent .= "</form>";
		}
		else
		{
			$singleEvent .= "<span class='error'> Logga in för att anmäla dig</span>";
		}
		return $singleEvent .= "</div>";
	}
	return "";
}

function getEventByDate($events, $date)
{
	$eventResult = array();
	$times = 0;
	foreach( $events as $event )
	{
		if( $event->eventDate == $date )
		{
			$eventResult[] = $event;
			$times ++;
			if( $times >= 4 )
			{
				break; // max 4 events per day..
			}
		}
	}
	return $eventResult;
}

function fetchRegisteredAtEvent($eventResult, $eventObject)
{
	$registered = "";
	if( ! empty( $eventResult ) )
	{
		foreach( $eventResult as $row )
		{
			$values = array("eventID" => $row->id );
			$registered .= "<a href='calendar.php?event=" . $row->id . "'><p class='event_p'>" . $row->eventName . "<br/>Anmälda: " . $eventObject->getNumberOfRegisteredByValue(
					$values ) . "</p></a>";
		}
	}
	return $registered;
}

function draw_calendar($month, $year, $userLoggedIn, $eventObject)
{
	$events = $eventObject->getCurrentMonthsEvents( "eventDate" );
	/* draw table */
	$calendar = '<table class="calendar">';

	/* table headings */
	$headings = array('Måndag', 'Tisdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lördag', 'Söndag' );
	$calendar .= '<tr class="calendar-row"><th class="calendar-day-head">' . implode( '</th><th class="calendar-day-head">', $headings ) . '</th></tr>';

	/* days and weeks vars now ... */
	$running_day = date( 'N', mktime( 0, 0, 0, $month, 1, $year ) );
	$days_in_month = date( 't', mktime( 0, 0, 0, $month, 1, $year ) );
	$days_in_this_week = 1;
	$day_counter = 0;

	/* row for week one */
	$calendar .= '<tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for($x = 1; $x < $running_day; $x ++)
	{
		$calendar .= '<td class="calendar-day-empty"> </td>';
		$days_in_this_week ++;
	}

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day ++)
	{
		$calendar .= ' <td class="calendar-day">';

		$addEvent_btn = "";
		if( $userLoggedIn )
		{
			$addEvent_btn = "<a class='eventAdd_btn' href='event.php?a=1&day=" . $list_day . "'><img src='img/add.png' width=18px height=18px></a>";
		}
		/* add in the day number */
		$calendar .= '<div class="day-number">' . $list_day . $addEvent_btn;

		/**
		 * QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !! IF MATCHES FOUND, PRINT THEM !! *
		 */
		// Need to refactor this method..
		$current_date = date( 'Y-m-d', mktime( 0, 0, 0, $month, $list_day, $year ) );

		if($events != null)
		{
		      $eventResult = getEventByDate( $events, $current_date );
    		  $calendar .= fetchRegisteredAtEvent( $eventResult, $eventObject );
		}

		$calendar .= "</div></td>";

		// $calendar .= str_repeat("<p> </p>", 2); // ???

		if( $running_day == 7 )
		{
			$calendar .= "</tr>";
			if( ($day_counter + 1) != $days_in_month )
			{
				$calendar .= "<tr class='calendar-row'>";
			}
			$running_day = 0;
			$days_in_this_week = 0;
		}
		$days_in_this_week ++;
		$running_day ++;
		$day_counter ++;
	}

	/* finish the rest of the days in the week */
	if( $days_in_this_week < 8 )
	{
		for($x = 1; $x <= (8 - $days_in_this_week); $x ++)
		{
			$calendar .= '<td class="calendar-day-empty"></td>';
		}
	}

	/* final row */
	$calendar .= '</tr>';

	/* end the table */
	$calendar .= '</table>';

	/* all done, return result */
	return $calendar;
}

$month = date( 'm', strtotime( '0 month' ) );
$year = date( 'Y', strtotime( '0 year' ) );
registerUserToEvent( $user, $eventObject );
displayError();
echo "<div class='row'>";
echo "<div class='b' style='padding-left:20px;'>";
echo draw_calendar( $month, $year, $user->isLoggedIn(), $eventObject );
echo showSingleCalendarEvent( $user, $eventObject );
echo "</div>";

echo "</div>";

include ("include/footer.php");
?>
