<?php
$pageId ="calendar";
$pageTitle ="- kalender";
include("include/header.php");

function getNrOfRegistered($db, $eventId)
{
    $sql = "SELECT COUNT(*) as count FROM registerd WHERE eventId=?";
    $params = array($eventId);
    $res = $db->queryAndFetch($sql, $params);
    return $res[0]->count;
}
function draw_calendar($db, $month,$year)
{
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
		$calendar.= ' <td class="calendar-day"><a href="register.php">';
		/* add in the day number */
		$calendar.= '<div class="day-number">'.$list_day;
		
		/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
		$current_date = date('Y-m-d', mktime(0,0,0,$month,$list_day,$year));
		$sql = "SELECT eventName,id FROM events WHERE date= '$current_date' ORDER BY startTime"; //Prepare SQL code
		$result = $db->queryAndFetch($sql); //Execute query
		if (isset($result[0]->eventName))
		{
			foreach($result as $row)
			{
			    echo $row->id;
				$calendar.= "<p class='event_p'>" . $row->eventName . "<br/>Anmälda: ".getNrOfRegistered($db, $row->id)."</p>";
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
echo "<div class='row clearFix'>";
	echo "<div class='b'>";
		echo draw_calendar($db, $month,$year);
	echo "</div>";
echo "</div>";

?>



<?php include("include/footer.php"); ?>
