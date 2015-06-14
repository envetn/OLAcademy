<?php

class Database
{

    private $options;
    private $db = null;
    private $stmt = null;
    private static $numQueries = 0;
    private static $params = array();
    private static $queries = array();
    // ** Construct **//
    public function __construct ($options)
    {
        $default = array( // default values for variables
                'dsn' => null,
                'username' => null,
                'password' => null,
                'driver_options' => null,
                'fetch_style' => PDO::FETCH_OBJ
        ); // fetch as object
        $this->options = array_merge($default, $options);
        try {
            $this->db = new PDO($this->options['dsn'], 
                    $this->options['username'], $this->options['password'], 
                    $this->options['driver_options']);
            $this->db->SetAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, 
                    $this->options['fetch_style']);
        } catch (Exception $e) {
            throw $e; // for debugging
            throw new PDOException('Could not connect');
        }
        // $this->db->SetAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,
    // $this->options['fetch_style']);
    }

    public function queryAndFetch ($query, $params = array(), $debug = false)
    {
        self::$queries[] = $query;
        self::$params[] = $params;
        self::$numQueries ++;
        if ($debug) {
            echo "<p>Query = <br/><pre>{$query}</pre></p><p>Num query = " .
                     self::$numQueries . "</p><p><pre>" . print_r($params, 1) .
                     "</pre></p>";
        }
        $this->stmt = $this->db->prepare($query);
        $this->stmt->execute($params);
        return $this->stmt->fetchAll();
    }

    /**
     * Get a html representation of all queries made, for debugging and
     * analysing purpose.
     *
     * @return string with html.
     */
    public function Dump ()
    {
        $html = '<p><i>You have made ' . self::$numQueries .
                 ' database queries.</i></p><pre>';
        foreach (self::$queries as $key => $val) 
        {
            $params = empty(self::$params[$key]) ? null : htmlentities(
                    print_r(self::$params[$key], 1)) . '<br/><br/>';
            $html .= $val . '<br/><br/>' . $params;
        }
        return $html . '</pre>';
    }

    /**
     * Execute a SQL-query and ignore the resultset.
     *
     * @param string $query
     *            the SQL query with ?.
     * @param array $params
     *            array which contains the argument to replace ?.
     * @param boolean $debug
     *            defaults to false, set to true to print out the sql query
     *            before executing it.
     * @return boolean returns TRUE on success or FALSE on failure.
     */
    public function ExecuteQuery ($query, $params = array(), $debug = false)
    {
        self::$queries[] = $query;
        self::$params[] = $params;
        self::$numQueries ++;
        if ($debug) {
            echo "<p>Query = <br/><pre>{$query}</pre></p><p>Num query = " .
                     self::$numQueries . "</p><p><pre>" . print_r($params, 1) .
                     "</pre></p>";
        }
        $this->stmt = $this->db->prepare($query);
        return $this->stmt->execute($params);
    }

    public function LastInsertId ()
    {
        return $this->db->lastInsertid();
    }

    /**
     * Save debug information in session, useful as a flashmemory when
     * redirecting to another page.
     *
     * @param string $debug
     *            enables to save some extra debug information.
     */
    public function SaveDebug ($debug = null)
    {
        if ($debug) {
            self::$queries[] = $debug;
            self::$params[] = null;
        }
        self::$queries[] = 'Saved debuginformation to session.';
        self::$params[] = null;
        $_SESSION['CDatabase']['numQueries'] = self::$numQueries;
        $_SESSION['CDatabase']['queries'] = self::$queries;
        $_SESSION['CDatabase']['params'] = self::$params;
    }

    /**
     * True mode: Return rows affected of last INSERT, UPDATE, DELETE
     * False mode: Return rows affected of last SELECT
     */
    public function RowCount ($syntax)
    {
		if ($syntax) return is_null($this->stmt) ? $this->stmt : $this->stmt->rowCount();
        else return is_null($this->stmt) ? $this->stmt : $this->stmt->fetchColumn();
    }

    public function getLastInsertedId ()
    {
        return $this->stmt->lastInsertId();
    }
}