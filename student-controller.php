<?php
    require_once "SQLHelper.php";
    require_once "zip/Zip.php";
    define ('SITE_ROOT', realpath(dirname(__FILE__)));


date_default_timezone_set('UTC');

    $username = 'username';
    $userID = getUserID($username);
    $courses = getCourses($userID);
    $action = filter_input(INPUT_POST , 'action');
    $courseID = filter_input(INPUT_POST, 'course_id');

        if (empty($action)) {

        }
        elseif($action == 'enroll_course') {
            if (isset($_POST['enrollment_key'])) {
                $key = filter_input(INPUT_POST, 'enrollment_key');
                registerCourse($key, date("Ymd"), $username);
            }
        }
        elseif($action == 'edit_profile'){
            editProfile($username);
        }
        elseif($action == 'upload_assignment'){
            uploadAssignment($username);
        }

        if(!empty($courseID)){
            getAssignments($courseID);
        }


    function registerCourse($key, $date, $username){
            // Load SQL Helper class
            $commands = new SQLHelper();
            // Try to run the registerCourse SQL command
            try{
                $commands->registerCourse($username, $key, $date);
                http_response_code(200);
            }
            //Catch any errors in the process
            catch (Exception $e){
                http_response_code(500);
                echo "There was an error in your enrollment process.";
            }
    }

    function getUser($username){
        $commands = new SQLHelper();
        try{
            $user = $commands->getUser($username);
            return $user;
        }
        catch (Exception $e){
            echo "Invalid user";
        }
    }

    function getUserID($username){
        $commands = new SQLHelper();
        try{
            $userID = $commands->getUserID($username);
            return $userID;
        }
        catch (Exception $e){
            return 'Error';
        }
    }

    function getCourses($userID){
        $commands = new SQLHelper();
        $courses = $commands->getUserCourses($userID);
        return $courses;
    }

    function getAssignments($courseID){
        try {
            $commands = new SQLHelper();
            $assignments = $commands->getUserAssignments($courseID);
            if (!empty($assignments)) {
                echo '<option value="">Select Assignment...</option>';
                foreach ($assignments as $assignment) {
                    echo '<option value="' . $assignment['AssignmentID'] . '">' . $assignment['AssignmentName'] . '</option>';
                }
            } else {
                echo '<option value="">No Assignments Found</option>';
            }
        }
        catch(Exception $e){
            echo 'Bad gateway';
        }
    }

    function editProfile($username){
       $uploadDirectory = '/profiles/' . $username . '/img/';
       try {
           if (isset($_FILES['image_files']) && $_FILES['image_files']['name'] != '') {
               $aboutMe = $_POST['about'];
               $resumeLink = $_POST['resume'];
               $personalWebsite = $_POST['website'];

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
                           $commands = new SQLHelper();
                           $commands->updateUser($username, $aboutMe, $fileDestination, $resumeLink, $personalWebsite);
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

    function uploadAssignment($username){
        $uploadDirectory = "/cap/$username/workspace/CIS4290_Assignment1";
        try {
            if(isset($_FILES['zip']) && $_FILES['zip']['name'] != ''){
                $zip = $_FILES['zip'];
                unzip($zip['tmp_name'], $uploadDirectory, $_FILES['zip']);
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

                echo 'Success';
            }
            catch (Exception $e){
                echo 'Failed to unzip';
            }
        }
    }
?>