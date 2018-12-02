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
        //Created as an alternative way to get user information, this function
        //performs the same as getUser, with the difference being that it restricts based on
        //UserID rather than Username
        //Used in admin, instructor, and project controllers
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
        //Purpose of this function is to update a users password,given that the
        //username and current password match database records.
        //All validation of new password is done prior to this function call
        //Is used in the login-functions file
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
                    $statement->bindValue(':close', $course->closeDate, PDO::PARAM_STR);
                    $output = $statement->execute();
                    $statement->closeCursor();

                    return $output;
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
                    else:
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

        //Purpose of this function is to retrieve all of the course terms in the database
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
                        echo "You are already registered.";
                    }
                }
                else
                {
                    echo "This is an invalid key";
                }
            } catch (PDOException $e)
            {
                echo "You have entered an incorrect course key.";
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
        
    }

?>
