<?php

    class SQLShared{
        
        function __construct(){
            
        }
        
        //Based on username, returns all student user information in a User object
        //Used in admin, instructor, profile, and student controller files, as well as the header file
        //and the registerCourse function
        function getUser($username)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select * From UserAccount Where Username = :uname;";
                $statement = $db->prepare($query);
                $statement->bindValue(':uname', $username, PDO::PARAM_STR);
                $statement->execute();
                $user = $statement->fetch();
                $statement->closeCursor();

                $return = new User($user[1], $user[2], $user[3], $user[4], 
                    $user[5], $user[6], $user[7], $user[8], $user[9], $user[10],
                    $user[11], $user[12], $user[13], $user[14], $user[15]);
                $return->setID($user[0]);
                return $return;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");

                return "Could not retrieve user data";
            }
        }
        
        //Purpose of this function is to add a student to the list of 
        //students of a given course.
        //Used in the registerCourse function
        function addStudentCourse($studentID, $courseID, $date)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "INSERT INTO Student_Course "
                    . "(StudentID, CourseID, DateAdded) "
                    . "VALUES(:sID, :cID, :date);";
                $statement = $db->prepare($query);
                $statement->bindValue(':sID', $studentID, PDO::PARAM_INT);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $statement->bindValue(':date', $date);
                $statement->execute();
                $statement->closeCursor();

                return "You have been successfully added to the course.";
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Student not added to course";
            }
        }
        
        //Purpose of this function is to add a student to a course, which
        //in turn will allow them to access assignments to make submissions.
        //Used in the student-controller file
        //This function also makes use of the addStudentCourse and getUser functions
        function registerCourse($username, $key, $date)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "SELECT CourseID FROM Courses WHERE CourseKey = :ckey";
                $statement = $db->prepare($query);
                $statement->bindValue('ckey', $key, PDO::PARAM_STR);
                $statement->execute();
                $count = $statement->rowCount();
                $course = $statement->fetch(PDO::FETCH_ASSOC);
                $statement->closeCursor();
                if ($count === 1)
                {
                    try
                    {
                        $user = $this->getUser($username);
                        $userID = $user->id;
                        $userCourses = $user->courses;
                        $courseID = $course['CourseID'];
                    } catch (Exception $e)
                    {
                        echo "Cannot find student account.";
                    }

                    try
                    {
                        $return = $this->addStudentCourse($userID, $courseID, $date);
                        if ($return != "Student not added to course"):
                            echo "You have been successfully added to the course.";
                        else:
                            throw new Exception;
                        endif;

                        try
                        {
                            $return = $this->updateCoursesEnrolled($userID, $userCourses, TRUE);
                            if ($return != "Coures enrolled changed."):
                                throw new Exception;
                            endif;
                        } catch (Exception $e)
                        {
                            echo "Could not update.";
                        }
                    } catch (Exception $e)
                    {
                        echo "You are already registered to this course.";
                    }
                }
                else
                {
                    echo "This is an invalid key.";
                }
            } catch (PDOException $e)
            {
                echo "You have entered an incorrect course key.";
            }
        }
        
        //Created as an alternative way to get user information, this function
        //performs the same as getUser, with the difference being that it restricts based on
        //UserID rather than Username
        //Used in admin-instructor, and project controllers
        function getUserByID($id)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select * From UserAccount Where UserID = :uid;";
                $statement = $db->prepare($query);
                $statement->bindValue(':uid', $id, PDO::PARAM_INT);
                $statement->execute();
                $user = $statement->fetch();
                $statement->closeCursor();

                $return = new User($user[1], $user[2], $user[3], $user[4], 
                    $user[5], $user[6], $user[7], $user[8], $user[9], $user[10],
                    $user[11], $user[12], $user[13], $user[14], $user[15]);
                $return->setID($user[0]);
                return $return;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");

                return "Could not retrieve user data";
            }
        }
        
        //Purpose of this function is to increase and decrease the courses a user is enrolled in
        //At this time, it is called in the registerCourse function, which adds an entry to the 
        //batabase on proper course registration
        //Used in the registerCourse function
        function updateCoursesEnrolled($userID, $userCourses, $incOrDec)
        {
            try
            {
                if ($incOrDec == TRUE):
                    $coursesEnrolled = $userCourses + 1;
                else:
                    $coursesEnrolled = $userCourses - 1;
                endif;

                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $db->beginTransaction();
                $query = "UPDATE UserAccount SET CoursesEnrolled = :cEnrolled WHERE UserID = :uID";
                $statement = $db->prepare($query);
                $statement->bindParam(':uID', $userID, PDO::PARAM_INT);
                $statement->bindParam(':cEnrolled', $coursesEnrolled, PDO::PARAM_INT);
                $statement->execute();
                $count = $statement->rowCount();
                $statement->closeCursor();

                if ($count == 1):
                    $db->commit();
                    return "Coures enrolled changed.";
                else:
                    $db->rollBack();
                    throw new PDOException;
                endif;
            } catch (PDOException $ex)
            {
                return "Could not update courses enrolled.";
            }
        }
        
        //Purpose of this function is to retrieve course information give a courseID
        //Used in getUserCourses function, admin, instructor, profile, project, and 
        //student controller files
        function getCourse($courseID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select * FROM Courses "
                    . "Where CourseID = :cid";
                $statement = $db->prepare($query);
                $statement->bindValue(':cid', $courseID, PDO::PARAM_INT);
                $statement->execute();
                $course = $statement->fetch();
                $statement->closeCursor();

                $return = new Course($course[1], $course[2], $course[3], 
                    $course[4], $course[5], $course[6], $course[7], $course[8],
                    $course[9], $course[11]);
                $return->setID($course[0]);
                $return->setCourseKey($course[10]);

                return $return;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retrieve course";
            }
        }
        
        //Purpose of this function is to get all of the courses that a student belongs to
        //Once that occurs, it gets all the course information tied to the id's returned
        //and puts them in an array
        //Used in student-controller file
        function getUserCourses($userID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "SELECT CourseID FROM Student_Course WHERE StudentID = :uID";
                $statement = $db->prepare($query);
                $statement->bindValue(':uID', $userID, PDO::PARAM_INT);
                $statement->execute();
                $courseIDList = $statement->fetchAll(PDO::FETCH_ASSOC);
                $courses = Array();
                foreach ($courseIDList as $courseID)
                {
                    try
                    {
                        $course = $this->getCourse($courseID['CourseID']);
                        array_push($courses, $course);
                    } catch (Exception $e)
                    {
                        return 'failed to get course';
                    }
                }
                return $courses;
            } catch (Exception $e)
            {
                return $e;
            }
        }
        
        //Purpose of this function is to ensure no duplicate course keys are
        //used in the database.
        //Used in updateCourseKey function
        //NOTE: Can be taken out if unique modifier is added to the CourseKey column
        function checkDuplicateCourseKey($courseID, $courseKey)
        {
            try{
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select CourseID From Courses "
                    . "Where CourseKey = :cKey;";
                $statement = $db->prepare($query);
                $statement->bindValue(':cKey', $courseKey, PDO::PARAM_STR);
                $statement->execute();
                $result = $statement->fetchAll();
                $statement->closeCursor();

                if(sizeof($result) != 0):
                    if(sizeof($result) == 1):
                        $tempID = $result[0]['CourseID'];
                        if($tempID == $courseID):
                            return FALSE; //Specifically for when pressing update without changing key;
                        else:
                            return TRUE; //Occurs when updating to different course's key
                        endif;
                    else:
                        return TRUE; //multiple duplicates found, shouldn't be reachable.
                    endif;
                else:
                    return FALSE; //duplicate course key not found;
                endif;
            } catch (PDOException $ex) {

            }
        }
        
        //Purpose of this function is to retrieve all assignment data for a specific
        //assignment based on its ID. It is then put in an Assignment object
        //Used in the profile, project, and student controller
        function getAssignment($assignmentID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select * From Assignments "
                    . "Where AssignmentID = :aID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':aID', $assignmentID, PDO::PARAM_INT);
                $statement->execute();
                $assignment = $statement->fetch();
                $statement->closeCursor();

                $output = new Assignment($assignment[0], $assignment[1], 
                    $assignment[2], $assignment[3], $assignment[4], $assignment[5],
                    $assignment[6], $assignment[7]);

                return $output;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retrieve assignment";
            }
        }
        
        //Purpose of this function is to get the assignment IDs and names of 
        //all assignments for a specific course based on the course id.
        //Used in getUserAssignments function, admin, instructor, and student controllers
        function getAssignments($courseID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select AssignmentID, AssignmentName "
                    . "From Assignments Where CourseID= :cID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $statement->execute();
                $assignments = $statement->fetchAll();
                $statement->closeCursor();

                return $assignments;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retreive assignment list";
            }
        }
        
        //Purpose of this function is to pre-place results from getAssignments into an array
        //Has no other function
        //Used in student-controller file
        function getUserAssignments($courseID)
        {
            $assignments = $this->getAssignments($courseID);
            $assignmentsList = array();
            foreach ($assignments as $assignment)
            {
                array_push($assignmentsList, $assignment);
            }

            return $assignmentsList;
        }
        
        //Purpose of this function is to return all student assignment submission
        //information that is stored in the database based on the assignment's id
        //and the student who the assignment belongs to.
        //Used in the profile, project, and student controller files
        function getStudentAssignment($studentID, $assignmentID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select * From Student_Assignment "
                    . "Where StudentID = :sID AND AssignmentID = :aID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':sID', $studentID, PDO::PARAM_INT);
                $statement->bindValue(':aID', $assignmentID, PDO::PARAM_INT);
                $statement->execute();
                $assignment = $statement->fetch();
                $statement->closeCursor();

                return $assignment;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retrieve student assignment";
            }
        }
        
        //Purpose of this function is to retrieve all the assignment ids related
        //to the assignments submitted by a specific student.
        //Used by the profile-controller file
        function getStudentAssignments($studentID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select AssignmentID From Student_Assignment "
                    . "Where StudentID = :sID";
                $statement = $db->prepare($query);
                $statement->bindValue(':sID', $studentID, PDO::PARAM_INT);
                $statement->execute();
                $assignments = $statement->fetchAll();
                $statement->closeCursor();

                return $assignments;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retrieve student assignments";
            }
        }
        
        //Purpose of this function is to retrieve all the student ids of specific assignment
        //submissions to be used elsewhere.
        //Used in the getSubmissionsOfCourse function and project-controller file
        function getStudentsOfAssignment($assignmentID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select StudentID From Student_Assignment "
                    . "Where AssignmentID = :aID";
                $statement = $db->prepare($query);
                $statement->bindValue(':aID', $assignmentID, PDO::PARAM_INT);
                $statement->execute();
                $students = $statement->fetchAll();
                $statement->closeCursor();

                return $students;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retrieve student assignment submissions";
            }
        }
    }