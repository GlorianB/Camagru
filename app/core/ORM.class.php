<?php

/**
 * This class handle every exchange between object models and database
 */

class ORM
{

    /**
     * @var DB: database name
     */
    private $DB;

    /**
     * @var conn : PDO connection instance
     */
    private $conn;

    /**
     * @var instance: class instance for Singleton
     */
    private static $instance = NULL;

    private function __construct()
    {
        try
        {
            require_once dirname(__DIR__) . "/../config/database.php";
            $this->DB = $DB_NAME;
            $this->conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("USE camagru");
        }
        catch (PDOEXCEPTION $e)
        {
            $msg = 'PDO error in ' . $e->getFile() . ' line' . $e->getLine() . ' : ' . $e->getMessage();
            exit($msg); 
        }
    }

    public static function getInstance()
    {
        if (is_null(self::$instance))
            self::$instance = new ORM;
        return self::$instance;
    }

    public function select($table, $where = array())
    {
        $query = "SELECT * FROM " . $table . " WHERE 1 = 1";
        foreach ($where as $column => $value)
            $query .= " AND " . $column . " = :" . $column;
        $stmt = $this->conn->prepare($query);
        foreach ($where as $column => $value)
            $stmt->bindValue(":" . $column, $value);
        $stmt->execute();
        $stmt->setFetchMode($this->conn::FETCH_CLASS, ucfirst($table) . "Model");
        return ($result = $stmt->fetch($this->conn::FETCH_CLASS));
    }

    public function selectAll($table, $where, $order = NULL, $limit = NULL)
    {
        $query = "SELECT * FROM " . $table . " WHERE 1 = 1";
        foreach ($where as $column => $value)
            $query .= " AND " . $column . " = :" . $column;
        if (!empty($order))
            $query .= " ORDER BY ". $order[0] . " " . $order[1];
        if (!empty($limit))
            $query .= " LIMIT ". $limit[0] . ", " . $limit[1];
        $stmt = $this->conn->prepare($query);
        foreach ($where as $column => $value)
            $stmt->bindValue(":" . $column, $value);
        $stmt->execute();
        return ($result = $stmt->fetchAll($this->conn::FETCH_ASSOC));
    }

    public function insert($table, $fields, $values)
    {
        $query = "INSERT INTO " . $table . " (";
        for ($i = 0; $i < sizeof($fields); $i++)
        {
            $query .= $fields[$i];
            if ($i + 1 != sizeof($fields))
                $query .= ", ";
        }
        $query .= ") VALUES (";
        for ($i = 0; $i < sizeof($fields); $i++)
        {
            $query .= ":" . $fields[$i];
            if ($i + 1 != sizeof($fields))
                $query .= ", ";
        }
        $query .= ")";
        $stmt = $this->conn->prepare($query);
        foreach ($values as $column => $value)
            $stmt->bindValue(":" . $column, $value);
        $stmt->execute();
        return ($this->conn->lastInsertId());
    }

    public function update($table, array $set, array $where)
    {
        $query = "UPDATE " . $table . " SET ";
        foreach ($set as $column => $value)
            $query .= $column . " = :" . $column . ($value != end($set) ? ", " : "");
        $query .= " WHERE 1 = 1";
        foreach ($where as $column => $value)
            $query .= " AND " . $column . " = :" . $column;
        $stmt = $this->conn->prepare($query);
        foreach ($set as $column => $value)
            $stmt->bindValue(":" . $column, $value);
        foreach ($where as $column => $value)
            $stmt->bindValue(":" . $column, $value);
        $stmt->execute();
        return (true);
    }

    public function delete($table, $where)
    {
        $query = "DELETE FROM " . $table . " WHERE  1 = 1";
        foreach ($where as $column => $value)
            $query .= " AND " . $column . " = :" . $column;
        $stmt = $this->conn->prepare($query);
        foreach ($where as $column => $value)
            $stmt->bindValue(":" . $column, $value);
        $stmt->execute();
        return (true);
    }

    public function count($table, $where)
    {
        $query = "SELECT COUNT(*) FROM " . $table . " WHERE 1 = 1";
        foreach ($where as $column => $value)
            $query .= " AND " . $column . " = :" . $column;
        $stmt = $this->conn->prepare($query);
        foreach ($where as $column => $value)
            $stmt->bindValue(":" . $column, $value);
       $stmt->execute();
       $result = $stmt->fetch();
       return $result[0];
    }
}
?>