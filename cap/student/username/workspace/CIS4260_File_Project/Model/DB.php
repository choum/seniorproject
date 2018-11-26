<?php
    $dsn = 'mysql:host=172.16.33.106;dbname=username_supplies_store';
    $username = 'username';
    $password = 'password';
    
    try {
        $db = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include('../Error/database_error.php');
        exit();
    }
?>

