<?php
include (INCLUDE_PATH . "/src/Database/Database.php");
abstract class DataObject
{
    protected $database;
    private $table;
    private $useWildCard;
    const SEPERATOR = ',';
    const QUESTION_MARK = '?';
    const WILDCARD = '%';
    const EQUAL_SIGN = "=";
    const START_PARENTHESES = '(';
    const END_PARENTHESES = ')';

    public function __construct($table)
    {
        global $GLOBAL;
        $this->database = new Database( $GLOBAL["database"] );
        $wildCard = false;
        $this->table = $table;
    }

    public function fetchAllEntries($orderBy = "")
    {
        $sql = " SELECT * FROM " . $this->table;
        if( $orderBy != "" )
        {
            $sql .= " ORDER BY " . $orderBy; // why doesn't this work with '?'
        }
        $result = $this->database->queryAndFetch( $sql );
        return $result;
    }

    public function fetchAllEntriesByValue($condition = array(), $values = array())
    {
        // Building SELECT value(s) FROM table WHERE condition(s)
        $query = $this->validateSelectValues( $condition, $values );

        $res = $this->database->queryAndFetch( $query["sql"], $query["params"] );

        if( $this->database->RowCount() >= 1 )
        {
            return $res;
        }

        return null;
    }

    public function fetchSingleEntryByValue($condition = array(), $values = array())
    {
        // Building SELECT value(s) FROM table WHERE condition(s)
        $query = $this->validateSelectValues( $condition, $values );

        $res = $this->database->queryAndFetch( $query["sql"], $query["params"] );
        if( $this->database->RowCount() == 1 )
        {
            return $res[0];
        }

        return null;
    }

    public function fetchNumberOfEntriesByValue($array)
    {
        // Building SELECT COUNT(DISTINCT userID) AS count FROM registered WHERE cond = ?
        $query = $this->validateSelectCount( $array );
        $res = $this->database->queryAndFetch( $query['sql'], $query['params'] );

        if( $this->database->RowCount() > 0 )
        {
            return $res[0]->count;
        }

        return 0;
    }
    
    public function insertEntyToDatabase($values)
    {
        $query = $this->validateInputParametersData( $values );
        $sql = $query["sql"];
        $params = $query["params"];
        return $this->database->ExecuteQuery( $sql, $params);
    }

    public function editSingleEntry($values, $condition)
    {
        $query = $this->validateUpdateParameters( $values, $condition );

        $sql = $query["sql"];
        $params = $query["params"];
        $this->database->ExecuteQuery( $sql, $params );

        return true;
    }

    public function fetchEntryWithOffset($offset, $limit, $orderby ="added")
    {
        $sql = "SELECT * FROM " . $this->table . " ORDER BY $orderby DESC LIMIT $offset, $limit";
        $res = $this->database->queryAndFetch( $sql );

        return $res;
    }

    public function removeSingleEntryById($condition = array())
    {
        $sql = "DELETE FROM " . $this->table;
        $query = $this->validateConditionForSql( $condition );

        $sql .= $query["sql"];
        $params = $query["params"];
        $this->database->ExecuteQuery( $sql, $params );

        return true;
    }

    public function rowCount()
    {
        return $this->database->RowCount();
    }

    public function countAllRows()
    {
        $sql = "SELECT count(*) as rows FROM " . $this->table;
        $result = $this->database->queryAndFetch( $sql );

        return $result[0]->rows;
    }
    
    public function useWildCard()
    {
        $this->useWildCard = true;
    }
    
    public function unsetWildCard()
    {
        $this->useWildCard = false;
    }

    private function createArrayIterator($array)
    {
        $nextIterator = new ArrayIterator( $array );
        $nextIterator->rewind();
        $nextIterator->next();

        return $nextIterator;
    }

    private function validateSelectValues($condition, $select)
    {
        $sql = "SELECT ";
        $params = array();

        if( ! empty( $select ) )
        {
            $nextIterator = $this->createArrayIterator( $select );

            foreach( $select as $name => $value )
            {
                $next_val = $nextIterator->current();
                $sql .= $value;
                if( strlen( $next_val ) > 0 )
                {
                    $sql .= self::SEPERATOR;
                }
                $nextIterator->next();
            }
        }
        else
        {
            $sql .= "*";
        }

        $sql .= " FROM " . $this->table;
        $query = $this->validateConditionForSql( $condition);
        $sql .= $query["sql"];
        $params = $query["params"];
        $query = array('sql' => $sql, 'params' => $params );

        return $query;
    }

    private function validateConditionForSql($condition)
    {
        $sql = "";
        $params = array();

        if( ! empty( $condition ) )
        {
            $nextIterator = $this->createArrayIterator( $condition );
            $sql .= " WHERE ";

            foreach( $condition as $name => $value )
            {
                $next_val = $nextIterator->current();
                if($this->useWildCard)
                {
                    $sql .= $name . " LIKE " . self::QUESTION_MARK;
                    $value .=  self::WILDCARD;
                    //WHERE name LIKE value%
                }
                else
                {
                    $sql .= $name . self::EQUAL_SIGN . self::QUESTION_MARK;
                    // WHERE name=?
                }
                
                $params[] = $value;

                if( strlen( $next_val ) > 0 )
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

        return array("sql" => $sql, "params" => $params );
    }
    
    private function validateSelectCount($condition)
    {
        $sql = "SELECT COUNT(DISTINCT userID) AS count FROM registered";
        $params = array();
    
        if( ! empty( $condition ) )
        {
            $nextIterator = $this->createArrayIterator( $condition );
            $sql .= " WHERE ";
    
            foreach( $condition as $name => $value )
            {
                $next_val = $nextIterator->current();
                $sql .= $name . self::EQUAL_SIGN . self::QUESTION_MARK;
                $params[] = $value;
    
                if( strlen( $next_val ) > 0 )
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
        $query = array('sql' => $sql, 'params' => $params );
        return $query;
    }

    private function validateUpdateParameters($values, $condition)
    {
        // Building UPDATE table SET column=?, column=?, column=? WHERE condition=?
        $sql = "UPDATE " . $this->table . " SET ";
        $params = array();
        $nextIterator = $this->createArrayIterator( $values );

        foreach( $values as $name => $value )
        {
            $next_val = $nextIterator->current();

            $sql .= $name . self::EQUAL_SIGN . self::QUESTION_MARK;
            $params[] = $value;
            if( strlen( $next_val ) > 0 )
            {
                $sql .= self::SEPERATOR;
            }
            $nextIterator->next();
        }

        foreach( $condition as $name => $value )
        {
            $sql .= " WHERE " . $name . self::EQUAL_SIGN . self::QUESTION_MARK;
            $params[] = $value;
            break;
        }
        $query = array('sql' => $sql, 'params' => $params );

        return $query;
    }

    private function validateInputParametersData($values)
    {
        // Building INSERT INTO news (column, column, column, column) VALUES (?,?,?,?)";
        $sql = "INSERT INTO " . $this->table . " " . self::START_PARENTHESES;
        $sqlValues = " VALUES " . self::START_PARENTHESES;
        $params = array();
        $nextIterator = $this->createArrayIterator( $values );

        foreach( $values as $name => $value )
        {
            $next_val = $nextIterator->current();

            $sql .= $name;
            $sqlValues .= self::QUESTION_MARK;
            $params[] = $value;
            if( isset( $next_val ) )
            {
                $sql .= self::SEPERATOR;
                $sqlValues .= self::SEPERATOR;
            }
            $nextIterator->next();
        }

        $sql .= self::END_PARENTHESES . $sqlValues . self::END_PARENTHESES;
        $query = array('sql' => $sql, 'params' => $params );

        return $query;
    }
}
