<?php
    /*
     * Created By: Nareg Khodanian
     * Description: Using the db.ini file, this class creates a connection to the main database located in phpmyadmin
     * It uses PDO in order to initalize the connection to the database.
     * Along with this, an attribute default has been set so that any failed queries through an Exception.
     */
    class Database
    {

        protected $db;

        function __construct()
        {

            //Calls the createConnection() function in order to create the connection to the database.
            $this->createConnection();

        }
        /* 
         * Purpose of this function is to create the initial connection to the database, which is then stored in an 
         * instance level object called db. It is here that access to the db.ini file and the setting of the 
         * attribute occurs.
         */
        private function createConnection()
        {

            //Pull db credentials from a .ini file in the private folder.
            $config = parse_ini_file('db.ini');

            $username = $config['username'];
            $password = $config['password'];
            $hostName = $config['servername'];
            $dbName = $config['dbname'];
            $dsn = 'mysql:host=' . $hostName . ';dbname=' . $dbName . ';';


            try {

                //Create connection
                $this->db = new  PDO($dsn, $username, $password);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException  $e) {

                $error_message = $e->getMessage();
                echo $error_message;
            }
        }

        //Use this function to get the database object which holds the connection.
        public function getConnection()
        {
            return $this->db;
        }

    }
