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

	function getNrOfRegisteredByValue($condition)
	{
		$query = $this->validateGetCount($condition);
		$res = $this->database->queryAndFetch($query['sql'], $query['params'] );
		if ($this->database->rowCount() > 0)
		{
			return $res[0]->count;
		}
		return 0;
	}

	private function validateGetCount($condition)
	{
		$sql = "SELECT COUNT(DISTINCT userID) AS count FROM registered";
		if(!empty($condition))
		{
			$nextIterator = new ArrayIterator($condition);
			$nextIterator->rewind();
			$nextIterator->next();
			$sql .= " WHERE ";

			foreach( $condition as $name => $value )
			{
				$next_val = $nextIterator->current();
				$sql .= $name . self::EQUAL_SIGN . self::QUESTION_MARK;
				$params[] = $value;

				if(strlen($next_val) > 0)
				{
					$sql .= " AND ";
				}
				$nextIterator->next();
			}
		}
		else
		{
			$sql .= " ORDER BY id DESC LIMIT 1";
		}
		$query = array('sql' => $sql, 'params' => $params);
		return $query;
	}

	public function removeSingleRegistered($id)
	{
		$sql = "DELETE FROM registered WHERE eventID=?";
		$params = array($id);
		$this->database->ExecuteQuery($sql, $params);
	}
}