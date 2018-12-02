<?php

    class SQLInstructor{
        
        function __construct(){
            
        }
        
        //Purpose of this function is to update a course's course key.
        //This covers both adding a course key for the first time and changing a course key
        //Used in instructor-controller file
        function updateCourseKey($courseID, $courseKey)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $result = $this->checkDuplicateCourseKey($courseID, $courseKey);
                if($result == FALSE):
                    $db->beginTransaction();
                    $query = "Update Courses "
                        . "Set CourseKey = :cKey "
                        . "Where CourseID= :cid";
                    $statement = $db->prepare($query);
                    $statement->bindValue(':cKey', $courseKey, PDO::PARAM_STR);
                    $statement->bindValue(':cid', $courseID, PDO::PARAM_INT);
                    $statement->execute();
                    $count = $statement->rowCount();
                    $statement->closeCursor();
                    if ($count == 1):
                        $db->commit();
                        return "Course key changed.";
                    else:
                        $db->rollBack();
                        throw new PDOException;
                    endif;
                endif;
            } catch (PDOException $e)
            {
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not change course key.";
            }
        }
        
        //Purpose of this function is to retrieve all the course ids  for a specific
        //instructor and a specific term. Used to due instructors only being able to
        //view courses of current term.
        //Used in instructor-controller file
        function getCoursesInstructorTerm($teacherID, $term)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select CourseID FROM Courses "
                    . "Where TeacherID = :tID "
                    . "And Term = :term "
                    . "Order By CourseNumber , CourseSection";
                $statement = $db->prepare($query);
                $statement->bindValue(':tID', $teacherID, PDO::PARAM_INT);
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
        
        //Purpose of this function is to retrieve all course ids for a specific instructor
        //Used in instructor-controller file
        function getInstuctorCourses($teacherID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select CourseID FROM Courses "
                    . "Where TeacherID = :tID "
                    . "Order By CourseNumber , CourseSection";
                $statement = $db->prepare($query);
                $statement->bindValue(':tID', $teacherID, PDO::PARAM_INT);
                $statement->execute();
                $courses = $statement->fetchAll();
                $statement->closeCursor();

                return $courses;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retrieve instructor's course list";
            }
        }
        
        //Purpose of this function is to retrieve all of the terms in the database
        //for a specific instructor
        //Used in the instructor-controller file
        function getTermsbyInstructor($teacherID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select Term From Courses "
                    . "Where TeacherID = :tID "
                    . "Group By Term";
                $statement = $db->prepare($query);
                $statement->bindValue(':tID', $teacherID, PDO::PARAM_INT);
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
        
        //Purpose of this function is to add a new assignment for specific course
        //to the database. After this point, the assignment is available for viewing 
        //in the other dashboards. There is currently no way to update an assignment by standard means
        //Used in the instructor-controller file
        function addAssignment($assignmentName, $description, $date, $pdf,
            $courseID, $teacherID, $type)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "INSERT INTO Assignments "
                    . "(AssignmentName, Description, "
                    . "AssignmentDate, PDFLocation, CourseID, TeacherID, Type) "
                    . "VALUES(:aName, :desc, :aDate, :pdf, :cID, :tID, :type);";
                $statement = $db->prepare($query);
                $statement->bindValue(':aName', $assignmentName, PDO::PARAM_STR);
                $statement->bindValue(':desc', $description, PDO::PARAM_STR);
                $statement->bindValue(':aDate', $date);
                $statement->bindValue(':pdf', $pdf, PDO::PARAM_STR);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $statement->bindValue(':tID', $teacherID, PDO::PARAM_INT);
                $statement->bindValue(':type', $type, PDO::PARAM_STR);
                $statement->execute();
                $statement->closeCursor();

                return "Assignment created";
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Assignment not created";
            }
        }
    }
