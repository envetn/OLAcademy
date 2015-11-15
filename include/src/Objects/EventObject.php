<?php
include ("DataObject.php");
class EventObject implements DatabaseObject
{
	private $database;
	private $today;
	private $nextWeek;

	function __construct($db)
	{
		$this->database = $db;
		$this->today = date ( "Y-m-d" );
		$this->nextWeek = date ( "Y-m-d", time () + (6 * 24 * 60 * 60) );
	}

	public function isAllowedToDeleteEntry($id)
	{
		$sql = "SELECT * FROM registered WHERE id=? AND userID=?";
		$params = array($id,$_SESSION['uid']);
		$res = $this->database->queryAndFetch ( $sql, $params );
		if ($this->rowResult() == 1)
		{
			return true;
		}
		return false;
	}
	
	public function countAllRows()
	{
	}

	function rowResult()
	{
		return $this->database->RowCount ();
	}

	public function fetchAllEntries()
	{
		$sql = " SELECT * FROM events ORDER BY date";
		$result = $this->database->queryAndFetch ( $sql );
		return $result;
	}

	public function fetchSingleEntryById($id)
	{
		$sql = "SELECT * FROM events WHERE id=? LIMIT 1";
		
		$params = array($id);
		$result = $this->database->queryAndFetch ( $sql, $params );
		if ($this->rowResult () == 1)
		{
			return $result[0];
		}
		return null;
	}

	public function removeSingleEntryById($id)
	{
		$sql = "DELETE FROM events WHERE id=? LIMIT 1";
		$this->database->ExecuteQuery ( $sql, array($id) );
		
		// Clear all registered from updated event
		$sql = "DELETE FROM registered WHERE eventID=?";
		$this->database->ExecuteQuery ( $sql, array($id) );
		return true;
	}
	
	public function removeSingleRegistered($id)
	{
		$sql = "DELETE FROM registered WHERE id=? LIMIT 1";
		$params = array($id);
		$this->database->ExecuteQuery($sql, $params);
		
	}

	public function editSingleEntryById($id, $params)
	{
		$sql = "UPDATE events SET eventName=?, info=?, startTime=?, date=?, reccurance=? WHERE id=?";
		$this->database->ExecuteQuery ( $sql, $params );
		return true;
	}

	public function addSingleEntry($params)
	{
		$sql = "INSERT INTO events (info, date, startTime, eventName, reccurance) VALUES(?,?,?,?,?)";
		$this->database->ExecuteQuery ( $sql, $params );
		return true;
	}

	public function getWeeklyEvents()
	{
		$sql = "
	        SELECT *
	        FROM events
	        WHERE date BETWEEN ? AND ?
			ORDER BY date
	        ";
		$params = array($this->today,$this->nextWeek);
		$result = $this->database->queryAndFetch ( $sql, $params );
		return $result;
	}

	function getCurrentMonthsEvents()
	{
		$sql = " SELECT * FROM events WHERE date BETWEEN ? AND ?";
		$firstDay = (new DateTime ( 'first day of this month' ))->format ( 'Y-m-d' );
		$lastDay = (new DateTime ( 'last day of this month' ))->format ( 'Y-m-d' );
		$params = array($firstDay,$lastDay);
		$result = $this->database->queryAndFetch ( $sql, $params );
		
		if ($this->rowResult () > 0)
		{
			return $result;
		}
		return 0;
	}

	function getNrOfRegistered($date)
	{
		$sql = "SELECT COUNT(DISTINCT userID) as count FROM registered WHERE date=?";
		$params = array($date);
		$result = $this->database->queryAndFetch ( $sql, $params );
		if ($this->rowResult () > 0)
		{
			return $result[0]->count;
		}
		return 0;
	}

	function getNrOfRegisteredById($id)
	{
		$sql = "SELECT COUNT(*) AS count FROM registered WHERE eventID=?";
		$params = array($id);
		$result = $this->database->queryAndFetch ( $sql, $params );
		if ($this->rowResult () > 0)
		{
			return $result[0]->count;
		}
		return 0;
	}

	function getRegisteredById($id)
	{
		$sql = "SELECT * FROM registered WHERE eventID=?";
		$params = array($id);
		$result = $this->database->queryAndFetch ( $sql, $params );
		return $result;
	}

	function updateEvents()
	{
		$sql = "
        SELECT id,date,reccurance
        FROM events
        WHERE date BETWEEN ? AND ?
        ";
		$currentDate = date ( 'Y-m-d', strtotime ( $this->today . ' -1 day' ) );
		$prev_date = date ( 'Y-m-d', strtotime ( $currentDate . ' -30 day' ) );
		
		$params = array($prev_date,$currentDate);
		$res = $this->database->queryAndFetch ( $sql, $params );
		if ($this->rowResult () > 0)
		{
			foreach ( $res as $event )
			{
				if ($event->reccurance == true)
				{
					// Set new date.
					$eventDay = $event->date;
					$newDate = date ( 'Y-m-d', strtotime ( $eventDay . ' + 7 day' ) );
					$id = $event->id;
					$sql = "UPDATE events SET date=? WHERE id=? LIMIT 1";
					$updateParams = array($newDate,$id);
					$this->database->ExecuteQuery ( $sql, $updateParams );
					
					// Clear all registered from updated event
					//duplicated in admin.php
					$sql = "DELETE FROM registered WHERE eventID=?";
					$this->database->ExecuteQuery ( $sql, array($id) );
				}
			}
		}
	}
}

?>