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
		parent::__construct( "events" );

		$this->today = date( "Y-m-d" );
		$this->nextWeek = date( "Y-m-d", time() + (6 * 24 * 60 * 60) );
		$this->registered = new Registered();
	}

	public function isAllowedToDeleteEntry($id)
	{
		$sql = "SELECT * FROM registered WHERE id=? AND userID=?";
		$params = array($id, $_SESSION["uid"] );
		$res = $this->database->queryAndFetch( $sql, $params );

		if( $this->RowCount() == 1 )
		{
			return true;
		}
		return false;
	}

	public function removeEventAndRegisteredById($id)
	{
		parent::removeSingleEntryById( $condition = array("id" => $id ) );

		$this->registered->removeSingleRegistered( $id );

		return true;
	}

	public function getWeeklyEvents()
	{
		$sql = "
	        SELECT id, eventDate, DATE_FORMAT(startTime, '%H:%i') AS startTime, eventName, info, reccurance, bus
	        FROM events
	        WHERE eventDate BETWEEN ? AND ?
			ORDER BY eventDate
	        ";
		$params = array($this->today, $this->nextWeek );
		
		return $this->database->queryAndFetch( $sql, $params );
	}

	public function getCurrentMonthsEvents($orderBy = "")
	{
		$sql = " SELECT * FROM events WHERE eventDate BETWEEN ? AND ?";
		if( $orderBy != "" )
		{
			$sql .= " ORDER BY " . $orderBy;
		}
		$firstDay = (new DateTime( 'first day of this month' ))->format( 'Y-m-d' );
		$lastDay = (new DateTime( 'last day of this month' ))->format( 'Y-m-d' );
		$params = array($firstDay, $lastDay );
		$result = $this->database->queryAndFetch( $sql, $params );

		if( $this->rowCount() > 0 )
		{
			return $result;
		}
		return 0;
	}

	public function updateEvents()
	{
		$sql = "
        SELECT id,eventDate,reccurance
				FROM events
				WHERE eventDate BETWEEN ? AND ?
				AND reccurance=1";

		$currentDate = date( 'Y-m-d', strtotime( $this->today . ' -1 day' ) );
		$prev_date = date( 'Y-m-d', strtotime( $currentDate . ' -90 day' ) );
		$params = array($prev_date, $currentDate );

		$res = $this->database->queryAndFetch( $sql, $params );
		if( $this->rowCount() > 0 )
		{
			foreach( $res as $event )
			{
				if( $event->reccurance === "1" )
				{
					// Set new date.
					$newDate = date( 'Y-m-d', strtotime( $event->eventDate . ' + 7 day' ) );

					$values = array('eventDate' => $newDate );
					$condition = array('id' => $event->id );
					parent::editSingleEntry( $values, $condition );

					$this->registered->removeSingleRegistered( $event->id );
				}
			}
		}
	}

	// registered functions
	public function registerUserToEvent($params)
	{
		$this->registered->insertEntyToDatabase( $params );
	}

	public function unRegisterUserToEvent($id)
	{
		$this->registered->removeSingleEntryById( $condition = array("id" => $id ) );
	}

	public function unRegisterUserToEventByValue($eventId)
	{
		$this->registered->removeSingleRegistered( $eventId );
	}

	public function getRegisteredByValue($condition = array())
	{
		$res = $this->registered->fetchAllEntriesByValue( $condition );
		return $res;
	}

	public function getNumberOfRegisteredByValue($value)
	{
		$res = $this->registered->fetchNumberOfEntriesByValue( $value );
		return $res;
	}

	public function fetchAllRegistered()
	{
		$orderBy = "eventID";
		$res = $this->registered->fetchAllEntries( $orderBy );
		return $res;
	}
}

?>