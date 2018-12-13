<?php
    /*
     * Created By: Nareg Khodanian
     * Description: Function of this class is to create database users in order to allow
     * access to a portion of phpmyadmin for the purpose of creating/importing databases 
     * and tables for the sole use of uploaded projects. 
     */
    class CreateDB{

        /*
         * This function creates the user account based on the username and password given on account registration.
         * Along with this, the newly created user is given complete access privileges to databases which 
         * follow the pattern of "username"_. This is done to isolate and restrict access to only certain
         * portions of phpmyadmin. Each user can only access and change databases that follow this naming convention. 
         */
        public function createDBUser($user, $pass){
            $db = new Database();
            $conn = $db->getConnection();

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbName = $user;
            $dbPassword = $pass;

            try{
                $createUser = "CREATE USER '$dbName'@'%' IDENTIFIED BY '$dbPassword'";
                $test = $conn->exec($createUser);
                if($test === false){
                    echo "Could not create a user...";
                    exit;
                }

                $grantPrivileges = "GRANT ALL PRIVILEGES ON `".$dbName."\_%` .  * TO '".$dbName."'@'%'";
                $test = $conn->exec($grantPrivileges);
                if($test === false){
                    echo "Could not grant a user...";
                    exit;
                }
            }
            catch(Exception $e){
                echo $e;
            }
        }


    }


?>
