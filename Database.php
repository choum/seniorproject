<?php

    class Database {

        function __construct() {

            $dsn  =  'mysql:host=172.16.33.106;dbname=capdb';  
            $username  =  'capdb';  
            $password  =  'portfolio4290';
            
            try    {      

                $db  =  new  PDO($dsn,  $username,  $password);  
                echo 'Success';
                
            } catch  (PDOException  $e)    {   

                $error_message  =  $e->getMessage();                         
            }  
        }

        function addUser() {}
        function updateUser() {}
        function addInstructor() {}
        function updateInstructor() {}  
        function addCourse() {}
        function updateCourse() {}  
        function getInstructors() {}
        function getCourses() {}
        function getTerms() {}
        

    }

    $test = new Database();
?>