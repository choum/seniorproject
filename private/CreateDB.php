<?php

    class CreateDB{

        public function createDBUser($user, $pass){

            include 'Database.php';
            $db = new Database();
            $conn = $db->getConnection();

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbName = $user;
            echo $dbName;
            $dbPassword = $pass;

            try{



//                $createDatabase = "CREATE DATABASE `$dbName`";
//                $test = $conn->exec($createDatabase);
//                if($test === false){
//                    echo "Could not create a database...";
//                    exit;
//                }
//                else{
//                    echo "You good. We got the database";
//                }

                $createUser = "CREATE USER '$dbName'@'%' IDENTIFIED BY '$dbPassword'";
                $test = $conn->exec($createUser);
                if($test === false){
                    echo "Could not create a user...";
                    exit;
                }
                else{
                    echo "You good. We got the user";
                }

                $grantPrivileges = "GRANT ALL PRIVILEGES ON `".$dbName."\_%` .  * TO '".$dbName."'@'%'";
                $test = $conn->exec($grantPrivileges);
                if($test === false){
                    echo "Could not grant a user...";
                    exit;
                }
                else{
                    echo "You good. We got the grant";
                }

//                $grantPrivileges = "GRANT ALL ON `$dbName`.* TO '$dbName'@'localhost'";
//                $test = $conn->exec($grantPrivileges);
//                if($test === false){
//                    echo "Could not grant privileges...";
//                    exit;
//                }
//                else{
//                    echo "You good. We got the privileges";
//                }

            }
            catch(Exception $e){
                echo $e;
            }
        }


    }


?>
