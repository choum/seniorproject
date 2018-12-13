<?php
    /*
     * Created By: Nareg Khodanian with assistance from Justin Crest
     * Description: This file serves as the controller segment of the student dashboard.
     * This file serves as the place for file uploads to occur. This means any zip uploads, 
     * images for the profile carousel, and the image for the users profile picture.
     * Along with this, this file is in charge of the course enrollment and gathering the various
     * bits of information to be displayed on the view.
     */
    require_once "SQLHelper.php";
    require_once "zip/Zip.php";
    require_once "includes/validationFunctions.php";
    define('SITE_ROOT', realpath(dirname(__FILE__)));


    date_default_timezone_set('UTC');
    if (!isset($_SESSION))
    {
        session_name('caps');
        session_start();
    }
    $username = $_SESSION['user'];
    $userID = getUserID($username);
    $courses = getCourses($userID);
    $action = filter_input(INPUT_POST, 'action');
    $courseID = filter_input(INPUT_POST, 'course_id');

    $assignments = getAssignmentsOfCourses($courses);
    $studentAssignments = getStudentAssignments($userID, $assignments);
    $imageDir = './profiles/' . $username . '/img/';
    $assignmentDir = "/cap/student/$username/workspace/";

    if (empty($action))
    {

    }
    elseif ($action == 'enroll_course')
    {
        if (isset($_POST['enrollment_key']))
        {
            $key = filter_input(INPUT_POST, 'enrollment_key');
            registerCourse($key, date("Ymd"), $username);
        }
    }
    elseif ($action == 'edit_profile')
    {
        editProfile($username);
    }
    elseif ($action == 'upload_assignment')
    {
        if (isset($_POST['user-courses']) && isset($_POST['user-assignments']))
        {
            uploadAssignment($username, $userID, $_POST['user-courses'], $_POST['user-assignments']);
        }
    }

    if (!empty($courseID))
    {
        getAssignments($courseID);
    }
    /*
     * Purpose of this function is to call the registerCourse function in the SQLHelper file.
     * based on the given information. More information about said function can be viewed
     * in the SQLHelper file.
     */
    function registerCourse($key, $date, $username)
    {
        // Load SQL Helper class
        $commands = new SQLHelper();
        // Try to run the registerCourse SQL command
        try
        {
            $commands->registerCourse($username, $key, $date);
            http_response_code(200);
        }
        //Catch any errors in the process
        catch (Exception $e)
        {
            http_response_code(500);
            echo "There was an error in your enrollment process.";
        }
    }
    /*
     * Purpose of this function is to call the getUser function in the SQLHelper file based on given info.
     * For more information about getUser, please visit the SQLHelper file.
     */
    function getUser($username)
    {
        $commands = new SQLHelper();
        try
        {
            $user = $commands->getUser($username);
            return $user;
        } catch (Exception $e)
        {
            echo "Invalid user";
        }
    }
    /*
     * Purpose of this function is to call the getUserID function in the SQLHelper file based on given info.
     * For more information about getUserID, please visit the SQLHelper file.
     */
    function getUserID($username)
    {
        $commands = new SQLHelper();
        try
        {
            $userID = $commands->getUserID($username);
            return $userID;
        } catch (Exception $e)
        {
            return 'Error';
        }
    }
    /*
     * Purpose of this function is to call the getUserCourses function in the SQLHelper file based on given info.
     * For more information about getUserCourses, please visit the SQLHelper file.
     */
    function getCourses($userID)
    {
        $commands = new SQLHelper();
        $courses = $commands->getUserCourses($userID);
        return $courses;
    }
    /*
     * Purpose of this function is to call the getTeacher function in the SQLHelper file based on given info.
     * For more information about getTeacher, please visit the SQLHelper file.
     */
    function getTeacher($teacherID)
    {
        $db = new SQLHelper();
        $teacher = $db->getUserByID($teacherID);
        if ($teacher != "Could not retrieve instructor"):
            return $teacher->firstName . " " . $teacher->lastName;
        else:
            return $teacher;
        endif;
    }
    /*
     * Purpose of this function is to call the getAssignments function for every course that a student is enrolled in.
     * This information is then used to get info on every assignment under each course given.
     * Each assignment with all its information is then stored in arraylist for use in the student-dashboard file.
     * For more info on getAssignments and getAssignment, please visit the SQLHelper file.
     */
    function getAssignmentsOfCourses($courses)
    {
        $db = new SQLHelper();
        $assignments = array();
        foreach ($courses as $course):
            $assignmentIDs = $db->getAssignments($course->courseID);
            foreach ($assignmentIDs as $assignmentID):
                $tempAssignment = $db->getAssignment($assignmentID[0]);
                if ($tempAssignment != "Could not retrieve assignment"):
                    array_push($assignments, $tempAssignment);
                endif;
            endforeach;
        endforeach;
        return $assignments;
    }
    /*
     * Purpose of this function is to go through every assignment for every course that a student in enrolled in
     * and check if they have an assignment submission using the getStudentAssignment function of SQLHelper.
     * Every submission is then put into an arraylist for later use in the view
     * For more info on getStudentAssignment, please visit the SQLHelper file.
     */
    function getStudentAssignments($userID, $assignments)
    {
        $db = new SQLHelper();
        $studentAssignments = array();
        foreach ($assignments as $assignment):
            $tempAssignment = $db->getStudentAssignment($userID, $assignment->id);
            if ($tempAssignment != "Could not retrieve student assignment"):
                array_push($studentAssignments, $tempAssignment);
            endif;
        endforeach;
        return $studentAssignments;
    }
    /*
     * Purpose of this function is to get all of the assignments for a selected course that the student is enrolled in.
     * This information is then placed in HTML code, which is used and displayed in the upload assignment module.
     * It works as a way to allow a dynamically displaying assignment list drop down without the need to reload an entire page.
     * For more info on the getUserAssignments function, please visit the SQLHelper file.
     */
    function getAssignments($courseID)
    {
        try
        {
            $commands = new SQLHelper();
            $assignments = $commands->getUserAssignments($courseID);
            if (!empty($assignments))
            {
                echo '<option value="">Select Assignment...</option>';
                foreach ($assignments as $assignment)
                {
                    echo '<option value="' . $assignment['AssignmentID'] . '">' . $assignment['AssignmentName'] . '</option>';
                }
            }
            else
            {
                echo '<option value="">No Assignments Found</option>';
            }
        } catch (Exception $e)
        {
            echo 'Bad gateway';
        }
    }
    /*
     * Purpose of this function is to validate all changes made to the edit profile section
     * of the student dashboard. This involves the creation of a User class object
     * and validating any image chosen for the users profile picture.
     * All profile edits are gathered and sent to the updateUser function of SQLHelper once validated.
     * Files uploaded are under more scrutiny, which includes having an acceptable file type and size.
     * Once validated, the file is permanently uploaded to the file directory under the profiles folder.
     * If any files already exist in the directory specific to the student, they are deleted. 
     * For more info on the updateUser function, please visit the SQLHelper file.
     */
    function editProfile($username)
    {
        $uploadDirectory = '/profiles/' . $username . '/img/';
        try
        {
          $commands = new SQLHelper();
          $currentUser = $commands->getUser($username);
          $userID = getUserID($username);
          if (isset($_POST['about'])) {
            $aboutMe = hPOST('about');
          } else if (!empty($currentUser->bio)){
            $aboutMe = $currentUser->bio;
          } else {
            $aboutMe = null;
          }
          if (isset($_POST['resume'])) {
            $resumeLink = hPOST('resume');
          } else if (!empty($currentUser->linkedin)){
            $resumeLink = $currentUser->linkedin;
          } else {
            $resumeLink = null;
          }
          if (isset($_POST['website'])) {
            $personalWebsite = hPOST('website');
          } else if (!empty($currentUser->website)){
            $personalWebsite = $currentUser->website;
          } else {
            $personalWebsite = null;
          }

            if (isset($_FILES['image_files']) && $_FILES['image_files']['name'] != '')
            {
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

                if (!in_array($fileType, $acceptable) && !empty($fileType))
                {
                    echo 'Invalid file type. Please upload an image.';
                    $errors++;
                    exit();
                }

                if ($fileSize >= $maxsize || $fileSize === 0)
                {
                    echo 'File must be under 1MB';
                    $errors++;
                    exit();
                }

                if ($errors === 0)
                {
                    if (!is_dir(SITE_ROOT . $uploadDirectory))
                    {
                        mkdir(SITE_ROOT . $uploadDirectory, 0755, true);
                    }
                    try
                    {
                        deleteDirectoryContent(SITE_ROOT . $uploadDirectory);
                        move_uploaded_file($fileTmp, SITE_ROOT . $uploadDirectory . $fileDestination);
                        try
                        {
                          $commands->updateUser($userID, $aboutMe, $fileDestination, $resumeLink, $personalWebsite);
                        } catch (Exception $e)
                        {
                            echo 'SQL Error occured, could not update user info.';
                        }
                        echo 'Image has been uploaded successfully. Profile has been updated.';
                    } catch (Exception $e)
                    {
                        //echo 'Upload failed';
                    }
                }
            }
            else
            {
              if (!empty($currentUser->imageLink)){
                $fileDestination = $currentUser->imageLink;
              } else {
                $fileDestination = null;
              }
              try {
                $commands->updateUser($userID, $aboutMe, $fileDestination, $resumeLink, $personalWebsite);
              } catch (Exception $e)
              {
                  echo 'SQL Error occured, could not update user info.';
              }
              echo 'Profile has been updated.';
            }
        } catch (Exception $e)
        {
            echo "Could not update profile.";
        }
    }
    /*
     * Purpose of this function is to upload files for a specific assignment of a specific course
     * Course and assignment information is used to decide the folder directory, and makes use of the
     * getCourse and getAssignment functions of SQLHelper. Along with this it uses the unzip function of this file
     * to allow for unzipping, storage, and later viewing of files uploaded.
     * Along with this process, this function also takes care of any files uploaded for the carousel display of the profile page.
     * If the student has chosen the upload project to be featured or shown as group work, that is added along with other information
     * and sent to the addStudentAssignment function of SQLHelper. For uploading seperate files, the uploadImages function is used.
     * the changeFeaturedAssignment function of SQLHelper is also used to ensure no more than one featured assignment is selected at a time
     * per student.
     * For more info on the getCourse, getAssignment, addStudentAssignment, and changeFeaturedAssignment functions, 
     * please visit the SQLHelper file. All other function calls are located in this file.
     */
    function uploadAssignment($username, $userID, $courseID, $assignmentID)
    {

        try
        {
            if (isset($_FILES['zip']) && $_FILES['zip']['name'] != '')
            {
                $zip = $_FILES['zip'];
                $commands = new SQLHelper();
                try
                {
                    $course = $commands->getCourse($courseID);
                    $courseSection = $course->courseNumber;
                } catch (Exception $e)
                {
                    echo 'Error finding course';
                    exit();
                }

                try
                {
                    $assignment = $commands->getAssignment($assignmentID);
                    $assignmentName = str_replace(' ', '_', $assignment->name);
                } catch (Exception $e)
                {
                    echo 'Error finding assignment';
                    exit();
                }
                $uploadDirectory = "/cap/student/" . $username . "/workspace/CIS" . $courseSection . "_" . $assignmentName;
                $path = "CIS" . $courseSection . "_" . $assignmentName;
                unzip($zip['tmp_name'], $uploadDirectory, $_FILES['zip']);

                try
                {
                    //Process involved with uploading image screenshots.
                    $file = $_FILES['filesToUpload'];

                    $imageLink = NULL;
                    if (isset($file) && $file['name'] != '' && $file['name'][0] != "")
                    {
                        $imageDirectory = '/cap/student/' . $username . '/img/';
                        $counter = 0;
                        $images = array();
                        if(is_array($file['name'])){
                            $counter = sizeof($file['name']);
                        }
                        else{
                            $counter = 1;
                        }

                        for($i = 0; $i < $counter; $i++){
                            $tempImage = uploadImages($imageDirectory, $file, $i, $assignmentID, $username);
                            array_push($images, $tempImage);
                        }

                        if(array_search("Upload failed", $images) == FALSE){
                            foreach($images as $image){
                                if($imageLink == ""):
                                    $imageLink = "$image";
                                else:
                                    $imageLink .= ",$image";
                                endif;
                            }
                        }
                        else{
                            echo "Error uploading screenshots";
                            exit();
                        }
                    }

                    //End process involving screenshots
                    $featured = filter_input(INPUT_POST, 'featured');
                    $group = filter_input(INPUT_POST, 'group');
                    if($group == NULL){ $group = 0; }
                    if($featured == NULL) { $featured = 0; }

                    $return = $commands->addStudentAssignment($userID, $assignmentID, $path, date("Ymd"), $imageLink, $featured, $group);

                    if ($featured == TRUE AND $return == "Student assignment created"):
                        $db = new SQLHelper();
                        $db->changeFeaturedAssignment($userID, $assignmentID);
                    elseif($return == "Student assignment created"):
                        echo $return . "<br/>";
                    else:
                        $db = new SQLHelper();
                        $readd = $db->readdStudentAssignment($userID, $assignmentID, $path, date("Ymd"), $imageLink, $featured, $group);
                        if($readd != "Student assignment created"):
                            throw new Exception;
                        else:
                            echo $readd . "<br/>";
                        endif;
                    endif;
                } catch (Exception $e)
                {
//                    echo 'Cannot add assignment to database.';
//                    exit();
                }

                echo '<a href="' . $uploadDirectory . '" target="_blank">View Assignment Here</a>';
            }
        } catch (Exception $e)
        {

        }
    }
    /*
     * Purpose of this function is to unzip the zip file given by the user on project upload
     * This function takes care of directory creation to store the project files, as well as 
     * placing all indiviual project files and subfolder in the newly created directory, 
     * which in turn changes the name of the project itself. This is done to ensure uniformity
     * and allowance for reupload to not rely on users keeping the same zip folder name.
     * This function calls the deleteDirectoryContent, recurse_copy, and deleteDirectory functions of this file.
     */
    function unzip($location, $new_location, $zipFile)
    {
        $fileDirectory = explode('.', $zipFile['name']);
        if (!file_exists(SITE_ROOT . $new_location)) {
            mkdir(SITE_ROOT . $new_location, 0755, true);
        }
        try
        {

            $dst = SITE_ROOT . $new_location . '/';
            deleteDirectoryContent($dst);
            $zip = new Zip();
            $zip->unzip_file($location, SITE_ROOT . $new_location);
            $content = scandir(SITE_ROOT.$new_location);
            $directoryName = $content[2];
            $src = SITE_ROOT . $new_location . '/' . $directoryName . '/';
            recurse_copy($src, $dst);
            deleteDirectory($src);
        } catch (Exception $e)
        {
            echo 'File upload has failed.';
            exit();
        }
    }
    /*
     * Purpose of this function is unpack all files inside of a zip and move them up one level.
     * This is done to ensure the project files and folders are not hidden behind unecessary layers,
     * and it is called recursively until hitting a certain point.
     */
    function recurse_copy($src, $dst)
    {
        try{$dir = opendir($src);}
        catch(Exception $e){echo 'error';}
        @mkdir($dst);
        while (false !== ( $file = readdir($dir)))
        {
            if (( $file != '.' ) && ( $file != '..' ))
            {
                if (is_dir($src . '/' . $file))
                {
                    recurse_copy($src . '/' . $file, $dst . '/' . $file);
                }
                else
                {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
    /*
     * Purpose of this function is to recursively call the delete directory function, which is
     * done to enure no linger directory exists from the process of unzipping.
     */
    function deleteDirectory($dirPath)
    {
        if (is_dir($dirPath))
        {
            $objects = scandir($dirPath);
            foreach ($objects as $object)
            {
                if ($object != "." && $object != "..")
                {
                    if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir")
                    {
                        deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
                    }
                    else
                    {
                        unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dirPath);
        }
    }
    /*
     * Purpose of this function is to delete any directory contents prior to the deletion of a directoy.
     * This is mainly done to ensure reuploads are performed without conflict of prexisting files.
     */
    function deleteDirectoryContent($dirPath)
    {
        if (is_dir($dirPath))
        {
            $objects = scandir($dirPath);
            foreach ($objects as $object)
            {
                if ($object != "." && $object != "..")
                {
                    if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir")
                    {
                        deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
                    }
                    else
                    {
                        unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
            reset($objects);
        }
    }
    /*
     * Purpose of this function is for uploading images meant for the iamge carousel of the profile page.
     * After being validated, the image in question is uploaded, with a new directory being created for the
     * files to be stored if one does not already exist.
     * If multiple files are selected for upload, this function is called for each one individually.
     */
    function uploadImages($imageDirectory, $file, $counter, $assignmentID, $username)
    {
        $maxsize = 10000000;
        $acceptable = array(
            'image/jpeg',
            'image/jpg',
            'image/gif',
            'image/png'
        );
        $errors = 0;
        if(is_array($file['name'])){
            $fileName = $file['name'][$counter];
            $fileSize = $file['size'][$counter];
            $fileType = $file['type'][$counter];
            $fileTmp = $file['tmp_name'][$counter];
        }
        else{
            $fileName = $file['name'];
            $fileSize = $file['size'];
            $fileType = $file['type'];
            $fileTmp = $file['tmp_name'];
        }
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileExtensionStr = strtolower($fileExtension);

        $fileDestination = $assignmentID . "_$counter." . $fileExtensionStr;
        if (!in_array($fileType, $acceptable) && !empty($fileType))
        {
            echo 'Invalid file type. Please upload an image.';
            $errors++;
            exit();
        }

        if ($fileSize >= $maxsize || $fileSize === 0)
        {
            echo 'File must be under 1MB';
            $errors++;
            exit();
        }

        if ($errors === 0)
        {
            if (!is_dir(SITE_ROOT . '/cap/student/' . $username . '/img')) {
              mkdir(SITE_ROOT . '/cap/student/' . $username . '/img', 0755, true);
            }
            if (!is_dir(SITE_ROOT . $imageDirectory))
            {
                mkdir(SITE_ROOT . $imageDirectory, 0755, true);
            }
            if(!is_dir(SITE_ROOT . $imageDirectory . $assignmentID))
            {
                mkdir(SITE_ROOT . $imageDirectory . $assignmentID, 0755, true);
            }
            if(!is_dir(SITE_ROOT . $imageDirectory . $assignmentID . "/"))
            {
                mkdir(SITE_ROOT . $imageDirectory . $assignmentID . "/", 0755, true);
            }
            try
            {
                $uploadBool = move_uploaded_file($fileTmp, SITE_ROOT . $imageDirectory . $assignmentID . "/". $fileDestination);

                if($uploadBool == TRUE)
                {
                    return $fileDestination;
                }
                else
                {
                    throw new Exception;
                }
            } catch (Exception $e)
            {
                return 'Upload failed';
            }
        }
    }

?>
