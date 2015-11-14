<?php
class NewsObject implements DatabaseObject
{
	private $database;

	function __construct($db)
	{
		$this->database = $db;
	}

	public function fetchAllEntries()
	{
		$sql = "SELECT * FROM news ORDER BY added";
		$res = $this->database->queryAndFetch ( $sql );
		return $res;
	}

	public function getNewsWithOffset($offset, $limit)
	{
		$sql = "SELECT * FROM news ORDER BY added DESC LIMIT $offset, $limit";
		$res = $this->database->queryAndFetch ( $sql );
		return $res;
	}

	public function fetchSingleEntryById($id)
	{
		if($id === -1)
		{
			$sql = "SELECT * FROM news ORDER BY added LIMIT 1";
		}
		else
		{
			$sql = "SELECT * FROM news WHERE id=? LIMIT 1";
		}
		
		$params = array($id);
		$res = $this->database->queryAndFetch ( $sql, $params );
		if($this->database->RowCount() == 1)
		{
			return $res;	
		}
		return null;
	}

	public function removeSingleEntryById($id)
	{
		$sql = "DELETE FROM news WHERE id=?";
		$params = array($id);
		$this->database->ExecuteQuery ( $sql, $params );
	}

	public function editSingleEntryById($id, $params)
	{
		$sql = "UPDATE news SET title=?, content=?, author=?, added=? WHERE id=?";
		$this->database->ExecuteQuery($sql, $params);
	}

	public function addSingleEntry($params)
	{
		$sql = "INSERT INTO news (title, content, author, added) VALUES (?,?,?,?)";
		$this->database->ExecuteQuery($sql, $params);
	}

	public function isAllowedToDeleteEntry($id)
	{
		if (isset($_SESSION['privilege']) && $_SESSION['privilege'] === "2")
		{
			return true;
		}
		return false;
	}

	public function countAllRows()
	{
		$sql = "SELECT count(*) as rows FROM news";
		$result = $this->database->queryAndFetch($sql);
		return $result[0]->rows;
	}
}

?>