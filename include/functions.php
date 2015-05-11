<?php
function linux_server()
{
    return in_array(strtolower(PHP_OS), array("linux", "superior operating system"));
}


// Insert post in guestbook
function makePost($db, $name, $text)
{
	// Remove html tags
	$name = strip_tags($name);
	$text = strip_tags($text);

	//Make clickable links
	preg_match_all('/http\:\/\/[\w\d\-\~\^\.\/]{1,}/',$text,$results);
	foreach($results[0] as $value)
	{
		$link = '<a href="' . $value . '" target="_blank">' . $value . '</a>';
		$text = preg_replace('/' . preg_quote($value,'/') . '/',$link,$text);
	}
	//Control message length
	$max_text_length = 2000;
	$max_name_length = 50;
	if (strlen($text) > $max_text_length)
	{
		$GLOBALS['error'] = "<pre class=red>Text must not exceed " . $max_text_length . " characters.</pre>";
	}
	elseif (strlen($name) > $max_name_length)
	{
		$GLOBALS['error'] = "<pre class=red>Name must not exceed " . $max_name_length . " characters.</pre>";
	}
	else
	{
		// Check if all fields are filled
		if(empty($name) or empty($text))
		{
			$GLOBALS['error'] = "<pre class=red>Fyll i alla fält.</pre>";
		}
		else
		{
			$sql = "INSERT INTO posts (name, text, date) VALUES(?,?,?)"; //Prepare SQL code
			$params = array($name, $text, date('Y-m-d H:i:s')); //Prepare query
			$db->ExecuteQuery($sql, $params, false); //Execute query
			header('Location: ' . $_SERVER['PHP_SELF']); //Refresh page
		}
	}
}


//Present posts from table and prepare paging
function presentPost($db, $offset, $limit) 
{
	$sql = "SELECT * FROM posts ORDER BY ID DESC LIMIT $offset, $limit"; //Prepare SQL code
	$result = $db->queryAndFetch($sql); //Execute query

	//Output data of each row
	foreach($result as $row)
	{		
		$name = $row->name;
		$text = $row->text;
		$date = $row->date;
		$text = nl2br($text); //Insert line breaks where newlines (\n) occur in the string:
		
		//Create html code for each row
		$post = "<div class='post'>
					<span class='name'>" . $name . " wrote:</span>
					<span class='date'>" . $date . "</span>
					<hr>
					<span class='text'>" . $text . "</span>
				</div>";
		echo $post;
		
	
	}
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
		$calendar.= '<td class="calendar-day">';
		/* add in the day number */
		$calendar.= '<div class="day-number">'.$list_day.'</div>';
		
		/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
		$current_date = date('Y-m-d', mktime(0,0,0,$month,$list_day,$year));
		//"SELECT * FROM events WHERE date= '$current_date' ORDER BY start_time";
		
		$sql = "SELECT eventName FROM events WHERE date= '$current_date' ORDER BY startTime"; //Prepare SQL code
		$result = $db->queryAndFetch($sql); //Execute query
		if (isset($result[0]->eventName))
		{
			foreach($result as $row)
			{
				$calendar.= "<p>" . $row->eventName . "</p>";
			}
		}
		
		$calendar.= str_repeat('<p> </p>',2);
			
		$calendar.= '</td>';
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

?>
