 <?php

class NewsObject extends DataObject
{
	function __construct()
	{
		parent::__construct("news");
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

}

?>