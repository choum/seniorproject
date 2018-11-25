<?php
    require_once "SQLHelper.php";
    require_once "zip/Zip.php";
    define ('SITE_ROOT', realpath(dirname(__FILE__)));


    date_default_timezone_set('UTC');

    $username = $current_user->username;
    $userID = $current_user->id;
    $courses = getCourses($userID);
    $assignments = getAssignmentsOfCourses($courses);
    $studentAssignments = getStudentAssignments($userID, $assignments);
    $imageDir = './profiles/' . $username . '/img/';
    $action = filter_input(INPUT_POST , 'action');
    $courseID = filter_input(INPUT_POST, 'course_id');

        if (empty($action)) {

        }
        elseif($action == 'enroll_course') {
            if (isset($_POST['enrollment_key'])) {
                $key = filter_input(INPUT_POST, 'enrollment_key');
                registerCourse($key, date("Ymd"), $userID);
            }
        }
        elseif($action == 'edit_profile'){
            editProfile($current_user);
        }
        elseif($action == 'upload_assignment'){
            $assignmentID = filter_input(INPUT_POST, 'assignmentID');
            uploadAssignment($current_user, $assignmentID);
        }

        if(!empty($courseID)){
            getAssignments($courseID);
        }


    function registerCourse($key, $date, $userID){
            // Load SQL Helper class
            $commands = new SQLHelper();
            // Try to run the registerCourse SQL command
            try{
                $commands->addCourseUsingCourseKey($userID, $key, $date);
                http_response_code(200);
            }
            //Catch any errors in the process
            catch (Exception $e){
                http_response_code(500);
                echo "There was an error in your enrollment process.";
            }
    }


    function getCourses($userID){
        $commands = new SQLHelper();
        $studentCourses = $commands->getStudentCourses($userID);
        if($studentCourses != "Could not retrieve student's courses"):
            $courses = array();
            foreach($studentCourses as $studentCourse):
                $tempCourse = $commands->getCourse($studentCourse['CourseID']);
                if($tempCourse->closed == FALSE ){
                    array_push($courses, $tempCourse);
                }
            endforeach;
            return $courses;
        else:
            return NULL;
        endif;
        
        //$courses = $commands->getUserCourses($userID);
    }

    function getAssignmentsOfCourses($courses){
        $db = new SQLHelper();
        $assignments = array();
        foreach($courses as $course):
            $assignmentIDs = $db->getAssignments($course->courseID);
            foreach($assignmentIDs as $assignmentID):
                $tempAssignment = $db->getAssignment($assignmentID[0]);
                if($tempAssignment != "Could not retrieve assignment"):
                    array_push($assignments, $tempAssignment);
                endif;
            endforeach;
        endforeach;
        
        return $assignments;
    }
    
    function getStudentAssignments($userID, $assignments){
        $db = new SQLHelper();
        $studentAssignments = array();
        foreach($assignments as $assignment):
            $tempAssignment = $db->getStudentAssignment($userID, $assignment->id);
            if($tempAssignment != "Could not retrieve student assignment"):
                array_push($studentAssignments, $tempAssignment);
            endif;
        endforeach;
        return $studentAssignments;
    }
    
    function getAssignments($courseID){
        try {
            $commands = new SQLHelper();
            $assignmentIDs = $commands->getAssignments($courseID);
            $assignments = array();
            if($assignmentIDs != "Could not retreive assignment list"):
                foreach($assignmentIDs as $assignmentID):
                    $tempAssignment = $commands->getAssignment($assignmentID);
                    array_push($assignments, $tempAssignment);
                endforeach;
            endif;
            //$assignments = $commands->getUserAssignments($courseID);
            if (!empty($assignments)) {
                echo '<option value="">Select Assignment...</option>';
                foreach ($assignments as $assignment) {
                    echo '<option value="' . $assignment->id . '">' . $assignment->name . '</option>';
                }
            } else {
                echo '<option value="">No Assignments Found</option>';
            }
        }
        catch(Exception $e){
            echo 'Bad gateway';
        }
    }

    function getTeacher($teacherID){
        $db = new SQLHelper();
        $teacher = $db->getInstructor($teacherID);
        if($teacher != "Could not retrieve instructor"):
            return $teacher->firstName . " " . $teacher->lastName;
        else:
            return $teacher;
        endif;
    }
    
    function editProfile($user){
        $username = $user->username;
       $uploadDirectory = '/profiles/' . $username . '/img/';
       try {
           if (isset($_FILES['image_files']) && $_FILES['image_files']['name'] != '') {
               $aboutMe = filter_input(INPUT_POST, 'about');
               $resumeLink = filter_input(INPUT_POST, 'resume');
               $personalWebsite = filter_input(INPUT_POST, 'website');

               $maxsize = 1000000;
               $acceptable = array(
                   'image/jpeg',
                   'image/jpg',
                   'image/gif',
                   'image/png'
               );
               $errors = 0;

               $file = $_FILES['image_files'];
               $fileName = $file['name'];
               $fileSize = $file['size'];
               $fileType = $file['type'];
               $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
               $fileExtension = strtolower($fileExtension);
               $fileTmp = $file['tmp_name'];
               $fileDestination = $username . '_profile.' . $fileExtension;

               if (!in_array($fileType, $acceptable) && !empty($fileType)) {
                   echo 'Invalid file type. Please upload an image.';
                   $errors++;
                   exit();
               }

               if ($fileSize >= $maxsize || $fileSize === 0) {
                   echo 'File must be under 1MB';
                   $errors++;
                   exit();
               }

               if($errors === 0){
                   if(!is_dir(SITE_ROOT . $uploadDirectory)){
                       mkdir(SITE_ROOT . $uploadDirectory, 0755, true);
                   }
                   try{
                       move_uploaded_file($fileTmp, SITE_ROOT . $uploadDirectory . $fileDestination);
                       try{
                           $userID = $user->id;
                           $commands = new SQLHelper();
                           $commands->updateUser($userID, $aboutMe, $fileDestination, $resumeLink, $personalWebsite);
                       }
                       catch(Exception $e){
                           echo 'SQL Error';
                       }
                       echo 'Image has been uploaded succesfully. Profile has been updated.';
                   }
                   catch(Exception $e){
                       'Upload failed';
                   }
               }


           } else {
               echo 'Fail';
           }
       }
       catch(Exception $e){
           echo 'Failed';
       }
    }

    function uploadAssignment($user, $assignmentID){
        $username = $user->username;
        $userID = $user->id;
        $db = new SQLHelper();
        
        $assignment = $db->getAssignment($assignmentID);
        $assignmentName = $assignment->name;
        
        $uploadDirectory = "/cap/$username/workspace/$assignmentName";
        try {
            if(isset($_FILES['zip']) && $_FILES['zip']['name'] != ''){
                $zip = $_FILES['zip'];
                $return = unzip($zip['tmp_name'], $uploadDirectory, $_FILES['zip']);
                if($return == "Success"):
                    
                    $return = $db->updateStudentAssignment($userID, $assignmentID, $uploadDirectory, date("Ymd"));
                endif;
            }
        }
        catch (Exception $e){

        }
    }

    function unzip($location,$new_location, $zipFile){
        $fileDirectory = explode('.', $zipFile['name']);

        if(exec("unzip $location",$arr)){
            if(!is_dir(SITE_ROOT . $new_location)){
                mkdir(SITE_ROOT.$new_location, 0755, true);
            }
            try {
                $zip = new Zip();
                $zip->unzip_file($location, SITE_ROOT.$new_location);

                return 'Success';
            }
            catch (Exception $e){
                return 'Failed to unzip';
            }
        }
    }
?>