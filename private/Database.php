<?php

    class Database
    {

        private static $db;

        function __construct()
        {

            //Calls the createConnection() function in order to create the connection to the database.
            self::createConnection();

        }

        private static function createConnection(){

            //Pull db credentials from a .ini file in the private folder.
            $config = parse_ini_file('db.ini');

            $username = $config['username'];
            $password = $config['password'];
            $hostName = $config['servername'];
            $dbName = $config['dbname'];
            $dsn = 'mysql:host='.$hostName.';dbname='.$dbName.';';


            try {

                //Create connection
                self::$db = new  PDO($dsn, $username, $password);
            } catch (PDOException  $e) {

                $error_message = $e->getMessage();
                echo $error_message;
            }
        }

//        //Use this function to get the database object
//        public function getConnection(){
//            return $this->db;
//        }

    }


