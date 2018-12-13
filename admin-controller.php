<?php
    /*
     * Created by: Nat Rivera
     * Description: This file acts as the controller portion to the instructor dashboard, and
     * as such gathers all information prior to being displayed on the view. Along with this
     * it controls what occurs when adding or updating the instructor or class, generating
     * the password for instructor accounts, and emailing instructors about their newly created accounts.
     */
    require_once "Dates.php";
    require_once "SQLHelper.php";
    require_once "Course.php";

    $errorAddC = "";
    $errorUpdateC = "";
    $errorAddP = "";
    $errorUpdateP = "";
    //get the action form the request
    $action = filter_input(INPUT_POST, 'action');

    //run the appropriate function depending on request
    if (empty($action))
    {

    }
    elseif ($action == 'add_instructor')
    {
        $errorAddP = addInstructor();
    }
    elseif ($action == 'update_instructor')
    {
        $errorUpdateP = updateInstructor();
    }
    elseif ($action == 'add_class')
    {
        $errorAddC = addClass();
    }
    elseif ($action == 'update_class')
    {
        $errorUpdateC = updateClass();
    }

    //get the username from session
    $username = "";
    if (isset($_SESSION))
    {
        $username = $_SESSION["user"];
    }

    //create an istance of SQLHelper to get data from database
    //load the arrays to use on the UI
    $db = new SQLHelper();

    //create object of dates class and get the current semester and year
    $dateOB = new Dates;
    $semester_year = $dateOB->getSemesterYear();
    $date_terms = $dateOB->terms;
    $date_years = $dateOB->years;

    //create an array of instructors from the database
    $instructors = array();
    $instructor_string = $db->getInstructors();
    foreach ($instructor_string as $instructor)
    {
        $tempstr = $instructor[2] . ", " . $instructor[1];
        $temp_id = $instructor[0];
        $temp_email = $instructor[3];
        $instructors[$temp_id] = [$temp_id, $tempstr, $temp_email , $instructor[1] , $instructor[2]];
        asort($instructors);
    }
    $currrent_chosen_instructor = reset($instructors);

    //create an array of terms from the database
    $terms = [];
    $term_string = $db->getTerms();
    foreach ($term_string as $term)
    {
        array_push($terms, $term[0]);
    }

    //get current user
    $current_user = $db->getUser($username);

    $courses = [];
    $course_string = $db->getAllCourses();
    foreach ($course_string as $course)
    {
        $temp_course = $db->getCourse($course[0]);
        $temp_year = substr($temp_course->term, -2);
        $temp_term = substr($temp_course->term, 0, -5);
        $temp_section = $temp_course->courseSection;
        $temp_id = $temp_course->courseNumber;
        if (intval($temp_id) < 999)
        {
            $temp_id = "0" . $temp_id;
        }
        if (intval($temp_section) < 10)
        {
            $temp_section = "0" . $temp_section;
        }
        $temp_count = $dateOB->countTerm($temp_term);
        $temp_add = $temp_year . $temp_count . $temp_id . $temp_section;
        $courses[$temp_add] = $temp_course;
    }
    krsort($courses);
    $current_selected_course = $db->getCourse(reset($courses)->courseID);

    $temp_courseID = filter_input(INPUT_POST, 'course_change_select');
    if ($temp_courseID != NULL)
    {
        $current_selected_course = $db->getCourse($temp_courseID);
    }

    //if user selected a term to view for courses
    //else use current session
    $temp_sem = filter_input(INPUT_POST, 'user_selected_term');
    if ($temp_sem != NULL)
    {
        $semester_year = $temp_sem;
    }


    //create an array of courses from the database
    $current_user_courses = array();
    $courseString = $db->getCoursesByTerm($semester_year);
    foreach ($courseString as $courseID)
    {
        $temp_course = $db->getCourse($courseID[0]);
        $temp_instructor = $db->getUserByID($temp_course->teacherID);
        $temp_assignments = $db->getAssignments($courseID[0]);
        $temp_instructor_name = $temp_instructor->firstName . " " . $temp_instructor->lastName;
        $temp_arr = [$temp_course, $temp_instructor_name, $temp_assignments];
        array_push($current_user_courses, $temp_arr);
    }


    //if any of the quesries could not run create an error to display
    if (!empty($result))
    {
        $error = $result;
    }

    /*
     * Purpose of this function is to gather all information needed to create an instructor account, 
     * generates a random password, and adds the newly created account using the addInstructor
     * function in SQLHelper. If this succeeds then an email is sent using emailInstructor to the email account
     * given. It makes use of the User class object before calling the addInstructor function.
     */
    function addInstructor()
    {

        //get the variable from the request
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password_random = generateRandomString(25);
        $unsalted = $password_random;
        $password_hashed = password_hash($password_random, PASSWORD_BCRYPT);
        //create an instance of the User class
        $temp_user = new User(substr($firstName, 0, 1) . $lastName, $password_hashed, $firstName, $lastName, "title", NULL, $email, NULL, NULL, NULL, 2, 0, "create", "");

        //create an instance of the SQLHelper class
        //add user to database
        $db = new SQLHelper();
        $result = $db->addInstructor($temp_user);
        if($result[0] == "Instructor created"){ 
            $return = emailInstructor($email, $result[1], $unsalted); 
            return $return;
        }
        else{
            return $result;
        }
        
    }

    function emailInstructor($email, $username, $unsalted)
    {
        // Message
        $message = "Your portfolio has been created by an admin on this website. \nAccount username: $username \nPassword: $unsalted";
        $bool = mail($email, 'CIS Application Portfolio Password', $message);

        if($bool == false){
            return  "Could not send email";
        }
    }
    /*
     * This function allows the updating of some account information, but does not currently change
     * the username, which is tied to the first and last name, or send an email regarding the change.
     * It makes use of the updateInstructor function of SQLHelper, more information about it can be found in that file.
     */
    function updateInstructor()
    {

        //get the variable from the request
        $instructorID = filter_input(INPUT_POST, 'instructorID');
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $email = filter_input(INPUT_POST, 'email');
        //create an instance of the SQLHelper class
        //update user in database
        $db = new SQLHelper();
        $result = $db->updateInstructor($instructorID, $firstName, $lastName, $email);
        if($result == "Instructor not updated"){
            return $result;
        }
    }//end of edit instructor

    /*
     * Purpose of this function is to, if all information is valid, to add a course for use elsewhere.
     * The course is givena close date, is packaged using the Course class object, and is sent to the
     * addCourse function of the SQLHelper file. Information on  getUser and addCourse can be found in the SQLHelper
     * file
     */
    function addClass()
    {
        //get the variable from the request
        $courseID = filter_input(INPUT_POST, 'courseID');
        $sectionNumber = filter_input(INPUT_POST, 'sectionNumber');
        $term_term = filter_input(INPUT_POST, 'term');
        $term_year = filter_input(INPUT_POST, 'term-year');
        $term = $term_term . " " . $term_year;
        $classTitle = filter_input(INPUT_POST, 'classTitle');
        $classInstructorID = filter_input(INPUT_POST, 'classInstructor');
        $classDescription = filter_input(INPUT_POST, 'classDescription');

        $dateOB = new Dates;
        $close = $dateOB->getCloseDate($term);

        $db = new SQLHelper();
        $classAdminID = $db->getUser($_SESSION['user'])->id;
        //create an instance of the Course class
        $course = new Course($classTitle, $courseID, $sectionNumber, $term, $classDescription, 0, 0, $classAdminID, $classInstructorID, $close);

        //create an instance of the SQLHelper class
        //add CourseSection to database

        $result = $db->addCourse($course);
        if($result !== TRUE){
            return $result;
        }
    }// end of add class function
 
    /*
     * Purpose of the function is to update nearly if not all information of a given information of a selected course.
     * On update, the close date is updated to match the chosen term, a Course class object is made before calling the 
     * updateCourse function of the SQLHelper file. Information on getUser and updateCourse can be found in the 
     * SQLHelper file.
     */
    function updateClass()
    {

        //get the variable from the request
        $courseID = filter_input(INPUT_POST, 'course_change_select');
        $courseNumber = filter_input(INPUT_POST, 'courseNumber');
        $sectionNumber = filter_input(INPUT_POST, 'sectionNumber');
        $term_term = filter_input(INPUT_POST, 'term');
        $term_year = filter_input(INPUT_POST, 'term-year');
        $term = $term_term . " " . $term_year;
        $classTitle = filter_input(INPUT_POST, 'classTitle');
        $description = filter_input(INPUT_POST, 'classDescription');
        $teacherID = filter_input(INPUT_POST, 'classInstructor');

        $dateOB = new Dates;
        $close = $dateOB->getCloseDate($term);

        //create an instance of the SQLHelper class
        //update CourseSection in database
        $db = new SQLHelper();
        $classAdmin = $db->getUser($_SESSION['user'])->id;
        $course = new Course($classTitle, $courseNumber, $sectionNumber, $term, $description, 0, 0, $classAdmin, $teacherID, $close);
        $course->setID($courseID);
        $result = $db->updateCourse($course);
        
        if($result != "Course updated"){
            return $result;
        }
    }// end of update class function

    /*
     * Purpose of this function is to generate a password for instructor accounts, as they are created
     * using the instructor dashboard and not through the normal registration process. 
     */
    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

?>
