<?php

require_once "Dates.php";
require_once "SQLHelper.php";
require_once "Course.php";

//get the action form the request
$action = filter_input(INPUT_POST , 'action');

$message = "";
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

//run the appropriate function depending on request
if (empty($action)) {

}
  elseif($action == 'add_project') {
    addProject($current_user->id , $username , $message);
} else if($action == 'project') {
   viewProject();
} else if($action == 'course_key'){
    addUpdateCourseKey();
}

//get array of user terms
//create an array of terms from the database
$terms = [];
$term_string = $db->getTermsbyInstructor($current_user->id);
foreach ($term_string as $term) {
  array_push($terms , $term[0]);
}

$courses = array();
$course_string = $db->getInstuctorCourses($current_user->id);
foreach ($course_string as $course) {
  array_push($courses , $db->getCourse($course[0]));
}

$current_selected_course = "";
$temp_course = filter_input(INPUT_POST , 'current_selected_course');
if($temp_course != Null) {
  $current_selected_course = $db->getCourse($temp_course);
} else if(sizeof($courses) > 0){
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

$courseKeys = array();
foreach($courseString as $courseID):
    $tempCourse = $db->getCourse($courseID[0]);
    $tempCourseKey = $tempCourse->courseKey;
    array_push($courseKeys, $tempCourseKey);
endforeach;

$createOrUpdate;
if($current_selected_course != ""):
    if($current_selected_course->courseKey == NULL):
        $createOrUpdate = "Create Course Key";
    else:
        $createOrUpdate = "Update Course Key";
    endif;
else:
    $createOrUpdate = "Create Course Key";
endif;

function addProject($id , $username , $message) {
  //get all the variebles from the input post
  $today = date("Y/m/d");
  $name = filter_input(INPUT_POST , 'name');
  $description = filter_input(INPUT_POST , 'description');
  $course = filter_input( INPUT_POST , 'course');
  $type = filter_input(INPUT_POST , 'type');
  $pdf = NULL;

  //if there is a file to insert to database
  if($_FILES['file']['size'] > 0 ){
      //get the file from the input file
      $errors= array();
      $file_name = $_FILES['file']['name'];
      $file_size =$_FILES['file']['size'];
      $file_tmp =$_FILES['file']['tmp_name'];
      $file_type=$_FILES['file']['type'];
      $ext_arr = explode('.' , $file_name );
      $ext = end($ext_arr);
      $file_ext=strtolower($ext);

      $expensions= array("jpeg","jpg","png" , "pdf");

      if(in_array($file_ext,$expensions)=== false){
         array_push($errors,"extension not allowed, please choose a JPEG or PNG file.");
      }

      if($file_size > 20971520){
         array_push($errors, 'File size must be less than 20 MB');
      }

      if(empty($errors)==true){
        $str_temp = "cap/" . $username;
        if (!is_dir($str_temp)) {
          mkdir($str_temp, 0777, true);
        }

        $str_temp = "cap/" . $username . "/" . $course;
        if(!is_dir($str_temp)) {
          mkdir($str_temp, 0777, true);
        }
         $bool = move_uploaded_file($file_tmp, $str_temp . "/" . $file_name);

         if($bool) {
           $db = new SQLHelper();
           $results = $db->addAssignment($name, $description, $today, $file_name, $course, $id, $type);
           //echo "Assignment uploaded successsfully.";
         } else {
           //echo "Could not add file to database.";

         }

      }//end of error is empty

   } else {

     $db = new SQLHelper();
     $results = $db->addAssignment($name, $description, $today, $pdf, $course, $id, $type);
     //echo "Assignment uploaded successsfully. No file";
   }//end of is else for file is set

}//end of addProjectS

function viewProject() {

}

function addUpdateCourseKey(){
    $currentCourseID = filter_input(INPUT_POST, 'course');
    $key = filter_input(INPUT_POST, 'key');
    $db = new SQLHelper();
    $db->updateCourseKey($currentCourseID, $key);
}
?>
