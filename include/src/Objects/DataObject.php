<?php
include(INCLUDE_PATH . "/src/Database/database.php");
class DataObject
{
	protected $database;
	private $table;

	public function __construct($table)
	{
		global $GLOBAL;
		$this->database = new Database($GLOBAL['database']);;
		$this->table = $table;
		
	}

	public function fetchAllEntries($orderBy = "")
	{
		$sql = " SELECT * FROM ". $this->table;
		if($orderBy != "")
		{
			$sql .= " ORDER BY " .$orderBy;
		}
		$result = $this->database->queryAndFetch($sql);
		return $result;
	}

	public function fetchSingleEntryById($id)
	{
		$sql = "SELECT * FROM ". $this->table;
		
		if($id === -1)
		{
			$sql .= " ORDER BY added DESC LIMIT 1";
		}
		else
		{
			$sql .= " WHERE id=? LIMIT 1";
		}

		$params = array($id);
		$result = $this->database->queryAndFetch($sql, $params);
		if ($this->database->RowCount() == 1)
		{
			return $result[0];
		}
		return null;
	}

	public function fetchEntryWithOffset($offset, $limit)
	{
		$sql = "SELECT * FROM ". $this->table . " ORDER BY added DESC LIMIT $offset, $limit";
		$res = $this->database->queryAndFetch ( $sql );
		return $res;
	}

	public function removeSingleEntryById($id)
	{
		$sql = "DELETE FROM ". $this->table . " WHERE id=? LIMIT 1";
		$this->database->ExecuteQuery($sql, array($id));
		return true;
	}
	
	public function countAllRows()
	{
		$sql = "SELECT count(*) as rows FROM ". $this->table;
		$result = $this->database->queryAndFetch($sql);
		return $result[0]->rows;
	}
	
	// 	public function editSingleEntryById($id, $params)
	// 	{
	
	// 	}
	
	// 	public function addSingleEntry($params)
	// 	{
	
	// 	}
	
	// 	public function isAllowedToDeleteEntry($id)
	// 	{
	
	// 	}
}