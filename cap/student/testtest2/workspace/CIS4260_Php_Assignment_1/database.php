<?php

class DBAccess
{

    public static function getMysqliConnection()
    {
        try {
            $serverName = "localhost";
            $databaseName = "BookCatalog";
            $username = "testtest3";
            $password = "qweiop123";
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $db = new mysqli($serverName, $username, $password, $databaseName);
            return $db;
        }
        catch (Exception $e){
            $error = $e->getMessage();
            echo '<p>Database connection error. ' . $error . '</p>';
            return null;
        }

    }

    public static function getPDOConnection()
    {
        try {
            $serverName = "localhost";
            $databaseName = "BookCatalog";
            $dsn = 'mysql:host=localhost;dbname=BookCatalog';
            $username = "php";
            $password = "php";
//            $db = new mysqli($serverName, $username, $password, $databaseName);
            $db = new PDO($dsn, $username, $password);
//            echo "Connection succesful";
            return $db;
        }
        catch (Exception $e){
            $error = $e->getMessage();
            echo '<p>Database connection error. ' . $error . '</p>';
            return null;
        }

    }
}
