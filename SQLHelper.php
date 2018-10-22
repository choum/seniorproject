<?php
require_once("./Database.php");
    Class SQLHelper
    {
        //Insertion of new student user to be added to the UserAccount Table
        //Done at first login.
        function addUser($userid, $username, $password,$firstName, $lastName, $title,
                $userRole, $suspended, $dateCreated,
                $bio = NULL, $imageLink = NULL, $linkedin = NULL, $website = NULL,
                $lastLoginDate = NULL) {
            try{
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Insert into UserAccount "
                        . "(UserID, Username, Password, FirstName, LastName, Title,"
                        . " Bio, ImageLink, Linkedin, Website, UserRole, "
                        . "Suspended, DateCreated, LastLoggedIn) "
                        . "VALUES (:uid, :username, :password, :fName, :lName, :title,"
                        . " :bio, :image, :linkedin, :website, :role, "
                        . ":suspend, :creation, :lastLogin);";
                $statement = $db->prepare($query);
                $statement->bindValue(':uid', $userid, PDO::PARAM_INT);
                $statement->bindValue(':username', $username, PDO::PARAM_STR);
                $statement->bindValue(':password', $password, PDO::PARAM_STR);
                $statement->bindValue(':fName', $firstName, PDO::PARAM_STR);
                $statement->bindValue(':lName', $lastName, PDO::PARAM_STR);
                $statement->bindValue(':title', $title, PDO::PARAM_STR);
                $statement->bindValue(':bio', $bio, PDO::PARAM_STR);
                $statement->bindValue(':image', $imageLink, PDO::PARAM_STR);
                $statement->bindValue(':linkedin', $linkedin, PDO::PARAM_STR);
                $statement->bindValue(':website', $website, PDO::PARAM_STR);
                $statement->bindValue(':role', $userRole, PDO::PARAM_INT);
                $statement->bindValue(':suspend', $suspended, PDO::PARAM_BOOL);
                $statement->bindValue(':creation', $dateCreated);
                $statement->bindValue(':lastLogin', $lastLoginDate);
                $result = $statement->execute();
                $statement->closeCursor();
                
                if($result)
                    Return "User created";
                else
                    Return "User not created";
            } catch(PDOException $e){
                $error_message = $e->getMessage();
                error_log($error_message);
            }

        }
        //Updating student user info based on update profile card, 
        //overwriting columns where new info was entered, 
        //or where info was not given, in the UserAccount table.
        //Done on student user side.
        function updateUser($userID, $bio = null, $imageLink = null, 
                $linkedin = null, $website = null) {
            try{
                $dbObj = new Database();
                $db = $dbObj->db;
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
                $result = $statement->execute();
                $statement->closeCursor();
                
                if($result)
                    Return "User updated";
                else
                    Return "User not updated";
            } catch (PDOException $e) {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }
        //Insertion of new instructor user to be added to the UserAccount Table
        //Done at first login.
        //TODO If query passes return empty string if fails, should go to catch return error statement
        function addInstructor($userid, $username, $password, $firstName, $lastName, $title,
                $userRole, $suspended, $dateCreated, $lastLoginDate = null) {
            try{
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Insert into UserAccount "
                        . "(UserID, Username, Password, FirstName, LastName, Title, "
                        . "UserRole, Suspended, DateCreated, LastLoggedIn) "
                        . "VALUES (:uid, :username, :password, :fName, :lName, :title,"
                        . " :role, :suspend, :creation, :lastLogin)";
                $statement = $db->prepare($query);
                $statement->bindValue(':uid', $userid, PDO::PARAM_INT);
                $statement->bindValue(':username', $username, PDO::PARAM_STR);
                $statement->bindValue(':password', $password, PDO::PARAM_STR);
                $statement->bindValue(':fName', $firstName, PDO::PARAM_STR);
                $statement->bindValue(':lName', $lastName, PDO::PARAM_STR);
                $statement->bindValue(':title', $title, PDO::PARAM_STR);
                $statement->bindValue(':role', $userRole, PDO::PARAM_INT);
                $statement->bindValue(':suspend', $suspended, PDO::PARAM_BOOL);
                $statement->bindValue(':creation', $dateCreated);
                $statement->bindValue(':lastLogin', $lastLoginDate);
                $result = $statement->execute();
                $statement->closeCursor();
                if($result)
                    Return "Instructor created";
                else
                    Return "Instructor not created";
            } catch(PDOException $e){
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }
        //Updating instructor user info based on update instructor card to the
        //UserAccount table.
        //Done on admin side.
        function updateInstructor($userID, $firstName, $lastName) {
            try{
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Update UserAccount "
                        . "SET FirstName=:fName, LastName=:lName "
                        . "WHERE UserID=:uid;";
                $statement = $db->prepare($query);
                $statement->bindValue(':fName', $firstName, PDO::PARAM_STR);
                $statement->bindValue(':lName', $lastName, PDO::PARAM_STR);
                $statement->bindValue(':uid', $userID, PDO::PARAM_INT);
                $results = $statement->execute();
                $statement->closeCursor();
                
                if($results)
                    Return "Instructor updated";
                else
                    Return "Instructor not updated";
            } catch(PDOException $e){
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }
        //Insertion of new course info to the Courses table
        //Done on admin side.
        function addCourse($courseID, $courseTitle, $courseNumber, $courseSection,
                $term, $description, $closed, $enrollment, $adminID, $teacherID) {
            try{
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "INSERT INTO Courses"
                        . "(CourseID, CourseTitle, CourseNumber, CourseSection, "
                        . "Term, Description, Closed, EnrollmentTotal, AdminID, "
                        . "TeacherID)"
                        . "VALUES(:cID, :cTitle, :cNumber, :cSection, :term, "
                        . ":desc, :closed, :enrolled, :adminID, :teacherID);";
                $statement = $db->prepare($query);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $statement->bindValue(':cTitle', $courseTitle, PDO::PARAM_STR);
                $statement->bindValue(':cNumber', $courseNumber, PDO::PARAM_INT);
                $statement->bindValue(':cSection', $courseSection, PDO::PARAM_INT);
                $statement->bindValue(':term', $term, PDO::PARAM_STR);
                $statement->bindValue(':desc', $description, PDO::PARAM_STR);
                $statement->bindValue(':closed', $closed, PDO::PARAM_BOOL);
                $statement->bindValue(':enrolled', $enrollment, PDO::PARAM_INT);
                $statement->bindValue(':adminID', $adminID, PDO::PARAM_INT);
                $statement->bindValue(':teacherID', $teacherID, PDO::PARAM_INT);
                $result = $statement->execute();
                $statement->closeCursor();
                
                if($result)
                    Return "Course created";
                else
                    Return "Course not created";
            } catch(PDOException $e){
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }
        //Update of specific columns in the Courses Table
        //Done on admin side.
        function updateCourse($courseID, $courseTitle,  $courseNumber, $courseSection, 
                $courseTerm, $adminID, $teacherID) {
            try{
                $dbObj = new Database(); 
                $db = $dbObj->db;
                $query = "Update Courses "
                        . "SET CourseTitle=:cTitle, CourseNumber=:cNumber, "
                        . "CourseSection=:cSection, Term=:cTerm, "
                        . "AdminID= :aID, TeacherID=:tID "
                        . "WHERE CourseID=:cID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':cTitle', $courseTitle, PDO::PARAM_STR);
                $statement->bindValue(':cNumber', $courseNumber, PDO::PARAM_INT);
                $statement->bindValue(':cSection', $courseSection, PDO::PARAM_INT);
                $statement->bindValue(':cTerm', $courseTerm, PDO::PARAM_STR);
                $statement->bindValue(':aID', $adminID, PDO::PARAM_INT);
                $statement->bindValue(':tID', $teacherID, PDO::PARAM_INT);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $result = $statement->execute();
                $statement->closeCursor();
                if($result)
                    Return "Course updated";
                else
                    Return "Course not updated";
            } catch(PDOException $e){
                $error_message = $e->getMessage();
                error_log($error_message);
            }

        }
        //Retrive instructor user information for display regarding updating
        //courses and instructors in the admin dashboard
        function getInstructors() {
            $instructorRoleNum = 1;
            try{
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select UserID, FirstName, LastName "
                        . "From UserAccount "
                        . "Where UserRole = :role";
                $statement = $db->prepare($query);
                $statement->bindValue(':role', $instructorRoleNum,PDO::PARAM_INT);
                $result = $statement->execute();
                $instructorList = $statement->fetchAll();
                $statement->closeCursor();
                
                if($result)
                    return $instructorList;
                else
                    Return "Could not retrieve list";
            } catch (PDOException $e) {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }
        //Retrieves list of all coursess to be inserted into the list of all classes
        //card on admin dashboard. Instructor displayed based on teacherid
        function getCourses() {
            try{
                $dbObj = new Database(); 
                $db = $dbObj->db;
                $query = "Select CourseID, CourseTitle, CourseNumber, "
                        . "CourseSection, Term, Description, TeacherID "
                        . "FROM Courses";
                $statement = $db->prepare($query);
                $result = $statement->execute();
                $courseList = $statement->fetchAll();
                $statement->closeCursor();

                if($result)
                    return $courseList;
                else
                    Return "Could not retrieve course list";
            } catch (PDOException $e) {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        //Returns list of assignment for singular course based on its id, very limited. 
        //Done in multiple dashboards.
        function getAssignments($courseID){
            try{
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select AssignmentID, AssignmentName "
                        . "From Assignments Where CourseID= :cID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $result = $statement->execute();
                $assignments = $statement->fetchAll();
                $statement->closeCursor();
                if($result)
                    return $assignments;
                else
                    Return "Could not retreive assignment list";
            } catch (PDOException $e) {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }
    }
?>