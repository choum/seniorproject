<?php

    class SQLStudent
    {

        function __construct()
        {
            
        }

        //This function is used to update some information of student user accounts
        //Is called in the student-controller file after validation is complete.
        function updateUser($userID, $bio = null, $imageLink = null,
            $linkedin = null, $website = null)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $db->beginTransaction();
                $query = "Update UserAccount "
                    . "SET Bio=:bio, ImageLink=:image, "
                    . "LinkedIn=:linkedin, Website=:website "
                    . "WHERE UserID=:uid;";
                $statement = $db->prepare($query);
                $statement->bindValue(':bio', $bio, PDO::PARAM_STR);
                $statement->bindValue(':image', $imageLink, PDO::PARAM_STR);
                $statement->bindValue(':linkedin', $linkedin, PDO::PARAM_STR);
                $statement->bindValue(':website', $website, PDO::PARAM_STR);
                $statement->bindValue(':uid', $userID, PDO::PARAM_INT);
                $statement->execute();
                $count = $statement->rowCount();
                $statement->closeCursor();

                if ($count == 1):
                    $db->commit();
                    return "User updated";
                else:
                    $db->rollback();
                    throw new PDOException();
                endif;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "User not updated";
            }
        }
        
        //Purpose of this function is to retrieve the UserID based on the username
        //Is used in the student-controller file.
        function getUserID($username)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "SELECT UserID FROM UserAccount  WHERE Username = :uName";
                $statement = $db->prepare($query);
                $statement->bindValue(':uName', $username, PDO::PARAM_STR);
                $statement->execute();
                $user = $statement->fetch();
                $userID = $user['UserID'];
                return $userID;
            } catch (Exception $e)
            {
                return 'User not found';
            }
        }
        
        //Purpose of this function is to add a student assignment submission to the database
        //Used by the readdStudentAssignment function and student-controller file
        function addStudentAssignment($studentID, $assignmentID, $dir,
            $dateCreated, $screenshot, $featured, $group)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "INSERT INTO Student_Assignment "
                    . "(StudentID, AssignmentID, `Directory`, "
                    . "DateCreated, Screenshot, Featured, `Group`) "
                    . "VALUES(:sID, :aID, :dir, :date, :screen, "
                    . ":featured, :group);";
                $statement = $db->prepare($query);
                $statement->bindValue(':sID', $studentID, PDO::PARAM_INT);
                $statement->bindValue(':aID', $assignmentID, PDO::PARAM_INT);
                $statement->bindValue(':dir', $dir, PDO::PARAM_STR);
                $statement->bindValue(':date', $dateCreated);
                $statement->bindValue(':screen', $screenshot, PDO::PARAM_STR);
                $statement->bindValue(':featured', $featured, PDO::PARAM_BOOL);
                $statement->bindValue(':group', $group, PDO::PARAM_BOOL);
                $statement->execute();
                $statement->closeCursor();

                return "Student assignment created";
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not create student assignment";
            }
        }
        
        //Purpose of this function is to delete a student submission before readding it
        //using the addStudentAssignment function. 
        //Used by the student-controller file
        function readdStudentAssignment($studentID, $assignmentID, $dir,
            $dateCreated, $screenshot, $featured, $group)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $db->beginTransaction();
                $query = "DELETE FROM Student_Assignment "
                    . "Where StudentID=:sID And AssignmentID = :aID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':sID', $studentID, PDO::PARAM_INT);
                $statement->bindValue(':aID', $assignmentID, PDO::PARAM_INT);
                $statement->execute();
                $count = $statement->rowCount();
                $statement->closeCursor();

                if ($count == 1):
                    $db->commit();
                    $return = $this->addStudentAssignment($studentID, $assignmentID,
                        $dir, $dateCreated, $screenshot, $featured, $group);
                    return $return;
                else:
                    $db->rollBack();
                    throw new PDOException;
                endif;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Student assignment not updated.";
            }
        }
        
        //Purpose of this function is to reset all student assignment submissions
        //for a specific student to not be featured, and set a new assignment submission
        //to be featured.
        //Used in the student-controller file
        function changeFeaturedAssignment($studentID, $assignmentID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $db->beginTransaction();
                $query = "Update Student_Assignment Set Featured = 0 "
                    . "Where StudentID = :sID";
                $statement = $db->prepare($query);
                $statement->bindParam(':sID', $studentID, PDO::PARAM_INT);
                $statement->execute();
                $statement->closeCursor();

                $query = "Update Student_Assignment Set Featured = 1 "
                    . "Where StudentID = :sID AND AssignmentID = :aID";
                $statement = $db->prepare($query);
                $statement->bindParam(':sID', $studentID, PDO::PARAM_INT);
                $statement->bindParam(':aID', $assignmentID, PDO::PARAM_INT);
                $statement->execute();
                $count = $statement->rowCount();
                $statement->closeCursor();

                if ($count == 1):
                    $db->commit();
                    return "Featured assignment updated";
                else:
                    $db->rollback();
                    throw new PDOException;
                endif;
            } catch (PDOException $ex)
            {
                return "Featured Assignment could not be changed.";
            }
        }        
    }

?>