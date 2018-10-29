<?php

    class Database
    {

        private $db;

        function __construct()
        {

            //Calls the createConnection() function in order to create the connection to the database.
            $this->createConnection();

        }

        private function createConnection(){

            //Pull db credentials from a .ini file in the private folder.
            $config = parse_ini_file('db.ini');

            $username = $config['username'];
            $password = $config['password'];
            $hostName = $config['servername'];
            $dbName = $config['dbname'];
            $dsn = 'mysql:host='.$hostName.';dbname='.$dbName.';';


            try {

                //Create connection
                $this->db = new  PDO($dsn, $username, $password);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException  $e) {

                $error_message = $e->getMessage();
                echo $error_message;
            }
        }

      //Use this function to get the database object
        public function getConnection(){
            return $this->db;
        }

    }


