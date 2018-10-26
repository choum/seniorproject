<?php

    require_once("./private/Database.php");

    Class SQLHelper
    {
        /* Insertion of new student user to be added to the UserAccount Table
         * Done at account registration.
         */

        function addUser($username, $password, $firstName, $lastName, $title,
                $userRole, $suspended, $dateCreated, $bio = NULL,
                $imageLink = NULL, $linkedin = NULL, $website = NULL,
                $lastLoginDate = NULL)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Insert into UserAccount "
                        . "(Username, Password, FirstName, LastName, Title,"
                        . " Bio, ImageLink, Linkedin, Website, UserRole, "
                        . "Suspended, DateCreated, LastLoggedIn) "
                        . "VALUES (:username, :password, :fName, :lName, :title,"
                        . " :bio, :image, :linkedin, :website, :role, "
                        . ":suspend, :creation, :lastLogin);";
                $statement = $db->prepare($query);
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

                if ($result)
                    Return "User created";
                else
                    Return "User not created";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        /* Update of student user info in UserAccount table
         * Done via student dashboard
         */

        function updateUser($userID, $bio = null, $imageLink = null,
                $linkedin = null, $website = null)
        {
            try
            {
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

                if ($result)
                    Return "User updated";
                else
                    Return "User not updated";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function getUser($username)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select * From UserAccounts Where Username = :uname;";
                $statement = $db->prepare($query);
                $statement->bindValue(':uname', $username, PDO::PARAM_STR);
                $result = $statement->execute();
                $user = $statement->fetch();
                $statement->closeCursor();

                if ($result)
                    return $user;
                else
                    return "Could not retrieve user data";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        /* Insertion of new instructor user to the UserAccount table
         * Done via admin dashboard
         */

        function addInstructor($username, $password, $firstName, $lastName,
                $title, $userRole, $suspended, $dateCreated,
                $lastLoginDate = null)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Insert into UserAccount "
                        . "(Username, Password, FirstName, LastName, Title, "
                        . "UserRole, Suspended, DateCreated, LastLoggedIn) "
                        . "VALUES (:username, :password, :fName, :lName, :title,"
                        . " :role, :suspend, :creation, :lastLogin)";
                $statement = $db->prepare($query);
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
                if ($result)
                    Return "Instructor created";
                else
                    Return "Instructor not created";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        /* Updating instructor user info based on update instructor card to the
         * UserAccount table.
         * Done via admin dashboard
         */

        function updateInstructor($userID, $firstName, $lastName)
        {
            try
            {
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

                if ($results)
                    Return "Instructor updated";
                else
                    Return "Instructor not updated";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function getInstructor($userID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select * From UserAccount "
                        . "Where UserID = :uid";
                $statement = $db->prepare($query);
                $statement->bindValue(':uid', $userID, PDO::PARAM_INT);
                $result = $statement->execute();
                $instructor = $statement->fetch();
                $statement->closeCursor();

                if ($result)
                    return $instructor;
                else
                    Return "Could not retrieve instructor";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        /*
         * Retrive all user information based on instructor user role
         */

        function getInstructors()
        {
            $instructorRoleNum = (int) 2; //Assumed role # for instructor users, to be changed.
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select UserID, FirstName, LastName "
                        . "From UserAccount "
                        . "Where UserRole = :role";
                $statement = $db->prepare($query);
                $statement->bindValue(':role', $instructorRoleNum,
                        PDO::PARAM_INT);
                $result = $statement->execute();
                $instructors = $statement->fetchAll();
                $statement->closeCursor();

                if ($result)
                    return $instructors;
                else
                    Return "Could not retrieve instructor list";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        /*
         * Insertion of new course into Courses table
         * Done via admin dashboard
         */

        function addCourse($courseTitle, $courseNumber, $courseSection, $term,
                $description, $closed, $enrollment, $adminID, $teacherID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "INSERT INTO Courses"
                        . "(CourseTitle, CourseNumber, CourseSection, "
                        . "Term, Description, Closed, EnrollmentTotal, AdminID, "
                        . "TeacherID)"
                        . "VALUES(:cTitle, :cNumber, :cSection, :term, "
                        . ":desc, :closed, :enrolled, :adminID, :teacherID);";
                $statement = $db->prepare($query);
                $statement->bindValue(':cTitle', $courseTitle, PDO::PARAM_STR);
                $statement->bindValue(':cNumber', $courseNumber, PDO::PARAM_INT);
                $statement->bindValue(':cSection', $courseSection,
                        PDO::PARAM_INT);
                $statement->bindValue(':term', $term, PDO::PARAM_STR);
                $statement->bindValue(':desc', $description, PDO::PARAM_STR);
                $statement->bindValue(':closed', $closed, PDO::PARAM_BOOL);
                $statement->bindValue(':enrolled', $enrollment, PDO::PARAM_INT);
                $statement->bindValue(':adminID', $adminID, PDO::PARAM_INT);
                $statement->bindValue(':teacherID', $teacherID, PDO::PARAM_INT);
                $result = $statement->execute();
                $statement->closeCursor();

                if ($result)
                    Return "Course created";
                else
                    Return "Course not created";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function updateCourse($courseID, $courseTitle, $courseNumber,
                $courseSection, $term, $description, $closed, $enrollment,
                $adminID, $teacherID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Update Courses "
                        . "SET CourseTitle=:cTitle, CourseNumber=:cNumber, "
                        . "CourseSection=:cSection, Term=:term, "
                        . "Description =:desc, Closed=:closed, "
                        . "Enrollment=:enrollment "
                        . "AdminID= :aID, TeacherID=:tID "
                        . "WHERE CourseID=:cID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':cTitle', $courseTitle, PDO::PARAM_STR);
                $statement->bindValue(':cNumber', $courseNumber, PDO::PARAM_INT);
                $statement->bindValue(':cSection', $courseSection,
                        PDO::PARAM_INT);
                $statement->bindValue(':term', $term, PDO::PARAM_STR);
                $statement->bindValue(':desc', $description, PDO::PARAM_STR);
                $statement->bindValue(':closed', $closed, PDO::PARAM_BOOL);
                $statement->bindValue(':enrollment', $enrollment, PDO::PARAM_INT);
                $statement->bindValue(':aID', $adminID, PDO::PARAM_INT);
                $statement->bindValue(':tID', $teacherID, PDO::PARAM_INT);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $result = $statement->execute();
                $statement->closeCursor();
                if ($result)
                    Return "Course updated";
                else
                    Return "Course not updated";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function getCourse($courseID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select * FROM Courses "
                        . "Where CourseID = :cid";
                $statement = $db->prepare($query);
                $statement->bindValue(':cid', $courseID, PDO::PARAM_INT);
                $result = $statement->execute();
                $course = $statement->fetch();
                $statement->closeCursor();

                if ($result)
                    return $course;
                else
                    Return "Could not retrieve course";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        /* Retrieves entire list of course with all course information
         * Done via admin dashboard TO ONLY BE USED THERE
         */

        function getAllCourses()
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select CourseID FROM Courses";
                $statement = $db->prepare($query);
                $result = $statement->execute();
                $courses = $statement->fetchAll();
                $statement->closeCursor();

                if ($result)
                    return $courses;
                else
                    Return "Could not retrieve complete course list";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function getInstuctorCourses($teacherID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select CourseID FROM Courses "
                        . "Where TeacherID = :tID";
                $statement = $db->prepare($query);
                $statement->bindValue(':tID', $teacherID, PDO::PARAM_INT);
                $result = $statement->execute();
                $courses = $statement->fetchAll();
                $statement->closeCursor();

                if ($result)
                    return $courses;
                else
                    Return "Could not retrieve instructor's course list";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function getTerms()
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select Term From Courses "
                        . "Group By Term";
                $statement = $db->prepare($query);
                $result = $statement->execute();
                $terms = $statement->fetchAll();
                $statement->closeCursor();

                if ($result)
                    Return $terms;
                else
                    Return "Could not retrieve list of terms";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function getCoursesOfTerm($term)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select CourseID From Courses "
                        . "Where Term = :term";
                $statement = $db->prepare($query);
                $statement->bindValue(':term', $tterm, PDO::PARAM_STR);
                $result = $statement->execute();
                $terms = $statement->fetchAll();
                $statement->closeCursor();

                if ($result)
                    Return $courses;
                else
                    Return "Could not retrieve courses of $term term";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function addAssignment($assignmentName, $description, $date, $courseID,
                $teacherID, $pdf = NULL)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "INSERT INTO Assignments "
                        . "(AssignmentName, Description, "
                        . "AssignmentDate, PDFLocation, CourseID, TeacherID) "
                        . "VALUES(:aID, :aName, :desc, :aDate, :pdf, :cID, :tID);";
                $statement = $db->prepare($query);
                $statement->bindValue(':aName', $assignmentName, PDO::PARAM_STR);
                $statement->bindValue(':desc', $description, PDO::PARAM_STR);
                $statement->bindValue(':aDate', $date);
                $statement->bindValue(':pdf', $pdf, PDO::PARAM_STR);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $statement->bindValue(':tID', $teacherID, PDO::PARAM_INT);
                $result = $statement->execute();
                $statement->closeCursor();

                if ($result)
                    Return "Assignment created";
                else
                    Return "Assignment not created";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function updateAssignment($assignmentID, $assignmentName, $description,
                $date, $courseID, $teacherID, $pdf = NULL)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Update Assignments "
                        . "Set AssignmentName=:aName, Description=:desc, "
                        . "AssignmentDate=:date, PDFLocation=:pdf, CourseID=:cID, "
                        . "TeacherID=:tID "
                        . "Where AssignmentID = :aID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':aID', $assignmentID, PDO::PARAM_INT);
                $statement->bindValue(':aName', $assignmentName, PDO::PARAM_STR);
                $statement->bindValue(':desc', $description, PDO::PARAM_STR);
                $statement->bindValue(':aDate', $date);
                $statement->bindValue(':pdf', $pdf, PDO::PARAM_STR);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $statement->bindValue(':tID', $teacherID, PDO::PARAM_INT);
                $result = $statement->execute();
                $statement->closeCursor();

                if ($result)
                    Return "Assignment updated";
                else
                    Return "Assignment not updated";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function getAssignment($assignmentID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select * From Assignments "
                        . "Where AssignmentID = :aID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':aID', $assignmentID, PDO::PARAM_INT);
                $result = $statement->execute();
                $assignment = $statement->fetch();
                $statement->closeCursor();

                if ($result)
                    Return $assignment;
                else
                    Return "Could not retrieve assignment";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        //Returns list of assignment for singular course based on its id, very limited.
        //Done in multiple dashboards.
        function getAssignments($courseID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select AssignmentID, AssignmentName "
                        . "From Assignments Where CourseID= :cID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $result = $statement->execute();
                $assignments = $statement->fetchAll();
                $statement->closeCursor();
                if ($result)
                    return $assignments;
                else
                    Return "Could not retreive assignment list";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function addStudentAssignment($studentID, $assignmentID, $desc, $dir,
                $dateCreated, $screenshot = NULL, $featured = NULL,
                $group = NULL)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "INSERT INTO Student_Assignment "
                        . "(StudentID, AssignmentID, Description, Directory, "
                        . "DateCreated, Screenshot, Featured, Group) "
                        . "VALUES(:sID, :aID, :desc, :dir, :date, :screen, "
                        . ":featured, :group);";
                $statement = $db->prepare($query);
                $statement->bindValue(':sID', $studentID, PDO::PARAM_INT);
                $statement->bindValue(':aID', $assignmentID, PDO::PARAM_INT);
                $statement->bindValue(':desc', $desc, PDO::PARAM_STR);
                $statement->bindValue(':dir', $dir, PDO::PARAM_STR);
                $statement->bindValue(':date', $dateCreated);
                $statement->bindValue(':screen', $screenshot, PDO::PARAM_STR);
                $statement->bindValue(':featured', $featured, PDO::PARAM_BOOL);
                $statement->bindValue(':group', $group, PDO::PARAM_BOOL);
                $result = $statement->execute();
                $statement->closeCursor();

                if ($result)
                    Return "Studnet ssignment created";
                else
                    Return "Student assignment not created";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function updateStudentAssignment($studentID, $assignmentID, $desc, $dir,
                $dateCreated, $screenshot = NULL, $featured = NULL,
                $group = NULL)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Update Student_Assignment "
                        . "Set Description=:desc, Directory=:dir, "
                        . "DateCreated=:date, Screenshot=:screen, "
                        . "Featured=:featured, Group=:group "
                        . "Where StudentI=:sID And AssignmentID = :aID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':desc', $desc, PDO::PARAM_STR);
                $statement->bindValue(':dir', $dir, PDO::PARAM_STR);
                $statement->bindValue(':date', $dateCreated);
                $statement->bindValue(':screen', $screenshot, PDO::PARAM_STR);
                $statement->bindValue(':featured', $featured, PDO::PARAM_BOOL);
                $statement->bindValue(':group', $group, PDO::PARAM_BOOL);
                $statement->bindValue(':sID', $studentID, PDO::PARAM_INT);
                $statement->bindValue(':aID', $assignmentID, PDO::PARAM_INT);
                $result = $statement->execute();
                $statement->closeCursor();

                if ($result)
                    Return "Studnet ssignment updated";
                else
                    Return "Student assignment not updated";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function getStudentAssignemnt($studentID, $assignmentID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select * From Student_Assignment "
                        . "Where StudentID = :sID, AssignmentID = :aID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':sID', $studentID, PDO::PARAM_INT);
                $statement->bindValue(':aID', $assignmentID, PDO::PARAM_INT);
                $result = $statement->execute();
                $assignment = $statement->fetch();
                $statement->closeCursor();

                if ($result)
                    Return $assignment;
                else
                    Return "Could not retrieve student assignment";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function getStudentAssignments($studentID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select AssignmentID From Student_Assignment "
                        . "Where StudentID = :sID";
                $statement = $db->prepare($query);
                $statement->bindValue(':sID', $studentID, PDO::PARAM_INT);
                $result = $statement->execute();
                $assignments = $statement->fetchAll();
                $statement->closeCursor();

                if ($result)
                    Return $assignments;
                else
                    Return "Could not retrieve student assignments";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function getStudentsOfAssignment($assignmentID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select StudentID From Student_Assignment "
                        . "Where AssignmentID = :aID";
                $statement = $db->prepare($query);
                $statement->bindValue(':aID', $assignmentID, PDO::PARAM_INT);
                $result = $statement->execute();
                $students = $statement->fetchAll();
                $statement->closeCursor();

                if ($result)
                    Return $students;
                else
                    Return "Could not retrieve student assignment submissions";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function addStudentCourse($studentID, $courseID, $date)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "INSERT INTO Student_Course "
                        . "(StudentID, CourseID, DateAdded) "
                        . "VALUES(:sID, :cID, :date);";
                $statement = $db->prepare($query);
                $statement->bindValue(':sID', $studentID, PDO::PARAM_INT);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $statement->bindValue(':date', $date);
                $result = $statement->execute();
                $statement->closeCursor();

                if ($result)
                    Return "Student added to course";
                else
                    Return "Student not added to course";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function getStudentCourse($studentID, $courseID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select * From Student_Course "
                        . "Where StudentID = :uID AND CourseID = :cID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':sID', $studentID, PDO::PARAM_INT);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $result = $statement->execute();
                $studentCourse = $statement->fetch();
                $statement->closeCursor();

                if ($result)
                    Return "$studentCourse";
                else
                    Return "Assignment not updated";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function getStudentCourses($studentID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select * From Student_Course "
                        . "Where StudentID = :uID";
                $statement = $db->prepare($query);
                $statement->bindValue(':sID', $studentID, PDO::PARAM_INT);
                $result = $statement->execute();
                $studentCourses = $statement->fetchAll();
                $statement->closeCursor();

                if ($result)
                    Return "$studentCourses";
                else
                    Return "Could not retrieve student courses";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function getCourseEnrollment($courseID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select * From Student_Course "
                        . "CourseID = :cID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $result = $statement->execute();
                $studentEnrollment = $statement->fetchAll();
                $statement->closeCursor();

                if ($result)
                    Return "$studentEnrollment";
                else
                    Return "Could not retrieve students enrolled in course";
            } catch (PDOException $e)
            {
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

        function getUserAuth($username)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->db;
                $query = "Select Password From UserAccount "
                        . "Where Username= :uname";
                $statement = $db->prepare($query);
                $statement->bindValue(':uname', $username, PDO::PARAM_STR);
                $result = $statement->execute();
                $userPass = $statement->fetch();
                $statement->closeCursor();
                echo $result;
                if ($result)
                    Return $userPass;
                else
                    Return "Could not retrieve user password";
            } catch (PDOException $e)
            {
                echo $e->getMessage();
                $error_message = $e->getMessage();
                error_log($error_message);
            }
        }

    }

?>