<?php

    class Database {

        function __construct() {

            $dsn  =  'mysql:host=localhost;dbname=my_guitar_shop1';  
            $username  =  'mgs_user';  
            $password  =  'pa55word';
            
            try    {      

                $db  =  new  PDO($dsn,  $username,  $password);  
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
?>