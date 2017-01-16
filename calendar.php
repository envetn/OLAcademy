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
        if ($event == null)
        {
            return "";
        }
        $createdBy = $user->fetchUsernameById($event->createdBy);

        $singleEvent = "<div class='eventPost' id='calendarRegisterEvent'>
                            <div class='eventHeader'>
                                <span class='eventName'>" . $event->eventName . "</span>
                                <span class='eventTime'>" . $event->startTime . "</span>
                            </div>";

        
        $singleEvent .= "<span class='eventInfo'>" . $event->info . "<br>Av: " .$createdBy. " </span>
                            <form method='POST' style='padding:7px;' >
                                <input type='hidden' name='eventID' value=" . $event->id . ">
                                <input type='hidden' name='date' value=" . $event->eventDate . ">";

        $userId = isset( $_SESSION["uid"] ) ? $_SESSION["uid"] : false;
        if( $user->isLoggedIn() )
        {
            $regCondition["userID"] = $userId;
            $regCondition["eventID"] = $event->id;

            $res = $eventObject->getRegisteredByValue( $regCondition );

            if( $res == null )
            {
                $singleEvent .= "<button type='submit' class='btn btn-primary regInput' name='register' value='Anmäl'>Anmäl</button>";
            }
            
            if($user->isAllowedToEditEvent($event->createdBy))
            {
            	$singleEvent .= "<span class='right'><button type='submit' class='btn btn-primary regInput' name='Edit' value='Edit'>Editera</button>
                				 <button type='submit' class='btn btn-primary regInput' name='Remove' value='Ta bort'>Ta bort</button></span>";
            }

            $singleEvent .= "</form>";
        }
        else
        {
            $singleEvent .= "<span class='error'> Logga in för att anmäla dig</span>";
        }

        $registered = $eventObject->getRegisteredByValue(array('eventId' => $condition["id"]));
        if(count($registered) > 0)
        {
            $singleEvent .= "<span class='eventInfo'>" . getRegisteredUsersTable($registered, $event->bus, $userId) . "</span>";
        }
        return $singleEvent .= "</div>";
    }
    return "";
}

function fetchRegisteredAtEvent($events, $month, $year)
{
    $html = "";
    foreach($events as $event)
    {
        $nrOfRegistered = count($event['registered']);
        $html .= "<a href='calendar.php?event=" . $event['eventData']->id . "&month=$month&year=$year'>
        <p class='event_p'>" . $event['eventData']->eventName ." [" . $nrOfRegistered . "]</p></a>";
    }
    return $html;
}

function makeCalendarPaging($month, $year)
{
    $months = array(1 => 'Januari', 'Februari', 'Mars', 'April', 'Maj', 'Juni', 'Juli', 'Augusti', 'September', 'Oktober', 'November', 'December');
    $prev_month = ($month - 2 + 12) % 12 + 1;
    $next_month = ($month + 12) % 12 + 1;
    $prev_year = ($prev_month == 12 ? $year-1 : $year);
    $next_year = ($next_month == 1 ? $year+1 : $year);
    $calendar_paging = "<div id='calendar_paging'>";
    $calendar_paging .= "<span class='switch_month left'><a href='?month=$prev_month&year=$prev_year'>".$months[$prev_month]."</a></span>";
    $calendar_paging .= "<span id='current_month'>".$months[(int)$month]."</span>";
    $calendar_paging .= "<span class='switch_month right'><a href='?month=$next_month&year=$next_year'>".$months[$next_month]."</a></span>";
    $calendar_paging .= "</div>";
    return $calendar_paging;
}


function draw_calendar($month, $year, $userLoggedIn, $eventObject)
{
    $prev_month = ($month - 2 + 12) % 12 + 1;
    $running_day = date( 'N', mktime( 0, 0, 0, $month, 1, $year));
    $days_in_month = date( 't', mktime( 0, 0, 0, $month, 1, $year));
    $days_in_prev_month = date( 't', mktime( 0, 0, 0, $prev_month, 1, $year));
    $prev_days = $days_in_prev_month - $running_day + 2;
    $day_counter = 0;
    $first_day = date("Y-m-d", mktime(0,0,0,$month, 1, $year));
    $last_day = date("Y-m-d", mktime(0,0,0,$month, $days_in_month, $year));
    $events = $eventObject->getEvents($first_day, $last_day);

    $calendar = "<div id='calendar'>";

    /* print week days */
    $weekdays = array('Måndag', 'Tisdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lördag', 'Söndag' );
    $calendar .= "<div class='week'>";
    for($i = 0; $i < 7; $i++)
    {
        $calendar .= "<div class='week_day'>$weekdays[$i]</div>";
    }
    $calendar .= "</div>";

    /* print days from previous month until the first of the current week */
    $calendar .= "<div class='week'>";
    for($i = 1; $i < $running_day; $i++)
    {
        $day_number = "<span class='day_number'>$prev_days</span>";
        $calendar .= "<div class='day prev_month_days'>$day_number</div>";
        $prev_days++;
    }

    /* keep going with days.... */
    for($list_day = 1; $list_day <= $days_in_month; $list_day++)
    {
        $running_date = date( 'Y-m-d', mktime( 0, 0, 0, $month, $list_day, $year ) );
        $addEvent_btn = "";
        if($userLoggedIn)
        {
            $addEvent_btn = "<a class='eventAdd_btn' href='event.php?a=1&day=" . $running_date . "'><img src='img/add.png' width=18px height=18px></a>";
        }
        $day_number = "<span class='day_number'>$list_day</span>";

        $calender_event = "";
        if(isset($events[$running_date]))
        {
            $calender_event = fetchRegisteredAtEvent($events[$running_date], $month, $year);
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
