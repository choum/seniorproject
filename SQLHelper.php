<?php
    require_once("private/Database.php");
    require("User.php");
    require("Course.php");
    require("Assignment.php");

    Class SQLHelper
    {

        function __construct()
        {

        }

    function addUser(User $user)
    {
        try
        {
            $dbObj = new Database();
            $db = $dbObj->getConnection();
            $query = "Insert into UserAccount "
                . "(Username, Password, FirstName, LastName, Title,"
                . " Bio, ImageLink, Linkedin, Website, UserRole, "
                . "Suspended, DateCreated, LastLoggedIn) "
                . "VALUES (:username, :password, :fName, :lName, :title,"
                . " :bio, :image, :linkedin, :website, :role, "
                . ":suspend, :creation, :lastLogin);";
            $statement = $db->prepare($query);
            $statement->bindValue(':username', $user->username,
                PDO::PARAM_STR);
            $statement->bindValue(':password', $user->password,
                PDO::PARAM_STR);
            $statement->bindValue(':fName', $user->firstName, PDO::PARAM_STR);
            $statement->bindValue(':lName', $user->lastName, PDO::PARAM_STR);
            $statement->bindValue(':title', $user->title, PDO::PARAM_STR);
            $statement->bindValue(':bio', $user->bio, PDO::PARAM_STR);
            $statement->bindValue(':image', $user->imageLink, PDO::PARAM_STR);
            $statement->bindValue(':linkedin', $user->linkedin,
                PDO::PARAM_STR);
            $statement->bindValue(':website', $user->website, PDO::PARAM_STR);
            $statement->bindValue(':role', $user->role, PDO::PARAM_INT);
            $statement->bindValue(':suspend', $user->suspended,
                PDO::PARAM_BOOL);
            $statement->bindValue(':creation', $user->dateCreated);
            $statement->bindValue(':lastLogin', $user->lastLoginDate);
            $statement->execute();
            $statement->closeCursor();

            return "User created";
        } catch (PDOException $e)
        {
            try
            {
                if ($user->imageLink == NULL OR $user->imageLink == "")
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

        /* Update of student user info in UserAccount table
         * Done via student dashboard
         */

    function updateUser($username, $bio = null, $imageLink = null,
                        $linkedin = null, $website = null)
    {
        try
        {
            $dbObj = new Database();
            $db = $dbObj->getConnection();
            $query = "Update UserAccount SET ImageLink = :image, Bio = :bio, LinkedIn = :linkedin, Website = :website WHERE Username = :uname";
            $statement = $db->prepare($query);
            $statement->bindValue(':bio', $bio, PDO::PARAM_STR);
            $statement->bindValue(':image', $imageLink, PDO::PARAM_STR);
            $statement->bindValue(':linkedin', $linkedin, PDO::PARAM_STR);
            $statement->bindValue(':website', $website, PDO::PARAM_STR);
            $statement->bindValue(':uname', $username, PDO::PARAM_STR);
            $statement->execute();
            $statement->closeCursor();

                if($count == 1):
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

        function getUser($username)
        {
            $dbObj = new Database();
            $db = $dbObj->getConnection();
            $query = "Select * From UserAccount Where Username = :uname;";
            $statement = $db->prepare($query);
            $statement->bindValue(':uname', $username, PDO::PARAM_STR);
            $statement->execute();
            $user = $statement->fetch();
            $statement->closeCursor();

            $return = new User($user[1], $user[2], $user[3],
                $user[4], $user[5], $user[6], $user[7], $user[8],
                $user[9], $user[10], $user[11], $user[12], $user[13], $user[14], $user[15]);
            $return->setID($user[0]);
            return $return;
        } catch (PDOException $e)
        {
            //$error_message = $e->getMessage();
            //error_log($error_message, (int)0,"./error.txt");

                return "Could not retrieve user data";
            }
        }

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

                $return = new User($user[1], $user[2], $user[3], $user[4], $user[5], $user[6], $user[7], $user[8], $user[9], $user[10], $user[11], $user[12], $user[13], $user[14], $user[15]);
                $return->setID($user[0]);
                return $return;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");

                return "Could not retrieve user data";
            }
        }

        /* Insertion of new instructor user to the UserAccount table
         * Done via admin dashboard
         */

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

        /* Updating instructor user info based on update instructor card to the
         * UserAccount table.
         * Done via admin dashboard
         */

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

                if($count == 1):
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

        function getInstructor($userID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select * From UserAccount "
                    . "Where UserID = :uid";
                $statement = $db->prepare($query);
                $statement->bindValue(':uid', $userID, PDO::PARAM_INT);
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
                return "Could not retrieve instructor";
            }
        }

        /*
         * Retrive all user information based on instructor user role
         */

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

        /*
         * Insertion of new course into Courses table
         * Done via admin dashboard
         */

        function addCourse(Course $course)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "INSERT INTO Courses"
                    . "(CourseTitle, CourseNumber, CourseSection, "
                    . "Term, Description, Closed, EnrollmentTotal, AdminID, "
                    . "TeacherID , CloseDate)"
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
                $statement->bindValue(':close', $course->close, PDO::PARAM_STR);
                $output = $statement->execute();
                $statement->closeCursor();

                return $output;
            } catch (PDOException $e)
            {
                //error_log($error_message, (int)0,"./error.txt");
                return "Course not created";
            }
        }

        function updateCourse($courseID, $courseTitle, $courseNumber,
            $courseSection, $term, $description, $closed, $enrollment, $adminID,
            $teacherID, $close)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
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
                $statement->bindValue(':cTitle', $courseTitle, PDO::PARAM_STR);
                $statement->bindValue(':cNumber', $courseNumber, PDO::PARAM_INT);
                $statement->bindValue(':cSection', $courseSection, PDO::PARAM_INT);
                $statement->bindValue(':term', $term, PDO::PARAM_STR);
                $statement->bindValue(':desc', $description, PDO::PARAM_STR);
                $statement->bindValue(':closed', $closed, PDO::PARAM_BOOL);
                $statement->bindValue(':enrollment', $enrollment, PDO::PARAM_INT);
                $statement->bindValue(':aID', $adminID, PDO::PARAM_INT);
                $statement->bindValue(':tID', $teacherID, PDO::PARAM_INT);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $statement->bindValue(':close', $close, PDO::PARAM_STR);
                $statement->execute();
                $count = $statement->rowCount();
                $statement->closeCursor();

                if($count == 1):
                    $db->commit();
                    return "Course updated";
                else:
                    $db->rollBack();
                    throw new PDOException;
                endif;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");

                return "Course not updated";
            }
        }

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

        /* Retrieves entire list of course with all course information
         * Done via admin dashboard TO ONLY BE USED THERE
         */

        function getAllCourses()
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select CourseID FROM Courses Group BY CourseNumber , CourseSection";
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

        //retrieve the courses of a term by instructor
        function getCoursesInstructorTerm($teacherID, $term)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select CourseID FROM Courses "
                    . "Where TeacherID = :tID "
                    . "And Term = :term "
                    . "Group By CourseNumber , CourseSection";
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

        //retrieve the courses of a term
        function getCoursesByTerm($term)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select CourseID FROM Courses "
                    . "Where Term = :term "
                    . "Group BY CourseNumber , CourseSection ";
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

        function getInstuctorCourses($teacherID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select CourseID FROM Courses "
                    . "Where TeacherID = :tID "
                    . "Group BY CourseNumber , CourseSection";
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

        function getCoursesOfTerm($term)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select CourseID From Courses "
                    . "Where Term = :term";
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
                return "Could not retrieve courses of $term term";
            }
        }

        function addAssignment($assignmentName, $description, $date,
            $pdf, $courseID, $teacherID, $type)
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

        function updateAssignment($assignmentID, $assignmentName, $description, $type,
            $date, $courseID, $teacherID, $pdf = NULL)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $db->beginTransaction();
                $query = "Update Assignments "
                    . "Set AssignmentName=:aName, Description=:desc, "
                    . "AssignmentDate=:date, PDFLocation=:pdf, CourseID=:cID, "
                    . "TeacherID=:tID, Type = :type "
                    . "Where AssignmentID = :aID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':aID', $assignmentID, PDO::PARAM_INT);
                $statement->bindValue(':aName', $assignmentName, PDO::PARAM_STR);
                $statement->bindValue(':desc', $description, PDO::PARAM_STR);
                $statement->bindValue(':date', $date);
                $statement->bindValue(':pdf', $pdf, PDO::PARAM_STR);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $statement->bindValue(':tID', $teacherID, PDO::PARAM_INT);
                $statement->bindValue(':type', $type, PDO::PARAM_STR);
                $statement->execute();
                $count = $statement->rowCount();
                $statement->closeCursor();

                if($count == 1):
                    $db->commit();
                    return "Assignment updated";
                else:
                    $db->rollBack();
                    throw new PDOException;
                endif;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Assignment not updated";
            }
        }

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

                $output = new Assignment($assignment[0], $assignment[1], $assignment[2], $assignment[3], $assignment[4], $assignment[5], $assignment[6], $assignment[7]);

                return $output;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retrieve assignment";
            }
        }

        //returns list of assignment for singular course based on its id, very limited.
        //Done in multiple dashboards.
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
                return "Assignment already exists";
            }
        }

        function updateStudentAssignment($studentID, $assignmentID, $dir,
            $dateCreated, $screenshot = NULL, $featured = NULL, $group = NULL)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $db->beginTransaction();
                $query = "Update Student_Assignment "
                    . "Set Directory`=:dir, "
                    . "DateCreated=:date, Screenshot=:screen, "
                    . "Featured=:featured, `Group`=:group "
                    . "Where StudentID=:sID And AssignmentID = :aID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':dir', $dir, PDO::PARAM_STR);
                $statement->bindValue(':date', $dateCreated);
                $statement->bindValue(':screen', $screenshot, PDO::PARAM_STR);
                $statement->bindValue(':featured', $featured, PDO::PARAM_BOOL);
                $statement->bindValue(':group', $group, PDO::PARAM_BOOL);
                $statement->bindValue(':sID', $studentID, PDO::PARAM_INT);
                $statement->bindValue(':aID', $assignmentID, PDO::PARAM_INT);
                $statement->execute();
                $count = $statement->rowCount();
                $statement->closeCursor();

                if($count == 1):
                    $db->commit();
                    return "Student assignment updated";
                else:
                    $db->rollBack();
                    throw new PDOException;
                endif;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Student assignment not updated";
            }
        }

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

    function getStudentsOfAssignment($assignmentID)
    {
        try
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

                return "Student added to course";
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Student not added to course";
            }
        }

        function getStudentCourse($studentID, $courseID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select * From Student_Course "
                    . "Where StudentID = :sID AND CourseID = :cID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':sID', $studentID, PDO::PARAM_INT);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $statement->execute();
                $studentCourse = $statement->fetch();
                $statement->closeCursor();

                return $studentCourse;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retrieve course of student";
            }
        }

        function getStudentCourses($studentID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select * From Student_Course "
                    . "Where StudentID = :sID";
                $statement = $db->prepare($query);
                $statement->bindValue(':sID', $studentID, PDO::PARAM_INT);
                $statement->execute();
                $studentCourses = $statement->fetchAll();
                $statement->closeCursor();

                return $studentCourses;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retrieve student's courses";
            }
        }

        function getStudentsEnrolled($courseID)
        {
            try
            {
                $dbObj = new Database();
                $db = $dbObj->getConnection();
                $query = "Select * From Student_Course "
                    . "Where CourseID = :cID;";
                $statement = $db->prepare($query);
                $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
                $statement->execute();
                $studentEnrollment = $statement->fetchAll();
                $statement->closeCursor();


                return $studentEnrollment;
            } catch (PDOException $e)
            {
                //$error_message = $e->getMessage();
                //error_log($error_message, (int)0,"./error.txt");
                return "Could not retrieve students enrolled in course";
            }
        }

        function getSubmissionsOfCourse($courseID)
        {
            $dbObj = new Database();
            $db = $dbObj->getConnection();
            $query = "Select AssignmentID From Assignments "
                . "Where CourseID=:cID";
            $statement = $db->prepare($query);
            $statement->bindValue(':cID', $courseID, PDO::PARAM_INT);
            $result = $statement->execute();
            $assignments = $statement->fetchAll();
            $statement->closeCursor();

            if ($result)
            {
                $listOfStudentAssignments = array();
                foreach ($assignments as $assignment)
                {
                    $students = $this->getStudentsOfAssignment($assignment[0]);
                    if (is_array($students))
                    {
                        foreach ($students as $student)
                        {
                            $studentAssignment = $this->getStudentAssignemnt($student[0], $assignment[0]);
                            if (is_array($studentAssignment))
                            {
                                array_push($listOfStudentAssignments, $studentAssignment);
                            }
                            else
                                    return "Could not retrieve student $student[0]'s assignment $assignment[0]";
                        }
                    }
                    else
                            return "Could not retrieve students of assignment $assignment[0]";
                }
                return $listOfStudentAssignments;
            }
            else
                    return "Could not retrieve assignment ID's based on course id $courseID";
        }

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

    }

    function getUserID($username){
        try{
            $dbObj = new Database();
            $db = $dbObj->getConnection();
            $query = "SELECT UserID FROM UserAccount  WHERE Username = :uName";
            $statement = $db->prepare($query);
            $statement->bindValue(':uName', $username, PDO::PARAM_STR);
            $statement->execute();
            $user = $statement->fetch();
            $userID = $user['UserID'];
            return $userID;
        }

        catch(Exception $e){
            return 'User not found';
        }
    }

    function getUserCourses($userID){
        try{
            $dbObj = new Database();
            $db = $dbObj->getConnection();
            $query = "SELECT CourseID FROM Student_Course WHERE StudentID = :uID";
            $statement = $db->prepare($query);
            $statement->bindValue(':uID', $userID, PDO::PARAM_INT);
            $statement->execute();
            $courseIDList = $statement->fetchAll(PDO::FETCH_ASSOC);
            $courses = Array();
            foreach ($courseIDList as $courseID){
                try{
                    $course = $this->getCourse($courseID['CourseID']);
                    array_push($courses, $course);
                }
                catch (Exception $e){
                    return 'failed to get course';
                }

            }
            return $courses;

        }

        catch(Exception $e){
            return $e;
        }
    }

    function getUserAssignments($courseID){
        $assignments = $this->getAssignments($courseID);
        $assignmentsList = array();
        foreach($assignments as $assignment){
            $singleAssignment = $assignment['AssignmentID'];
            array_push($assignmentsList, $assignment);
        }

        return $assignmentsList;

    }

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
            if($statement->rowCount() != 0):
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

                if($count == 1):
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

}

?>
