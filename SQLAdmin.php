<?php
    /*
     * Created By: Justin Crest
     * Description: This file is holds all queries pertaining to the admin-dashboard and
     * admin-controller files. 
     */
    class SQLAdmin{
        
        function __construt(){
            
        }
        
        //Purpose of this function is to add instructor users to the database, as well as
        //Generate differing usernames if the one given is already in use. 
        //Used in the admin-controller file
        function addInstructor(User $user)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select Username From UserAccount "
                    . "Where Username Like :username";
                $statement = $db->prepare($query);
                $statement->bindValue(':username', $user->username . "%", PDO::PARAM_STR);
                $statement->execute();
                $count = sizeof($statement->fetchAll());
                $statement->closeCursor();

                $username = "";
                if ($count > 0):
                    $username = "$user->username$count";
                else:
                    $username = $user->username;
                endif;

                $query = "Insert into UserAccount "
                    . "(Username, Password, FirstName, LastName, Title, "
                    . "Email, UserRole, Suspended, DateCreated, LastLoggedIn) "
                    . "VALUES (:username, :password, :fName, :lName, :title,"
                    . " :email, :role, :suspend, :creation, :lastLogin)";
                $statement = $db->prepare($query);
                $statement->bindValue(':username', $username, PDO::PARAM_STR);
                $statement->bindValue(':password', $user->password, PDO::PARAM_STR);
                $statement->bindValue(':fName', $user->firstName, PDO::PARAM_STR);
                $statement->bindValue(':lName', $user->lastName, PDO::PARAM_STR);
                $statement->bindValue(':title', $user->title, PDO::PARAM_STR);
                $statement->bindValue(':email', $user->email, PDO::PARAM_STR);
                $statement->bindValue(':role', $user->role, PDO::PARAM_INT);
                $statement->bindValue(':suspend', $user->suspended, PDO::PARAM_BOOL);
                $statement->bindValue(':creation', $user->dateCreated);
                $statement->bindValue(':lastLogin', $user->lastLoginDate);
                $statement->execute();
                $statement->closeCursor();

                return ["Instructor created", $username];
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Instructor not created";
            }
        }
        
        //Purpose of this function is to update the limited information pertaining to instructors
        //At this time, it only updates the first name, last name, and email columns
        //Username, which is originally based on the first and last name, does not change.
        //Used in the admin-controller file
        function updateInstructor($userID, $firstName, $lastName, $email)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $db->beginTransaction();
                $query = "Update UserAccount "
                    . "SET FirstName=:fName, LastName=:lName , Email=:email "
                    . "WHERE UserID=:uid;";
                $statement = $db->prepare($query);
                $statement->bindValue(':fName', $firstName, PDO::PARAM_STR);
                $statement->bindValue(':lName', $lastName, PDO::PARAM_STR);
                $statement->bindValue(':email', $email, PDO::PARAM_STR);
                $statement->bindValue(':uid', $userID, PDO::PARAM_INT);
                $statement->execute();
                $count = $statement->rowCount();
                $statement->closeCursor();

                if ($count == 1):
                    $db->commit();
                    return "Instructor updated";
                else:
                    $db->rollBack();
                    throw new PDOException;
                endif;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Instructor not updated";
            }
        }
        
        //Purpose is this function is retrieve user data for only instructors, which
        //is restricted to only roles 2 and 4 currently.
        //Used in the admin-controller file
        function getInstructors()
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select UserID, FirstName, LastName , Email "
                    . "From UserAccount "
                    . "Where UserRole = 2 OR UserRole = 4";
                $statement = $db->prepare($query);
                $statement->execute();
                $instructors = $statement->fetchAll();
                $statement->closeCursor();

                return $instructors;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retrieve instructor list";
            }
        }
        
        //Purpose of this function is to add a new course based on information in 
        //the Course object, as well as assign a specific instructor to the course
        //Used in the admin-controller file
        function addCourse(Course $course)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $result = $this->checkDuplicateCourseForTerm($course);
                if($result == FALSE):
                    $query = "INSERT INTO Courses"
                        . "(CourseTitle, CourseNumber, CourseSection, "
                        . "Term, Description, Closed, EnrollmentTotal, AdminID, "
                        . "TeacherID , CloseDate) "
                        . "VALUES(:cTitle, :cNumber, :cSection, :term, "
                        . ":desc, :closed, :enrolled, :adminID, :teacherID , :close);";
                    $statement = $db->prepare($query);
                    $statement->bindValue(':cTitle', $course->courseTitle, PDO::PARAM_STR);
                    $statement->bindValue(':cNumber', $course->courseNumber, PDO::PARAM_INT);
                    $statement->bindValue(':cSection', $course->courseSection, PDO::PARAM_INT);
                    $statement->bindValue(':term', $course->term, PDO::PARAM_STR);
                    $statement->bindValue(':desc', $course->description, PDO::PARAM_STR);
                    $statement->bindValue(':closed', $course->closed, PDO::PARAM_BOOL);
                    $statement->bindValue(':enrolled', $course->enrollment, PDO::PARAM_INT);
                    $statement->bindValue(':adminID', $course->adminID, PDO::PARAM_INT);
                    $statement->bindValue(':teacherID', $course->teacherID, PDO::PARAM_INT);
                    $statement->bindValue(':close', $course->closeDate, PDO::PARAM_STR);
                    $output = $statement->execute();
                    $statement->closeCursor();

                    if($output == TRUE){
                        return $output;
                    }
                    else{
                        throw new PDOException;
                    }
                else:
                    return "Course already exists.";
                endif;
            } catch (PDOException $e)
            {
                //error_log($error_message, (int)0,"./error.txt");
                return "Course not created";
            }
        }
        
        //Purpose of this course is to work in tandem with the addCourse function
        //Its  use is to ensure that not duplicate courses for the term chosen 
        //are added to the database, as courses with the same number and section should
        //not exist.
        //Used in the addCourse and updateCourse function
        function checkDuplicateCourseForTerm(Course $course)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select CourseID From Courses "
                    . "Where CourseNumber = :cNumber And CourseSection = :cSection "
                    . "And Term = :term;";
                $statement = $db->prepare($query);
                $statement->bindValue(':cNumber', $course->courseNumber, PDO::PARAM_INT);
                $statement->bindValue(':cSection', $course->courseSection, PDO::PARAM_INT);
                $statement->bindValue(':term', $course->term, PDO::PARAM_STR);
                $statement->execute();
                $result = $statement->fetchAll();
                $statement->closeCursor();

                if(sizeof($result) != 0):
                    if(sizeof($result) == 1):
                        $tempID = $result[0]['CourseID'];
                        if(isset($course->courseID)):
                            if($tempID == $course->courseID):
                                return FALSE; //Specifically for when updating course with same number&section;
                            else:
                                return TRUE; //Occurs when updating to different course number&section
                            endif;
                        else:
                            return TRUE; //Only occurs when adding a course
                        endif;
                    else:
                        return TRUE; //multiple duplicates found, shouldn't be reachable.
                    endif;
                else:
                    return FALSE; //duplicate course not found;
                endif;
            } catch (PDOException $e)
            {
                //error_log($error_message, (int)0,"./error.txt");
                return "Duplicate check could not completed";
            }
        }
        
        //Purpose of this function is to update course information to whatever is
        //entered. All validation is done prior to function call.
        //It also calls the checkDuplicateCourseForTerm function in order to ensure
        //that it does not update to a course that already exists. for the term.
        function updateCourse(Course $course)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $result = $this->checkDuplicateCourseForTerm($course);
                if($result == FALSE):
                    $db->beginTransaction();
                    $query = "Update Courses "
                        . "SET CourseTitle=:cTitle, CourseNumber=:cNumber, "
                        . "CourseSection=:cSection, Term=:term, "
                        . "Description =:desc, Closed=:closed, "
                        . "EnrollmentTotal=:enrollment, "
                        . "AdminID= :aID, TeacherID=:tID , "
                        . "CloseDate= :close "
                        . "WHERE CourseID=:cID;";
                    $statement = $db->prepare($query);

                    $statement->bindValue(':cTitle', $course->courseTitle, PDO::PARAM_STR);
                    $statement->bindValue(':cNumber', $course->courseNumber, PDO::PARAM_INT);
                    $statement->bindValue(':cSection', $course->courseSection, PDO::PARAM_INT);
                    $statement->bindValue(':term', $course->term, PDO::PARAM_STR);
                    $statement->bindValue(':desc', $course->description, PDO::PARAM_STR);
                    $statement->bindValue(':closed', $course->closed, PDO::PARAM_BOOL);
                    $statement->bindValue(':enrollment', $course->enrollment, PDO::PARAM_INT);
                    $statement->bindValue(':aID', $course->adminID, PDO::PARAM_INT);
                    $statement->bindValue(':tID', $course->teacherID, PDO::PARAM_INT);
                    $statement->bindValue(':close', $course->closeDate, PDO::PARAM_STR);
                    $statement->bindValue(':cID', $course->courseID, PDO::PARAM_INT);
                    $statement->execute();
                    $count = $statement->rowCount();
                    $statement->closeCursor();

                    if ($count == 1):
                        $db->commit();
                        return "Course updated";
                    elseif($count != 0):
                        $db->rollBack();
                        throw new PDOException;
                    endif;
                else:
                    return "Course already exists";
                endif;
            } catch (PDOException $e)
            {                
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");

                return "Course not updated";
            }
        }
        
        //Purpose of this function is to retrieve all courseIDs without restriction
        //Used in admin-controller file
        function getAllCourses()
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select CourseID FROM Courses Order By CourseNumber , CourseSection";
                $statement = $db->prepare($query);
                $statement->execute();
                $courses = $statement->fetchAll();
                $statement->closeCursor();

                return $courses;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retrieve complete course list";
            }
        }
        
        //Purpose of this function is to retrieve all course ids for a specific term
        //Used in admin-controller file
        function getCoursesByTerm($term)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select CourseID FROM Courses "
                    . "Where Term = :term "
                    . "Order BY CourseNumber , CourseSection ";
                $statement = $db->prepare($query);
                $statement->bindValue(':term', $term, PDO::PARAM_STR);
                $statement->execute();
                $courses = $statement->fetchAll();
                $statement->closeCursor();

                return $courses;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retrieve complete course list";
            }
        }
        
        //Purpose of this function is to retrieve all terms that have been used in the database
        //Used in the admin-controller file
        function getTerms()
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select Term From Courses "
                    . "Group By Term";
                $statement = $db->prepare($query);
                $statement->execute();
                $terms = $statement->fetchAll();
                $statement->closeCursor();

                return $terms;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retrieve list of terms";
            }
        }
    }
