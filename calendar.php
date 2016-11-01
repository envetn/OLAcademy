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

function fetchRegisteredAtEvent($eventResult, $registered, $month, $ear)
{
	$htmlReg = "";
	foreach($eventResult as $row)
	{
		$nrOfRegistered = 0;
		foreach($registered as $person)
		{
			if($row->id == $person->eventID)
			{
				$nrOfRegistered ++;
			}
		}
		$htmlReg .= "<a href='calendar.php?event=" . $row->id . "&month=$month&year=$ear'>
		<p class='event_p'>" . $row->eventName ." [" . $nrOfRegistered . "]</p></a>";
	}
	return $htmlReg;
}

function makeCalendarPaging($month, $year)
{
	$months = array('Januari', 'Februari', 'Mars', 'April', 'Maj', 'Juni', 'Juli', 'Augusti', 'September', 'Oktober', 'November', 'December');
	$prev_month = ($month - 2 + 12) % 12 + 1;
	$next_month = ($month + 12) % 12 + 1;
	$prev_year = ($prev_month == 12 ? $year-1 : $year);
	$next_year = ($next_month == 1 ? $year+1 : $year);
	$calendar_paging = "<div id='calendar_paging'>";
	$calendar_paging .= "<span class='switch_month left'><a href='?month=$prev_month&year=$prev_year'>".$months[$prev_month-1]."</a></span>";
	$calendar_paging .= "<span class='switch_month right'><a href='?month=$next_month&year=$next_year'>".$months[$next_month-1]."</a></span>";
	$calendar_paging .= "</div>";
	return $calendar_paging;
}


function draw_calendar($month, $year, $userLoggedIn, $eventObject)
{
	$events = $eventObject->getEventByGivenMonth( $month );
	$registered = $eventObject->fetchAllRegisteredGivenMonth($month);
	
	$prev_month = ($month - 2 + 12) % 12 + 1;
	$running_day = date( 'N', mktime( 0, 0, 0, $month, 1, $year));
	$days_in_month = date( 't', mktime( 0, 0, 0, $month, 1, $year));
	$days_in_prev_month = date( 't', mktime( 0, 0, 0, $prev_month, 1, $year));
	$prev_days = $days_in_prev_month - $running_day + 2;
	$day_counter = 0;
	
	$calendar = "<div id='calendar'>";
	
	/* print week days */
	$weekdays = array('Måndag', 'Tisdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lördag', 'Söndag' );
	$calendar .= "<div class='week'>";
	for($x = 0; $x < 7; $x++)
	{
		$calendar .= "<div class='week_day'>$weekdays[$x]</div>";
	}
	$calendar .= "</div>";
	
	/* print days from previous month until the first of the current week */
	$calendar .= "<div class='week'>";
	for($x = 1; $x < $running_day; $x++)
	{
		$day_number = "<span class='day_number'>$prev_days</span>";
		$calendar .= "<div class='day prev_month_days'>$day_number</div>";
		$prev_days++;
	}

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++)
	{
		$addEvent_btn = "";
		if($userLoggedIn)
		{
			$addEvent_btn = "<a class='eventAdd_btn' href='event.php?a=1&day=" . $list_day . "'><img src='img/add.png' width=18px height=18px></a>";
		}
		$day_number = "<span class='day_number'>$list_day</span>";
		
		$calender_event = "";
		if($events != null)
		{
			$current_date = date( 'Y-m-d', mktime( 0, 0, 0, $month, $list_day, $year ) );
			$eventResult = getEventByDate($events, $current_date);
			$calender_event = fetchRegisteredAtEvent($eventResult, $registered, $month, $year);
		}
		
		$calendar .= "<div class='day'>" . $day_number . $addEvent_btn . $calender_event ."</div>";

		if( $running_day == 7 )
		{
			$calendar .= "</div>";
			if( ($day_counter + 1) != $days_in_month )
			{
				$calendar .= "<div class='week'>";
			}
			$running_day = 0;
			$days_in_this_week = 0;
		}
		$running_day++;
		$day_counter++;
	}
	
	/* print days from next month until the end of week */
	if($running_day != 1)
	{
		$next_days = 1;
		for($x = $running_day; $x < 8; $x++)
		{
			$calendar .= "<div class='day next_month_days'>$next_days</div>";
			$next_days++;
		}
	}
	$calendar .= "</div>";
	$calendar .= "</div>";
	return $calendar;
}

$month = (isset($_GET['month']) ? $_GET['month'] : date('m', strtotime('0 month')));
$year = (isset($_GET['year']) ? $_GET['year'] : date('Y', strtotime('0 year')));

registerUserToEvent( $user, $eventObject );
displayError();

echo makeCalendarPaging($month, $year);
echo draw_calendar($month, $year, $user->isLoggedIn(), $eventObject);
echo showSingleCalendarEvent( $user, $eventObject );

include ("include/footer.php");
?>
