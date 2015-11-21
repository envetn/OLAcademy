<?php
$pageId ="calendar";
$pageTitle ="- kalender";
include("include/header.php");

function draw_calendar($month,$year, $userLoggedIn)
{
	$eventObject = new EventObject();
	/* draw table */
	$calendar = '<table class="calendar">';

	/* table headings */
	$headings = array('Måndag','Tisdag','Onsdag','Torsdag','Fredag','Lördag','Söndag');
	$calendar.= '<tr class="calendar-row"><th class="calendar-day-head">'.implode('</th><th class="calendar-day-head">',$headings).'</th></tr>';

	/* days and weeks vars now ... */
	$running_day = date('N',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;

	/* row for week one */
	$calendar.= '<tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for($x = 1; $x < $running_day; $x++)
	{
		$calendar.= '<td class="calendar-day-empty"> </td>';
		$days_in_this_week++;
	}

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++)
	{
		$calendar.= ' <td class="calendar-day"><a href="event.php">';
		
		$addEvent_btn = "";
		if($userLoggedIn)
		{
			$addEvent_btn = "<a class='eventAdd_btn' href='event.php?a=1&day=".$list_day."'><img src='img/add.png' width=18px height=18px></a>";
		}
		/* add in the day number */
		$calendar.= '<div class="day-number">'.$list_day . $addEvent_btn . "</p>";
		
		/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
		// Need to refactor this method..
		$current_date = date('Y-m-d', mktime(0,0,0,$month,$list_day,$year));

		$result = $eventObject->fetchEventByDay($current_date); //Execute query

		if (isset($result[0]->eventName))
		{
			foreach($result as $row)
			{
				$values = array('variable' =>'id', 'value' => $row->id);
				$calendar.= "<a href=''><p class='event_p'>" . $row->eventName . "<br/>Anmälda: ".$eventObject->getNumberOfRegisteredByValue($values)."</a>";
			}
		}
		$calendar .= '</div>';
		
		$calendar.= str_repeat('<p> </p>',2);
			
		$calendar.= '</td></a>';
		if($running_day == 7)
		{
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month)
			{
				$calendar.= '<tr class="calendar-row">';
			}
			$running_day = 0;
			$days_in_this_week = 0;
		}
		$days_in_this_week++; $running_day++; $day_counter++;
	}

	/* finish the rest of the days in the week */
	if($days_in_this_week < 8)
	{
		for($x = 1; $x <= (8 - $days_in_this_week); $x++)
		{
			$calendar.= '<td class="calendar-day-empty"> </td>';
		}
	}

	/* final row */
	$calendar.= '</tr>';

	/* end the table */
	$calendar.= '</table>';
	
	/* all done, return result */
	return $calendar;
}




$month = date('m', strtotime('0 month'));
$year = date('Y', strtotime('0 year'));
echo "<div class='row'>";
	echo "<div class='b'>";
		echo draw_calendar($month,$year, $user->isLoggedIn());
	echo "</div>";
echo "</div>";

?>



<?php include("include/footer.php"); ?>
