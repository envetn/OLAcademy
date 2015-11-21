<?php
class Registered extends DataObject
{
	public function __construct()
	{
		parent::__construct("registered");
	}
	
	function getNrOfRegisteredbyDate($date)
	{
		$sql = "SELECT COUNT(DISTINCT userID) as count FROM registered WHERE date=?";
		$params = array($date);
		$result = $this->database->queryAndFetch($sql, $params);
		if ($this->database->rowCount() > 0)
		{
			return $result[0]->count;
		}
		return 0;
	}

	function getNrOfRegisteredById($id)
	{
		$sql = "SELECT COUNT(*) AS count FROM registered WHERE eventID=?";
		$params = array($id);
		$result = $this->database->queryAndFetch($sql, $params);
		if ($this->database->rowCount() )
		{
			return $result[0]->count;
		}
		return 0;
	}

	function getRegisteredById($id)
	{
		$sql = "SELECT * FROM registered WHERE eventID=?";
		$params = array($id);
		$result = $this->database->queryAndFetch($sql, $params);
		return $result;
	}

	public function removeSingleRegistered($id)
	{
		$sql = "DELETE FROM registered WHERE id=? LIMIT 1";
		$params = array($id);
		$this->database->ExecuteQuery($sql, $params);
	}
}