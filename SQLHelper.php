<?php
    /*
     * Created By: Justin Crest
     * Description: This file serves as a starting point for all other files starting with
     * SQL. All other files in the code come to this file for queries and are redirected to where
     * the query is actually located. This was done to split the query file into more manageable parts
     * and allow for clearer distinction on what is used where.
     */
    require_once("private/Database.php");
    require("User.php");
    require("Course.php");
    require("Assignment.php");
    
    require("SQLLoginRegistration.php");
    require("SQLShared.php");
    require("SQLAdmin.php");
    require("SQLInstructor.php");
    require("SQLStudent.php");

    /*
     * BE AWARE: This class has been split into the five different requires placed just above
     * this message. All functions have been redirected to their actual counterparts.
     * Not all functions are called directly by this class and as such do not need
     * redirection, but it is left in due to simplicity and organization.
     * If you desire to remove the redirections, please make sure that you don't remove ones that
     * have actual connections to the rest of the files, as this will change multiple errors.
     */
    Class SQLHelper
    {

        function __construct()
        {

        }
        //This function is used to add users to the database on valid account registration
        //Used in the login-functions file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function addUser(User $user)
        {
            $SQL = new SQLLoginRegistration();
            $return = $SQL->addUser($user);
            return $return;
        }
        //This function is used to update some information of student user accounts
        //Used in the student-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function updateUser($userID, $bio = null, $imageLink = null,
            $linkedin = null, $website = null)
        {
            $SQL = new SQLStudent();
            $return = $SQL->updateUser($userID, $bio, $imageLink, $linkedin, $website);
            return $return;
        }
        //Based on username, returns all student user information in a User object
        //Used in admin, instructor, profile, and student controller files, as well as the header file
        //and the registerCourse function
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getUser($username)
        {
            $SQL = new SQLShared();
            $return = $SQL->getUser($username);
            return $return;
        }
                
        //Created as an alternative way to get user information, this function
        //performs the same as getUser, with the difference being that it restricts based on
        //UserID rather than Username
        //Used in admin, instructor, and project controllers
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getUserByID($id)
        {
            $SQL = new SQLShared();
            $return = $SQL->getUserByID($id);
            return $return;
        }
        //This function is used to compare the user given password to the password stored in the DB
        //Along with this, the role is gotten in order to properly redirect the user on successful login
        //Used in the login-functions file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getUserAuth($username)
        {
            $SQL = new SQLLoginRegistration();
            $return = $SQL->getUserAuth($username);
            return $return;
        }
        //This function is used to keep track of and update the user on their most recent login
        //As well as allow later functionality for certain actions to occur on first login.
        //Used in the login-functions file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function updateLastLoggedIn($username, $loggedIn)
        {
            $SQL = new SQLLoginRegistration();
            $return = $SQL->updateLastLoggedIn($username, $loggedIn);
            return $return;
        }
        //Purpose of this function is to check if the username desired
        //for account regisrtation is available. 
        //Used in the login-functions file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function checkForDuplicate($username)
        {
            $SQL = new SQLLoginRegistration();
            $return = $SQL->checkForDuplicate($username);
            return $return;
        }

        //Purpose of this function is to add instructor users to the database, as well as
        //Generate differing usernames if the one given is already in use. 
        //Used in the admin-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function addInstructor(User $user)
        {
            $SQL = new SQLAdmin();
            $return = $SQL->addInstructor($user);
            return $return;
        }

        //Purpose of this function is to update the limited information pertaining to instructors
        //At this time, it only updates the first name, last name, and email columns
        //Username, which is originally based on the first and last name, does not change.
        //Used in the admin-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function updateInstructor($userID, $firstName, $lastName, $email)
        {
            $SQL = new SQLAdmin();
            $return = $SQL->updateInstructor($userID, $firstName, $lastName, $email);
            return $return;
        }

        //Purpose is this function is retrieve user data for only instructors, which
        //is restricted to only roles 2 and 4 currently.
        //Used in the admin-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getInstructors()
        {
            $SQL = new SQLAdmin();
            $return = $SQL->getInstructors();
            return $return;
        }
        //Purpose of this function is to update a users password,given that the
        //username and current password match database records.
        //All validation of new password is done prior to this function call
        //Is used in the login-functions file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function changePassword($username, $password, $newPassword)
        {
            $SQL = new SQLLoginRegistration();
            $return = $SQL->changePassword($username, $password, $newPassword);
            return $return;
        }
        //Purpose of this function is to increase and decrease the courses a user is enrolled in
        //At this time, it is called in the registerCourse function, which adds an entry to the 
        //batabase on proper course registration
        //Used in the registerCourse function
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function updateCoursesEnrolled($userID, $userCourses, $incOrDec)
        {
            $SQL = new SQLShared();
            $return = $SQL->updateCoursesEnrolled($userID, $userCourses, $incOrDec);
            return $return;
        }
        //Purpose of this function is to retrieve the UserID based on the username
        //Is used in the student-controller file.
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getUserID($username)
        {
            $SQL = new SQLStudent();
            $return = $SQL->getUserID($username);
            return $return;
        }
        //Purpose of this function is to add a new course based on information in 
        //the Course object, as well as assign a specific instructor to the course
        //Used in the admin-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function addCourse(Course $course)
        {
           $SQL = new SQLAdmin();
           $return = $SQL->addCourse($course);
           return $return;
        }
        //Purpose of this course is to work in tandem with the addCourse function
        //Its  use is to ensure that not duplicate courses for the term chosen 
        //are added to the database, as courses with the same number and section should
        //not exist.
        //Used in the addCourse and updateCourse function.
        //Note: These functions have since been moved to the SQLAdmin file, along with a
        //copy of this function. This function remains for purely reference purposes.
        function checkDuplicateCourseForTerm(Course $course)
        {
            $SQL = new SQLAdmin();
            $return = $SQL->checkDuplicateCourseForTerm($course);
            return $return;
        }
        //Purpose of this function is to update course information to whatever is
        //entered. All validation is done prior to function call.
        //It also calls the checkDuplicateCourseForTerm function in order to ensure
        //that it does not update to a course that already exists for the term.
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function updateCourse(Course $course)
        {
            $SQL = new SQLAdmin();
            $return = $SQL->updateCourse($course);
            return $return;
        }
        //Purpose of this function is to update a course's course key.
        //This covers both adding a course key for the first time and changing a course key
        //Used in instructor-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function updateCourseKey($courseID, $courseKey)
        {
            $SQL = new SQLInstructor();
            $return = $SQL->updateCourseKey($courseID, $courseKey);
            return $return;
        }
        //Purpose of this function is to ensure no duplicate course keys are used in the database.
        //Used in updateCourseKey function
        //NOTE: Can be taken out if unique modifier is added to the CourseKey column
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function checkDuplicateCourseKey($courseID, $courseKey)
        {
            //Since this function is used by updateCourseKey it is stored in SQLInstructor
            $SQL = new SQLInstructor();
            $return = $SQL->checkDuplicateCourseKey($courseID, $courseKey);
            return $return;
        }
        //Purpose of this function is to retrieve course information give a courseID
        //Used in getUserCourses function, admin, instructor, profile, project, and 
        //student controller files
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getCourse($courseID)
        {
            $SQL = new SQLShared();
            $return = $SQL->getCourse($courseID);
            return $return;
        }
        
        //Purpose of this function is to get all of the courses that a student belongs to
        //Once that occurs, it gets all the course information tied to the id's returned
        //and puts them in an array
        //Used in student-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getUserCourses($userID)
        {
            //Unlike other moved functions, since this function requires getCourse, it was
            //placed in the SQLShared file.
            $SQL = new SQLShared();
            $return = $SQL->getUserCourses($userID);
            return $return;
        }
        
        //Purpose of this function is to retrieve all courseIDs without restriction
        //Used in admin-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getAllCourses()
        {
            $SQL = new SQLAdmin();
            $return = $SQL->getAllCourses();
            return $return;
        }

        //Purpose of this function is to retrieve all the course ids  for a specific
        //instructor and a specific term. Used to due instructors only being able to
        //view courses of current term.
        //Used in instructor-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getCoursesInstructorTerm($teacherID, $term)
        {
            $SQL = new SQLInstructor();
            $return = $SQL->getCoursesInstructorTerm($teacherID, $term);
            return $return;
        }

        //Purpose of this function is to retrieve all course ids for a specific term
        //Used in admin-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getCoursesByTerm($term)
        {
            $SQL = new SQLAdmin();
            $return = $SQL->getCoursesByTerm($term);
            return $return;
        }
        
        //Purpose of this function is to retrieve all course ids for a specific instructor
        //Used in instructor-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getInstuctorCourses($teacherID)
        {
            $SQL = new SQLInstructor();
            $return = $SQL->getInstuctorCourses($teacherID);
            return $return;
        }

        //Purpose of this function is to retrieve all of the course terms in the database
        //Used in the admin-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getTerms()
        {
            $SQL = new SQLAdmin();
            $return = $SQL->getTerms();
            return $return;
        }
        //Purpose of this function is to retrieve all of the terms in the database
        //for a specific instructor
        //Used in the instructor-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getTermsbyInstructor($teacherID)
        {
            $SQL = new SQLInstructor();
            $return = $SQL->getTermsbyInstructor($teacherID);
            return $return;
        }
        //Purpose of this function is to add a new assignment for specific course
        //to the database. After this point, the assignment is available for viewing 
        //in the other dashboards. There is currently no way to update an assignment by standard means
        //Used in the instructor-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function addAssignment($assignmentName, $description, $date, $pdf, $courseID, $teacherID, $type)
        {
            $SQL = new SQLInstructor();
            $return = $SQL->addAssignment($assignmentName, $description, $date, $pdf, $courseID, $teacherID, $type);
            return $return;
        }
        //Purpose of this function is to retrieve all assignment data for a specific
        //assignment based on its ID. It is then put in an Assignment object
        //Used in the profile, project, and student controller
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getAssignment($assignmentID)
        {
            $SQL = new SQLShared();
            $return = $SQL->getAssignment($assignmentID);
            return $return;
        }

        //Purpose of this function is to get the assignment IDs and names of 
        //all assignments for a specific course based on the course id.
        //Used in getUserAssignments function, admin, instructor, and student controllers
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getAssignments($courseID)
        {
            $SQL = new SQLShared();
            $return = $SQL->getAssignments($courseID);
            return $return;
        }
        //Purpose of this function is to pre-place results from getAssignments into an array
        //Has no other function
        //Used in student-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getUserAssignments($courseID)
        {
            //Since this function requires getAssignments it is stored in SQLShared
            //rather than SQLStudent
            $SQL = new SQLShared();
            $return = $SQL->getUserAssignments($courseID);
            return $return;
        }
        //Purpose of this function is to add a student assignment submission to the database
        //Used by the readdStudentAssignment function and student-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function addStudentAssignment($studentID, $assignmentID, $dir,
            $dateCreated, $screenshot, $featured, $group)
        {
            $SQL = new SQLStudent();
            $return = $SQL->addStudentAssignment($studentID, $assignmentID, $dir,
                $dateCreated, $screenshot, $featured, $group);
            return $return;
        }

        //Purpose of this function is to delete a student submission before readding it
        //using the addStudentAssignment function. 
        //Used by the student-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function readdStudentAssignment($studentID, $assignmentID, $dir,
            $dateCreated, $screenshot, $featured, $group)
        {
            $SQL = new SQLStudent();
            $return = $SQL->readdStudentAssignment($studentID, $assignmentID, $dir, 
                $dateCreated, $screenshot, $featured, $group);
            return $return;
        }
        //Purpose of this function is to return all student assignment submission
        //information that is stored in the database based on the assignment's id
        //and the student who the assignment belongs to.
        //Used in the profile, project, and student controller files
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getStudentAssignment($studentID, $assignmentID)
        {
            $SQL = new SQLShared();
            $return = $SQL->getStudentAssignment($studentID, $assignmentID);
            return $return;
        }
        //Purpose of this function is to retrieve all the assignment ids related
        //to the assignments submitted by a specific student.
        //Used by the profile-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getStudentAssignments($studentID)
        {
            $SQL = new SQLShared();
            $return = $SQL->getStudentAssignments($studentID);
            return $return;
        }
        //Purpose of this function is to retrieve all the student ids of specific assignment
        //submissions to be used elsewhere.
        //Used in the project-controller file
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function getStudentsOfAssignment($assignmentID)
        {
            $SQL = new SQLShared();
            $return = $SQL->getStudentsOfAssignment($assignmentID);
            return $return;
        }
        //Purpose of this function is to reset all student assignment submissions
        //for a specific student to not be featured, and set a new assignment submission
        //to be featured.
        //Used in the student-controller file
        function changeFeaturedAssignment($studentID, $assignmentID)
        {
            $SQL = new SQLStudent();
            $return = $SQL->changeFeaturedAssignment($studentID, $assignmentID);
            return $return;
        }
        
        //Purpose of this function is to add a student to the list of 
        //students of a given course.
        //Used in the registerCourse function
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function addStudentCourse($studentID, $courseID, $date)
        {
            $SQL = new SQLShared();
            $return = $SQL->addStudentCourse($studentID, $courseID, $date);
            return $return;
        }
        //Purpose of this function is to add a student to a course, which
        //in turn will allow them to access assignments to make submissions.
        //Used in the student-controller file
        //This function also makes use of the addStudentCourse and getUser functions
        //NOTE: A complete version of this function is in the file with the same name
        //as the class object created here. Please direct any issues to that file.
        function registerCourse($username, $key, $date)
        {
            $SQL = new SQLShared();
            $return = $SQL->registerCourse($username, $key, $date);
            return $return;
        }
    }

?>
