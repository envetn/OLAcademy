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

	public function insertEntyToDatabase($values)
	{
		$values['added'] = $this->today;
		parent::insertEntyToDatabase($values);
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