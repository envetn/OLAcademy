<?php
include ("DataObject.php");
include ("Registered.php");
class EventObject extends DataObject
{
    private $registered;

    function __construct()
    {
        parent::__construct( "events" );

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

    public function getEvents($start, $end)
    {
        $sql = "SELECT *, DATE_FORMAT(startTime, '%H:%i') AS startTime FROM events WHERE eventDate BETWEEN ? AND ? ORDER BY eventDate, startTime";
        $params = array($start, $end);

        $events = $this->database->queryAndFetch( $sql, $params );
        $result = array();
        if( $this->rowCount() > 0 )
        {
            $registered = $this->getRegistered($start, $end);
            foreach($events as $event)
            {
                $eventRegistered = issetor($registered[$event->id], array());
                $result[$event->eventDate][$event->id] = array("eventData" => $event, "registered" => $eventRegistered);
            }
        }
        return $result;
    }
    
    public function updateEvents()
    {
        $sql = "SELECT *
                FROM events
                WHERE eventDate BETWEEN ? AND ?
                AND reccurance=1";

        $currentDate = date( 'Y-m-d', strtotime( date( "Y-m-d" ) . ' -1 day' ) );
        $prev_date = date( 'Y-m-d', strtotime( $currentDate . ' -90 day' ) );
        $params = array($prev_date, $currentDate );

        $res = $this->database->queryAndFetch( $sql, $params );
        if( $this->rowCount() > 0 )
        {
            foreach( $res as $event )
            {
                    // append 7 days.
                    $newDate = date( 'Y-m-d', strtotime( $event->eventDate . ' + 7 day' ) );

                    $values = array('reccurance' => 0 );
                    $condition = array('id' => $event->id );
                    parent::editSingleEntry( $values, $condition ); //Change reccurence to 0 on old event
                    
                    $params = array('eventName' => $event->eventName,
                    		'info' => $event->info,
                    		'startTime' => $event->startTime,
                    		'eventDate' => $newDate,
                    		'reccurance' => "1",
                    		'bus' => $event->bus,
                    		'createdBy' => $event->createdBy );
                    
                    parent::insertEntyToDatabase($params);
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

    public function getRegistered($start, $end)
    {
        $sql = " SELECT * FROM registered WHERE date BETWEEN ? AND ?";
        $params = array($start, $end);
        $registered = $this->database->queryAndFetch( $sql, $params );
        $result = array();
        if( $this->rowCount() > 0 )
        {
            foreach($registered as $person)
            {
                $result[$person->eventID][] = $person;
            }
        }
        return $result;
    }
}

?>
