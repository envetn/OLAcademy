<?php
class GuestbookObject implements DatabaseObject
{
	private $database;
	private $today;

	public function __construct($db)
	{
		$this->database = $db;
		$this->today = date("Y-m-d H:i:s");
	}

	public function fetchAllEntries()
	{
		// TO be implemented
	}

	public function fetchSingleEntryById($id)
	{
		//TO be implemented
	}

	public function removeSingleEntryById($id)
	{
		$sql = "DELETE FROM posts WHERE id=?";
		$params = array($id);
		$this->database->ExecuteQuery($sql, $params);
	}

	public function editSingleEntryById($id, $params)
	{
		// TO be implemented
	}

	public function addSingleEntry($params)
	{
		$sql = "INSERT INTO posts (name, text, date) VALUES(?,?,?)";
		$params[] = $this->today;
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
		$sql = "SELECT count(*) as rows FROM posts";
		$result = $this->database->queryAndFetch($sql);
		return $result[0]->rows;
	}

	public function getPostsWithOffset($offset, $limit)
	{
		$sql = "SELECT * FROM posts ORDER BY ID DESC LIMIT $offset, $limit";
		$res = $this->database->queryAndFetch($sql);
		return $res;
	}
}