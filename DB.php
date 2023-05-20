<?php

class DB
{
    private $host;
    private $username;
    private $password;
    private $database;
    private $conn;
    private $results;

    private $last_id;

    public function __construct($host = 'localhost', $username, $password, $database)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;

        $this->connect();
    }

    public function connect()
    {
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            $this->conn->set_charset( "utf8" );
        } catch ( Exception $e ) {
            // Log the error details
            // error_log('Unable to connect to the database. Error: ' . $e->getMessage());

            // Display a generic error message to the user
            die('Unable to connect to the database. Error: ' . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function closeConnection()
    {
        $this->conn->close();
    }

    public function query($sql, $fetchMode = 'all')
    {   
        $separate = explode(' ', strtolower(trim($sql)));

        switch ($separate[0]) {
            case 'select':
                $result = $this->query_stmt($sql);
                if (!$result) {
                    die("Query failed: " . $this->conn->error);
                }
                return $this->fetch($result, $fetchMode);
                break;
            case 'insert':
            case 'update':
            case 'delete':
                $query = $this->query_stmt($sql);
                $this->last_id = $this->conn->insert_id;
                return $query;
                break;
            default:
                return 'Command not found';
        }

    }

    public function query_stmt($sql)
    {
        $result = $this->conn->query($sql);

        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        return $result; // is the statement object OR fail (Must do fetch for transform "statement object" to "array" before using)
    }

    public function fetch($result, $fetchMode = 'all')
    {
        if ($result->num_rows === 0) {
            return null;
        }
        
        switch ($fetchMode) {
            case 'assoc':
                return $result->fetch_assoc();
            case 'array':
                return $result->fetch_array();
            case 'object':
                return $result->fetch_object();
            case 'all':
                $rows = array(); 
                while ($row = $result->fetch_assoc()) { 
                    $rows[] = $row; 
                } 
                return $rows;
            default:
                return $result->fetch_assoc();
        }
    }

    public function generateInsertString($table, $data, $where = '')
    {
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", $data) . "'";
        
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";

        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        
        return $sql;
    }

    public function generateUpdateQuery($table, $data, $where = '')
    {
        $set = '';
        foreach ($data as $column => $value) {
            $set .= "$column = '$value', ";
        }
        $set = rtrim($set, ', ');
        
        $sql = "UPDATE $table SET $set";

        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        
        return $sql;
    }

    public function generateDeleteQuery($table, $where = '')
    {  
        $sql = "DELETE FROM $table";

        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        
        return $sql;
    }

    // $table = 'your_table';
    // $data = [
    //     'column1' => 'value1',
    //     'column2' => 'value2',
    //     // Add more column-value pairs as needed
    // ];
    // $where = "column3 = 'value3'"; // Example WHERE condition

    // $sql = generateInsertQuery($table, $data, $where);
    
    public function lastId()
    {          
        return (!empty($this->last_id))? $this->last_id : null;
    }
    
}
?>
