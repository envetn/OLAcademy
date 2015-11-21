<?php 
include ("DataObject.php");
include ("Registered.php");

class EventObject extends DataObject
{
	private $today;
	private $nextWeek;
	private $registered;

	function __construct()
	{
		parent::__construct("events");

		$this->today = date("Y-m-d");
		$this->nextWeek = date("Y-m-d", time() + (6 * 24 * 60 * 60));
		$this->registered = new Registered();
	}

	public function isAllowedToDeleteEntry($id)
	{
		$sql = "SELECT * FROM registered WHERE id=? AND userID=?";
		$params = array($id,$_SESSION['uid']);
		$res = $this->database->queryAndFetch($sql, $params);

		if ($this->rowResult() == 1)
		{
			return true;
		}
		return false;
	}

	function rowResult()
	{
		return $this->database->RowCount();
	}

	public function fetchEventByDay($day)
	{
		$sql = "SELECT eventName,id FROM events WHERE eventDate=? ORDER BY startTime"; //Prepare SQL code
		$params = array($day);
		$res = $this->database->queryAndFetch($sql, $params);
		if ($this->rowResult() > 0)
		{
			return $res;
		}
		return null;
	}

	public function fetchRegisteredByUserIdAndEventId($userId, $eventId)
	{
		$sql = "SELECT * FROM registered WHERE userID=? and eventID=?";
		$params = array($userId, $eventId);
		$res = $this->database->queryAndFetch($sql, $params);

		if ($this->rowResult() > 0)
		{
			return $res;
		}
		return null;
	}

	public function removeEventAndRegisteredById($id)
	{
		parent::removeSingleEntryById($id);

		// Clear all registered from updated event
		$sql = "DELETE FROM registered WHERE eventID=?";
		$this->database->ExecuteQuery($sql, array($id));
		return true;
	}

	public function getWeeklyEvents()
	{
		$sql = "
	        SELECT *
	        FROM events
	        WHERE eventDate BETWEEN ? AND ?
			ORDER BY eventDate
	        ";
		$params = array($this->today,$this->nextWeek);
		$result = $this->database->queryAndFetch($sql, $params);
		return $result;
	}

	function getCurrentMonthsEvents()
	{
		$sql = " SELECT * FROM events WHERE eventDate BETWEEN ? AND ?";
		$firstDay = (new DateTime('first day of this month'))->format('Y-m-d');
		$lastDay = (new DateTime('last day of this month'))->format('Y-m-d');
		$params = array($firstDay,$lastDay);
		$result = $this->database->queryAndFetch($sql, $params);

		if ($this->rowResult() > 0)
		{
			return $result;
		}
		return 0;
	}

	function updateEvents()
	{
		$sql = "
        SELECT id,eventDate,reccurance
        FROM events
        WHERE eventDate BETWEEN ? AND ?
        ";
		$currentDate = date('Y-m-d', strtotime($this->today . ' -1 day'));
		$prev_date = date('Y-m-d', strtotime($currentDate . ' -30 day'));
		$params = array($prev_date,$currentDate);

		$res = $this->database->queryAndFetch($sql, $params);
		if ($this->rowResult() > 0)
		{
			foreach ( $res as $event )
			{
				echo $event->reccurance;
				if ($event->reccurance === "1")
				{
					// Set new date.
					$eventDay = $event->eventDate;
					$newDate = date('Y-m-d', strtotime($eventDay . ' + 7 day'));
					$id = $event->id;
					$sql = "UPDATE events SET eventDate=? WHERE id=? LIMIT 1";
					$updateParams = array($newDate,$id);
					$this->database->ExecuteQuery($sql, $updateParams);

					// Clear all registered from updated event
					// duplicated in admin.php
					$sql = "DELETE FROM registered WHERE eventID=?";
					$this->database->ExecuteQuery($sql, array($id));
				}
			}
		}
	}

	// registered functions
	public function registerUserToEvent($params)
	{
		$this->registered->insertEntyToDatabase($params);
	}
	
	public function unRegisterUserToEvent($id)
	{
		$this->registered->removeSingleEntryById($id);
	}

	public function getRegisteredByValue($value = array())
	{
		// get content of Registered
		if($value['variable'] == 'id')
		{
			$res = $this->registered->getRegisteredById($value['value']);
			return $res;
		}
	}

	public function getNumberOfRegisteredByValue($value = array())
	{
		// get count from registered
		if($value['variable'] == 'id')
		{
			$res = $this->registered->getNrOfRegisteredById($value['value']);
		}
		else if($value['variable'] == 'date')
		{
			$res = $this->registered->getNrOfRegisteredbyDate($value['value']);			
		}
		return $res;
	}
}

?>