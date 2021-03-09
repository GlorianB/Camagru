<?php

if (isset($_GET['load']))
{
    require_once "database.php";
    try {
        $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = file_get_contents("database.sql");
        $rows_affected = $pdo->exec($query);
        $query = file_get_contents("insert.sql");
        $rows_affected += $pdo->exec($query);
    }
    catch (PDOEXCEPTION $e)
    {
        $msg = 'PDO error in ' . $e->getFile() . ' line' . $e->getLine() . ' : ' . $e->getMessage();
        exit($msg); 
    }
    echo "Database setup OK.<br><br>" . $rows_affected . " rows affected.";
}
else
    echo '<a href="?load">Delete and recreate database</a>';

?>