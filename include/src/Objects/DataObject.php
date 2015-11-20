<?php
include (INCLUDE_PATH . "/src/Database/Database.php");
abstract class DataObject
{
	protected $database;
	private $table;
	private $sql;
	const SEPERATOR = ',';
	const QUESTION_MARK = '?';
	const START_PARENTHESES = '(';
	const END_PARENTHESES	= ')';

	public function __construct($table)
	{
		global $GLOBAL;
		$this->database = new Database($GLOBAL['database']);
		$this->table = $table;
	}

	public function fetchAllEntries($orderBy = "")
	{
		$this->sql = " SELECT * FROM " . $this->table;
		if ($orderBy != "")
		{
			$this->sql .= " ORDER BY " . $orderBy;
		}
		$result = $this->database->queryAndFetch($this->sql);
		return $result;
	}

	public function fetchSingleEntryByValue($values = array())
	{
		$this->sql = "SELECT * FROM " . $this->table;
		$params = array();

		if (isset($values['variable']))
		{
			$this->sql .= " WHERE " . $values['variable'] . "=? LIMIT 1";
			$params[] = $values['value'];
		}
		else
		{
			$this->sql .= " ORDER BY id DESC LIMIT 1";
		}

		$res = $this->database->queryAndFetch($this->sql, $params);

		if ($this->database->RowCount() == 1)
		{
			return $res[0];
		}
		return null;
	}

	private function validateParametersData($values)
	{
		// Building INSERT INTO news (column, column, column, column) VALUES (?,?,?,?)";
		$nextIterator = new ArrayIterator($values);
		$nextIterator->rewind();
		$nextIterator->next();

		$sql = "INSERT INTO " . $this->table . " (";
		$sqlValues = " VALUES " . self::START_PARENTHESES;
		$params = array();

		foreach( $values as $name => $value )
		{
			$next_val = $nextIterator->current();

			$sql .= $name;
			$sqlValues .= self::QUESTION_MARK;
			$params[] = $value;
			if (strlen($next_val) > 0)
			{
				$sql .= self::SEPERATOR;
				$sqlValues .= self::SEPERATOR;
			}
			$nextIterator->next();
		}

		$sql .= self::END_PARENTHESES . $sqlValues . self::END_PARENTHESES;
		$query = array('sql' => $sql, 'params' => $params);

		return $query;
	}

	public function insertEntyToDatabase($values)
	{
		$query = $this->validateParametersData($values);

		$sql = $query['sql'];
		$params = $query['params'];
		$this->database->ExecuteQuery($sql, $params);

		return true;
	}

	public function fetchEntryWithOffset($offset, $limit)
	{
		$this->sql = "SELECT * FROM " . $this->table . " ORDER BY added DESC LIMIT $offset, $limit";
		$res = $this->database->queryAndFetch($this->sql);
		return $res;
	}

	public function removeSingleEntryById($id)
	{
		$this->sql = "DELETE FROM " . $this->table . " WHERE id=? LIMIT 1";
		$this->database->ExecuteQuery($this->sql, array($id));
		return true;
	}

	public function rowCount()
	{
		return $this->database->RowCount();
	}

	public function countAllRows()
	{
		$this->sql = "SELECT count(*) as rows FROM " . $this->table;
		$result = $this->database->queryAndFetch($this->sql);
		return $result[0]->rows;
	}
}