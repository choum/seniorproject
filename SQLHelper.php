<?php

        //Insertion of new student user to be added to the UserAccount Table
        //Done at first login.
        function addUser($userid, $username, $firstName, $lastName, $title,
                $userRole, $suspended, $dateCreated,
                $bio = null, $imageLink = null, $linkedin = null, $website = null,
                $lastLoginDate = null) {
            try{
                $db = new Database();
                $query = "Insert into UserAccount "
                        . "VALUES(:uid, :username, :fName, :lNAme, :title, :bio,"
                        . " :image, :linkedin, :website, :role, :suspend, :creation"
                        . ":lastLogin);";
                $statement = $db->prepare($query);
                $statement->bindValue(':uid', $userid, PDO::PARAM_INT);
                $statement->bindValue(':username', $username, PDO::PARAM_STR);
                $statement->bindValue(':fName', $firstName, PDO::PARAM_STR);
                $statement->bindValue(':lName', $lastName, PDO::PARAM_STR);
                $statement->bindValue(':title', $title, PDO::PARAM_STR);
                $statement->bindValue(':bio', $bio, PDO::PARAM_STR);
                $statement->bindValue(':image', $imageLink, PDO::PARAM_STR);
                $statement->bindValue(':linkedin', $linkedin, PDO::PARAM_STR);
                $statement->bindValue('website', $website, PDO::PARAM_STR);
                $statement->bindValue(':role', $userRole, PDO::PARAM_INT);
                $statement->bindValue(':suspend', $suspended, PDO::PARAM_BOOL);
                $statement->bindValue(':creation', $dateCreated);
                $statement->bindValue(':lastLogin', $lastLoginDate);
                $statement->execute();
                $statement->closeCursor();
            } catch(PDOException $e){
                $error_message = $e->getMessage();
                include("../Error/DBError.php");
            }
            
        }
        //Updating student user info based on update profile card, 
        //overwriting columns where new info was entered, 
        //or where info was not given, in the UserAccount table.
        //Done on student user side.
        function updateUser($userID, $bio = null, $imageLink = null, 
                $linkedin = null, $website = null) {
            try{
                $db = new Database();
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
                $statement->closeCursor();
            } catch (PDOException $e) {
                $error_message = $e->getMessage();
                include("../Error/DBError.php");
            }
        }
        //Insertion of new instructor user to be added to the UserAccount Table
        //Done at first login.
        function addInstructor($userid, $username, $firstName, $lastName, $title,
                $userRole, $suspended, $dateCreated,
                $bio = null, $imageLink = null, $linkedin = null, $website = null,
                $lastLoginDate = null) {
            try{
                $db = new Database();
                $query = "Insert into UserAccount "
                        . "VALUES(:uid, :username, :fName, :lNAme, :title, :bio,"
                        . " :image, :linkedin, :website, :role, :suspend, :creation"
                        . ":lastLogin);";
                $statement = $db->prepare($query);
                $statement->bindValue(':uid', $userid, PDO::PARAM_INT);
                $statement->bindValue(':username', $username, PDO::PARAM_STR);
                $statement->bindValue(':fName', $firstName, PDO::PARAM_STR);
                $statement->bindValue(':lName', $lastName, PDO::PARAM_STR);
                $statement->bindValue(':title', $title, PDO::PARAM_STR);
                $statement->bindValue(':bio', $bio, PDO::PARAM_STR);
                $statement->bindValue(':image', $imageLink, PDO::PARAM_STR);
                $statement->bindValue(':linkedin', $linkedin, PDO::PARAM_STR);
                $statement->bindValue('website', $website, PDO::PARAM_STR);
                $statement->bindValue(':role', $userRole, PDO::PARAM_INT);
                $statement->bindValue(':suspend', $suspended, PDO::PARAM_BOOL);
                $statement->bindValue(':creation', $dateCreated);
                $statement->bindValue(':lastLogin', $lastLoginDate);
                $statement->execute();
                $statement->closeCursor();
            } catch(PDOException $e){
                $error_message = $e->getMessage();
                include("../Error/DBError.php");
            }
        }
        //Updating instructor user info based on update instructor card to the
        //UserAccount table.
        //Done on admin side.
        function updateInstructor($userID, $firstName, $lastName) {
            try{
                $db = new Database();
                $query = "Update UserAccount "
                        . "SET First Name=:fName, Last Name=:lName "
                        . "WHERE UserID=:uid;";
                $statement = $db->prepare($query);
                $statement->bindValue(':fName', $firstName, PDO::PARAM_STR);
                $statement->bindValue(':lName', $lastName, PDO::PARAM_STR);
                $statement->bindValue(':uid', $userID, PDO::PARAM_INT);
                $statement->execute();
                $statement->closeCursor();
            } catch(PDOException $e){
                $error_message = $e->getMessage();
                include("../Error/DBError.php");
            }
        }
        //Insertion of new course info to the Courses table
        //Done on admin side.
        function addCourse($courseID, $courseName, $courseNumber, $courseSection,
                $createdDate, $description, $closed, $enrollment, $adminID, $teacherID) {
            try{
                $db = new Database();
                $query = "INSERT INTO Courses"
                        . "VALUES(:cID, :cName, :cNumber, :cSection, :creation, "
                        . ":desc, :closed, :enrolled, :adminID, :teacherID);";
                $statement = $db->prepare($query);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $statement->bindValue(':cName', $courseName, PDO::PARAM_STR);
                $statement->bindValue(':cNumber', $courseNumber, PDO::PARAM_INT);
                $statement->bindValue(':cSection', $courseSection, PDO::PARAM_INT);
                $statement->bindValue(':creation', $createdDate);
                $statement->bindValue(':desc', $description, PDO::PARAM_STR);
                $statement->bindValue(':closed', $closed, PDO::PARAM_BOOL);
                $statement->bindValue(':enrolled', $enrollment, PDO::PARAM_INT);
                $statement->bindValue(':adminID', $adminID, PDO::PARAM_INT);
                $statement->bindValue(':teacherID', $teacherID, PDO::PARAM_INT);
                $statement->execute();
                $statement->closeCursor();
            } catch(PDOException $e){
                $error_message = $e->getMessage();
                include("../Error/DBError.php");
            }
        }
        //Update of specific columns in the Courses Table
        //Done on admin side.
        function updateCourse($courseID, $courseNumber, $courseSection, 
                $courseTerm, $courseTitle, $teacherID) {
            try{
                $db = new Database();
                $query = "Update Courses "
                        . "SET CourseNumber=:cNumber, CourseSection=:cSection, "
                        . "CourseTerm=:cTerm, Description=:cTitle, TeacherID=:tID "
                        . "WHERE CourseID=:cID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':cNumber', $courseNumber, PDO::PARAM_INT);
                $statement->bindValue(':cSection', $courseSection, PDO::PARAM_INT);
                $statement->bindValue(':cTerm', $courseTerm, PDO::PARAM_STR);
                $statement->bindValue(':cTitle', $courseTitle, PDO::PARAM_STR);
                $statement->bindValue(':tID', $teacherID, PDO::PARAM_INT);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $statement->execute();
                $statement->closeCursor();
            } catch(PDOException $e){
                $error_message = $e->getMessage();
                include("../Error/DBError.php");
            }
            
        }
        //Retrive instructor user information for display regarding updating
        //courses and instructors in the admin dashboard
        function getInstructors() {
            $instructorRoleNum = (int) 2;
            try{
                $db = new Database();
                $query = "Select UserID, First Name, Last Name"
                        . "From UserAccount"
                        . "Where UserRole = :role";
                $statement = $db->prepare($query);
                $statement->bindValue(':role', $instructorRoleNum,PDO::PARAM_INT);
                $statement->execute();
                $results = $statement->fetchAll();
                $statement->closeCursor();
                
                return $results;
            } catch (PDOException $e) {
                $error_message = $e->getMessage();
                include("../Error/DBError.php");
            }
        }
        //Retrieves list of all coursess to be inserted into the list of all classes
        //card on admin dashboard. Instructor displayed based on teacherid
        function getCourses() {
            try{
                $db = new Database();
                $query = "Select CourseID, CourseName, CourseNumber, CourseSection, "
                        . "TermID, Description, TeacherID"
                        . "From Courses";
                $statement = $db->prepare($query);
                $statement->execute();
                $results = $statement->fetchAll();
                $statement->closeCursor();
                
                return $results;
            } catch (PDOException $e) {
                $error_message = $e->getMessage();
                include("../Error/DBError.php");
            }
        }
        //For use in comboboxes for adding and updating courses, as well as
        //Displaying term for list of all classes.
        //Done in multiple dashboards
        function getTerms() {
            try{
                $db = new Database();
                $query = "Select termID, termSeason, termYear, visibility"
                        . "From Terms";
                $statement = $db->prepare($query);
                $statement->execute();
                $results = $statement->fetchAll();
                $statement->closeCursor();
                
                return $results;
            } catch (Exception $e) {
                $error_message = $e->getMessage();
                include("../Error/DBError.php");
            }
        }
        //Returns list of assignment for singular course based on its id, very limited. 
        //Done in multiple dashboards.
        function getAssignmentList($courseID){
            try{
                $db = new Database();
                $query = "Select AssignmentID, AssignmentName"
                        . "From Assignments"
                        . "Where CourseID=:cID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $statement->execute();
                $results = $statement->fetchAll();
                $statement->closeCursor();
                
                return $results;
            } catch (PDOException $e) {
                $error_message = $e->getMessage();
                include("../Error/DBError.php");
            }
        }

?>