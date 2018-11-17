<?php
    class Database {

        public $db;
        
        function __construct() {

            $dsn  =  'mysql:host=172.16.33.106;dbname=capdb';  
            $username  =  'capdb';  
            $password  =  'portfolio4290';
            
            try    {      

                $this->db  =  new  PDO($dsn,  $username,  $password);
            } catch  (PDOException  $e)    {   

                $error_message  =  $e->getMessage();                         
            }  
        }
    }
?>