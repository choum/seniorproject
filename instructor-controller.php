<?php

require_once "Dates.php";
require_once "SQLHelper.php";
require_once "Course.php";

//get the action form the request
$action = filter_input(INPUT_POST , 'action');



//create object of dates class and get the current semester and year
$dateOB = new Dates;
$semester_year = $dateOB->getSemesterYear();

//create an istance of SQLHelper to get data from database
//load the arrays to use on the UI
$db = new SQLHelper();

//get the username from session
$username = $_SESSION["user"];
//get user object from database
$current_user = $db->getUser($username);
//concat first and lasat name for display
$current_user_name = $current_user->firstName . " " . $current_user->lastName;

//run the appropriate function depending on request
if (empty($action)) {

}
  elseif($action == 'add_project') {
    addProject($current_user->id);
} else if($action == 'project') {
   viewProject();
}

//get array of user terms
//create an array of terms from the database
$terms = [];
$term_string = $db->getTerms();
foreach ($term_string as $term) {
  array_push($terms , $term[0]);
}

$courses = [];
$course_string = $db->getInstuctorCourses($current_user->id);
foreach ($course_string as $course) {
  //echo $course[0];
  array_push($courses , $db->getCourse($course[0]));
}
if($courses != []) {
  $current_selected_course = $db->getCourse($courses[0]->courseID);
}

//if user selected a term to view for courses
//else use current session
$temp_sem = filter_input(INPUT_POST , 'user_selected_term');
if($temp_sem != NULL) {
  $semester_year = $temp_sem;
}


//create an array of courses from the database
$current_user_courses = [];
$courseString = $db->getCoursesInstructorTerm($current_user->id , $semester_year);
foreach ($courseString as $courseID ) {
  $temp_course = $db->getCourse($courseID[0]);
  $temp_instructor = $db->getUserByID($temp_course->teacherID);
  $temp_assignments = $db->getAssignments($courseID[0]);
  $temp_instructor_name = $temp_instructor->firstName . " " . $temp_instructor->lastName;
  $temp_arr = [$temp_course , $temp_instructor_name , $temp_assignments];
  array_push($current_user_courses, $temp_arr);
}

function addProject($id) {
  $today = date("m/d/Y");
  $name = filter_input(INPUT_POST , 'name');
  $description = filter_input(INPUT_POST , 'description');
  $course = filter_input( INPUT_POST , 'course');
  $type = filter_input(INPUT_POST , 'type');

  $db = new SQLHelper();
  $results = $db->addAssignment($name , $description , $today , $course , $id , NULL);
}

function viewProject() {

}

?>
