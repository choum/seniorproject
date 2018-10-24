<?php

    class CreateDB{

        public static function createDBUser($user, $pass){

            include 'Database.php';
            $db = new Database();
            $conn = $db->getConnection();

            $dbName = $user;
            $dbPassword = $pass;

            try{
                $createDatabase = "CREATE DATABASE `$dbName`";
                $test = $conn->exec($createDatabase);
                if($test === false){
                    echo "Could not create a database...";
                    exit;
                }
                else{
                    echo "You good. We got the database";
                }

                $createUser = "CREATE USER '$dbName'@'localhost' IDENTIFIED BY '$dbPassword'";
                $test = $conn->exec($createUser);
                if($test === false){
                    echo "Could not create a user...";
                    exit;
                }
                else{
                    echo "You good. We got the user";
                }

                $grantPriveleges = "GRANT ALL ON `$dbName`.* TO '$dbName'@'localhost'";
                $test = $conn->exec($grantPriveleges);
                if($test === false){
                    echo "Could not grant priveleges...";
                    exit;
                }
                else{
                    echo "You good. We got the priveleges";
                }

            }
            catch(Exception $e){
                echo $e;
            }
        }

    }

?>
