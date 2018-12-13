<?php
/*
 * Created By: Nat Rivera and Justin Crest
 * Description: This file is treated as the controller section of the instructor dashboard
 * and gathers all information prior to being displayed., including the user information,
 * courses that the instructor teaches, assignments tied to said courses, and so on.
 */
require_once "Dates.php";
require_once "SQLHelper.php";
require_once "Course.php";

//get the action form the request
$action = filter_input(INPUT_POST , 'action');

$errorAdd = "";
$errorKey = "";
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
    $errorAdd = addProject($current_user->id , $username);
} else if($action == 'course_key'){
    $errorKey = addUpdateCourseKey();
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
/*
 * Purpose of this function is to add an assignment to a given course. If the assignment is 
 * created and a file is given, that file is verified by type and size prior to being uploaded.
 * Each assignment has its own directory in order to ensure that files are not accidentally overwritten,
 * as there is no current way to warn the user that the file name already is in use.
 * Once all is is said and done, the assignment is added to the database using the addAssignment function
 * of SQLHelper. More info on this function can be found in the SQLHelper file.
 */
function addProject($id , $username) {
  //get all the variebles from the input post
  $today = date("Y/m/d");
  $name = filter_input(INPUT_POST , 'name');
  $description = filter_input(INPUT_POST , 'description');
  $course = filter_input( INPUT_POST , 'course');
  $type = filter_input(INPUT_POST , 'type');
  $pdf = NULL;

  define('SITE_ROOT', realpath(dirname(__FILE__)));
  $file = $_FILES['file'];
  $uploadDirectory = "/cap/" . $username . "/" . $course . "/";

  try
  {
      if (isset($file) && $file['name'] != '')
      {
          $maxsize = 10000000;
          $acceptable = array(
              'image/jpeg',
              'image/jpg',
              'image/gif',
              'image/png',
              'application/pdf'
          );
          $errors = 0;
          $fileName = $file['name'];
          $fileSize = $file['size'];
          $fileType = $file['type'];
          $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
          $fileExtension = strtolower($fileExtension);
          $fileTmp = $file['tmp_name'];
          $fileDestination = $fileName;
          if (!in_array($fileType, $acceptable) && !empty($fileType))
          {
              $errorAdd = 'Invalid file type.';
              $errors++;
          }

          if ($fileSize >= $maxsize || $fileSize === 0)
          {
              $errorAdd = 'File must be under 1MB';
              $errors++;
          }

          if ($errors === 0)
          {
              if (!is_dir(SITE_ROOT . '/cap/' . $username)) {
                  mkdir(SITE_ROOT . '/cap/' . $username, 0755, true);
              }
              if (!is_dir(SITE_ROOT . $uploadDirectory))
              {
                  mkdir(SITE_ROOT . $uploadDirectory, 0755, true);
              }
              try
              {
                  $uploadBool = move_uploaded_file($fileTmp, SITE_ROOT . $uploadDirectory . $fileDestination);
                  $db = new SQLHelper();
                  $results = $db->addAssignment($name, $description, $today, $fileName, $course, $id, $type);
              } catch (Exception $e)
              {
                  $errorAdd = 'File upload failed';
              }
          }
      }
      else
      {
        $fileName = null;
        $db = new SQLHelper();
        $results = $db->addAssignment($name, $description, $today, $fileName, $course, $id, $type);
      }
  } catch (Exception $e)
  {
      $errorAdd = 'Failed';
  }
  
  if ($errorAdd != null){
      return $errorAdd;
  }
  else{
      return null;
  }


}//end of addProjects
/*
 * Purpose of this function is to call the updateCourseKey function of the SQLHelper file.
 * More info about this function is located in the SQLHelper file. 
 */
function addUpdateCourseKey(){
    $currentCourseID = filter_input(INPUT_POST, 'course');
    $key = filter_input(INPUT_POST, 'key');
    $db = new SQLHelper();
    $return = $db->updateCourseKey($currentCourseID, $key);
    if($return != "Course key changed."):
        return $return;
    endif;
}
?>
