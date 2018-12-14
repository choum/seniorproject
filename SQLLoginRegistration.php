<?php
    /*
     * Created By: Justin Crest
     * Description: This file holds all of the SQL queries related to login, registration,
     * and the changing of ones password.
     */
    class SQLLoginRegistration
    {
        function __construct(){
            
        }
        
        //This function is used to add users to the database on valid account registration
        //Is called in the login-functions file after all proper validation is complete.
        function addUser(User $user)
        {
            try
            {
                if ($user->imageLink == null OR $user->imageLink == "")
                {
                    $user->imageLink = "default-profile.jpg";
                }
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Insert into UserAccount "
                    . "(Username, Password, FirstName, LastName, Title,"
                    . " Bio, Email, ImageLink, Linkedin, Website, UserRole, "
                    . "Suspended, DateCreated, LastLoggedIn) "
                    . "VALUES (:username, :password, :fName, :lName, :title,"
                    . " :bio, :email, :image, :linkedin, :website, :role, "
                    . ":suspend, :creation, :lastLogin);";
                $statement = $db->prepare($query);
                $statement->bindValue(':username', $user->username, PDO::PARAM_STR);
                $statement->bindValue(':password', $user->password, PDO::PARAM_STR);
                $statement->bindValue(':fName', $user->firstName, PDO::PARAM_STR);
                $statement->bindValue(':lName', $user->lastName, PDO::PARAM_STR);
                $statement->bindValue(':title', $user->title, PDO::PARAM_STR);
                $statement->bindValue(':bio', $user->bio, PDO::PARAM_STR);
                $statement->bindValue(':email', $user->email, PDO::PARAM_STR);
                $statement->bindValue(':image', $user->imageLink, PDO::PARAM_STR);
                $statement->bindValue(':linkedin', $user->linkedin, PDO::PARAM_STR);
                $statement->bindValue(':website', $user->website, PDO::PARAM_STR);
                $statement->bindValue(':role', $user->role, PDO::PARAM_INT);
                $statement->bindValue(':suspend', $user->suspended, PDO::PARAM_BOOL);
                $statement->bindValue(':creation', $user->dateCreated);
                $statement->bindValue(':lastLogin', $user->lastLoginDate);
                $statement->execute();
                $statement->closeCursor();

                return "User created";
            } catch (PDOException $e)
            {
                /* Specific error message for:
                 * SQL ERROR 1062 (Duplicate entry for unique field)
                 * SQL ERROR 1048 (Null entry for non-null field)
                 */
                if (strpos($e->getMessage(), "Integrity constraint violation: 1062") !== FALSE)
                        return "Username unavailable.";
                else if (strpos($e->getMessage(), "Integrity constraint violation: 1048") !== FALSE)
                        return "Null entry where not allowed.";
                else return "User not created.";
                //error_log($error_message, (int)0,"./error.txt");
            }
        }
        
        //This function is used to compare the user given password to the password stored in the DB
        //Along with this, the role is gotten in order to properly redirect the user on successful login
        //Used in the login-functions file
        function getUserAuth($username)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select Password, UserRole From UserAccount "
                    . "Where Username= :uname";
                $statement = $db->prepare($query);
                $statement->bindValue(':uname', $username, PDO::PARAM_STR);
                $statement->execute();
                $userPass = $statement->fetch();
                $statement->closeCursor();

                return $userPass;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retrieve user password";
            }
        }
        
        //This function is used to keep track of and update the user on their most recent login
        //As well as allow later functionality for certain actions to occur on first login.
        //Used in the login-functions file
        function updateLastLoggedIn($username, $loggedIn)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $db->beginTransaction();
                $query = "Select LastLoggedIn from UserAccount "
                    . "Where Username= :uname";
                $statement = $db->prepare($query);
                $statement->bindValue(':uname', $username, PDO::PARAM_STR);
                $statement->execute();
                if ($statement->rowCount() != 0):
                    $lastLoggedIn = $statement->fetch();
                    $statement->closeCursor();

                    $query = "UPDATE `UserAccount` "
                        . "SET `LastLoggedIn` = :loggedIn "
                        . "WHERE `Username` = :uname";
                    $statement = $db->prepare($query);
                    $statement->bindValue(':uname', $username, PDO::PARAM_STR);
                    $statement->bindValue(':loggedIn', $loggedIn);
                    $statement->execute();
                    $count = $statement->rowCount();
                    $statement->closeCursor();

                    if ($count == 1):
                        $db->commit();
                        if ($lastLoggedIn[0] == NULL OR $lastLoggedIn[0] == '0000-00-00 00:00:00'):
                            return "Welcome, this is the first time you've logged in!";
                        else:
                            return "Welcome back, you last logged in at $lastLoggedIn[0] Pacific Time";
                        endif;
                    else:
                        $db->rollBack();
                        throw new PDOException;
                    endif;
                else:
                    return "Could not retrieve previous login of user.";
                endif;
            } catch (PDOException $e)
            {
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retrieve/update last login";
            }
        }
        
        //Purpose of this function is to check if the username desired
        //for account regisrtation is available. 
        //Used in the login-functions file
        function checkForDuplicate($username)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select Username from UserAccount "
                    . "Where Username= :uname";
                $statement = $db->prepare($query);
                $statement->bindValue(':uname', $username, PDO::PARAM_STR);
                $statement->execute();
                $count = $statement->rowCount();
                $statement->closeCursor();

                if ($count > 0):
                    return false;
                else:
                    return true;
                endif;
            } catch (PDOException $e)
            {
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not check username";
            }
        }
        
        //Purpose of this function is to update a users password,given that the
        //username and current password match database records.
        //All validation of new password is done prior to this function call
        //Is used in the login functions file
        function changePassword($username, $password, $newPassword)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $db->beginTransaction();
                $query = "Select username From UserAccount "
                    . "Where Username= :uname AND Password = :pword";
                $statement = $db->prepare($query);
                $statement->bindValue(':uname', $username, PDO::PARAM_STR);
                $statement->bindValue(':pword', $password, PDO::PARAM_STR);
                $statement->execute();
                $count = $statement->rowCount();
                $statement->closeCursor();

                if ($count == 1):
                    $query = "Update UserAccount "
                        . "Set Password= :newPass "
                        . "Where Username= :uname AND Password = :pword";
                    $statement = $db->prepare($query);
                    $statement->bindValue(':uname', $username, PDO::PARAM_STR);
                    $statement->bindValue(':pword', $password, PDO::PARAM_STR);
                    $statement->bindValue(':newPass', $newPassword, PDO::PARAM_STR);
                    $statement->execute();
                    $count = $statement->rowCount();
                    $statement->closeCursor();

                    if ($count == 1):
                        $db->commit();
                        return "Password changed.";
                    else:
                        $db->rollBack();
                        throw new PDOException;
                    endif;
                else:
                    return "Username/password credentials incorrect.";
                endif;
            } catch (PDOException $e)
            {
                //error_log($error_message, (int)0,"./error.txt");
                //return "Could not retrieve user password";
                return "Password could not be changed.";
            }
        }
    }

