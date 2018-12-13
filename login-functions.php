<?php
    /*
     * Created By: Heather Tran
     * Description: This file  has all functions tied to logging in, registration, and contains some redirection.
     */
//functions for alerting, logging out, redirecting to dash
function logout() {
    after_successful_logout();
    header("refresh: 2;");
}
function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}
/*
 * Depending on the role of the user that is logged in, they are allowed access to certain dashboards
 * For example, role 3 can access the admin dashboard, whereas role 2 can access the instructor dashboard.
 * If a user is not logged in, then they are shown the login page.
 */
function redirect() {
  try {
    if (is_logged_in()) {
      $role = $_SESSION['role'];
      if ($role == 1) {
        //redirect to student
        before_every_protected_page();
        require 'student-dashboard.php';
      } else if ($role == 2) {
        //redirect to instructor
        before_every_protected_page();
        require 'instructor-dashboard.php';
      } else if ($role == 3) {
        //redirect to admin
        before_every_protected_page();
        require 'admin-dashboard.php';
      } else if ($role == 4) {
        $post = hPost('action');
        before_every_protected_page();
        if ($post === 'add_project' || $post === 'project' || $post === 'course_key') {
          require 'instructor-dashboard.php';
        } else {
          require 'admin-dashboard.php';
        }
      }
      else {
        require 'login.php';
        if (isset($_SESSION)) {
          end_session();
        }

      }

    } else {
      require 'login.php';
    }
  } catch (Exception $e) {
    error();
  }
}
/*
 * This function is called whenever a user attempts to log in. The account is authorized as existing,
 * the password is verified as matching what is in the database, and the last login date is updated.
 * This function makes use of the getUserAuth and updateLastLoggedIn functions of the SQLHelper file
 * please visit that for info on those functions.
 */
function login() {
  try {
    $sql = new SQLHelper;
    if (csrf_token_is_valid()) {
      //validate via DB and get user type
      $username = hPOST('username');
      $password = hPOST('password');
      $results = $sql->getUserAuth($username);
      if (!empty($results)) {
        $pass = $results['Password'];
        $role = $results['UserRole'];
      } else {
        $pass = "";
        $role = "";
      }
      //check if passwords are salted
      if (password_verify($password, $pass)) {
        $_SESSION['user'] = $username;
        $_SESSION['role'] = $role;
        $loggedIn = date("Y-m-d");
        $sql->updateLastLoggedIn($username, $loggedIn);
        after_successful_login();
        redirect();

      } else {
        $error = "Username and password combination does not exist";
        require 'login.php';
      }
    } else {
      $error = "Form token error";
      require 'login.php';
    }
  } catch (Exception $e) {
    error();
  }

}
/*
 * This function is called after completing the first step of account registration, and serves
 * as a way to validate the information entered by the user. Once all information, including the 
 * validity of the file uploaded if one was given, the image file is uploaded, the user account
 * is added to the database, and the user is redirected to the setup page which holds the second
 * portion of their registration.
 */
function register() {
  //check to form tokens
  try {
    if (csrf_token_is_valid()) {
      //make sure user is only
      $sUser = hPOST("username");
      if ( !preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $sUser) ) {
        $error .= "Username should only be alphanumeric characters length greater than 5 and less than 31 <br/>";
      } else if (empty($sUser)){
        $error .= "Username cannot be empty";
      }


      $sPass = hPOST("password");
      if ( !preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $sPass)) {
        $error .= "Password should only be alphanumeric characters length greater than 5 and less than 31 <br/>";
      } else if (empty($sPass)){
        $error .= "Password cannot be empty";
      } else {
        //hash the password
        $sPass = password_hash($sPass, PASSWORD_BCRYPT);
      }

      //make sure both are only chars
      $sFirst = hPOST("firstname");
      $sLast = hPOST("lastname");

      //email
      $sEmail = hPOST("email");
      if (!filter_var($sEmail, FILTER_VALIDATE_EMAIL) ) {
        $error .= "Email is invalid <br/>";
      } else if (empty($sEmail)){
        $error .= "Email cannot be empty";
      }
      //Profile
      //ADD IMAGE CHECK
      if (empty($_POST['resume'])) {
        $sResume = NULL;
      } else {
        $sResume = hPOST("resume");
      }

      if (empty($_POST['website'])) {
        $sWebsite = null;
      } else {
        $sWebsite = hPOST("website");
      }

      if (empty($_POST['about'])) {
        $sAbout = null;
      } else {
        $sAbout = hPOST("about");
      }
      //check for duplicate
      $sql = new SQLHelper;
      $bool = $sql->checkForDuplicate($sUser);
      if ($bool == false) {
        $error = "This Username is already taken. Please choose another username.";
        require 'register.php';
      } else {
        define('SITE_ROOT', realpath(dirname(__FILE__)));
        $file = $_FILES['file'];
        $uploadDirectory = '/profiles/' . $sUser . '/img/';

        try
        {
            if (isset($file) && $file['name'] != '')
            {
                $maxsize = 10000000;
                $acceptable = array(
                    'image/jpeg',
                    'image/jpg',
                    'image/gif',
                    'image/png'
                );
                $errors = 0;
                $fileName = $file['name'];
                $fileSize = $file['size'];
                $fileType = $file['type'];
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $fileExtension = strtolower($fileExtension);
                $fileTmp = $file['tmp_name'];
                $fileDestination = $sUser . '_profile.' . $fileExtension;
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
                    if (!is_dir(SITE_ROOT . '/profiles/' . $sUser)) {
                      mkdir(SITE_ROOT . '/profiles/' . $sUser, 0755, true);
                    }
                    if (!is_dir(SITE_ROOT . $uploadDirectory))
                    {
                        mkdir(SITE_ROOT . $uploadDirectory, 0755, true);
                    }
                    try
                    {
                        $uploadBool = move_uploaded_file($fileTmp, SITE_ROOT . $uploadDirectory . $fileDestination);
                    } catch (Exception $e)
                    {
                        echo 'Upload failed';
                    }
                }
            }
            else
            {
                $uploadBool = false;
                $file_name = null;
                $file = null;
            }
        } catch (Exception $e)
        {
            echo 'Failed';
        }
      }

      if (!empty($error)) {
        require 'register.php';
      } else if ($uploadBool || empty($file)) {
        if (empty($file)) {
          $fileName = null;
        } else {
          $fileName = $fileDestination;
        }
        $user = new User($sUser, $sPass, $sFirst, $sLast, 'student', $sAbout, $sEmail, $fileName, $sResume, $sWebsite,1,0, date("Y/m/d"), date("Y/m/d"));
        $results = $sql->addUser($user);
        $_SESSION['user'] = $sUser;


        require 'setup.php';
      } else {
        $error = "Error occurred please try again";
        require 'register.php';
      }
    }
  } catch (Exception $e) {
    error();
  }
}
/*
 * The second part of account registration, this portion only needs to be validate that the password
 * entered is valid, as the username for phpmyadmin is the same username decided in the first part of registration.
 * Once that is successful, the database user is created and the user is redirected to the student dashboard.
 * Again, only students need to register and make phpmyadmin users.
 */
function setup() {
  try {
    $createDB = new CreateDB;
    $user = $_SESSION['user'];
    $error = "";
    if (csrf_token_is_valid()) {
      $sqlPass = hPOST("sqlPass");
      if ( !preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $sqlPass) ) {
        $error .= "SQL Password should only be alphanumeric characters length greater than 5 and less than 31 <br/>";
      } else if (empty($sqlPass)){
        $error .= "SQL Password cannot be empty<br/>";
      } else if ($sqlPass == $user) {
        $error .= "SQL Password cannot be your username<br/>";
      }
      //$ftpPass = hPOST("ftpPass");
      //if ( !preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $ftpPass) ) {
        //$error .= "FTP Password should only be alphanumeric characters length greater than 5 and less than 31 <br/>";
      //} else if (empty($ftpPass)){
      //  $error .= "FTP Password cannot be empty<br/>";
      //} else if ($ftpPass == $user) {
      //  $error .= "FTP Password cannot be your username<br/>";
      //}
      if (!empty($error)) {
        require 'setup.php';
      } else {
        $createDB->createDBUser($user, $sqlPass);
        $_SESSION['role'] = 1;
        after_successful_login();
        require 'student-dashboard.php';
      }
    } else {
      error();
    }
  } catch (Exception $e) {
    error();
  }
}
/*
 * Purpose of this function is to allow users to change their password. This can be done by all user
 * types, and has the same restrictions as password creation when registering ones account.
 * It makes use of the getUserAuth and changePassword function of SQLHelper, please visit that file
 * for more info.
 */
function change() {
  try {
    $sql = new SQLHelper;
    if (is_logged_in() && is_session_valid()) {
      $user = $_SESSION["user"];
      $pass = hPOST('currentPass');
      $results = $sql->getUserAuth($user);
      $error = "";
      if (password_verify($pass, $results['Password'])) {
        $newPass = hPOST('newPass');
        $comfPass = hPOST('comfPass');
        if ($newPass == $comfPass) {
          if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $newPass) ) {
            $error .= "Password should only be alphanumeric characters length greater than 5 and less than 31 <br/>";
            require 'changePass.php';
          } else {
            $newPass = password_hash($newPass, PASSWORD_BCRYPT);
            $sql->changePassword($user, $results['Password'], $newPass);
            alert("Password successfully changed. Please log back in again.");
            logout();

          }
        } else {
          $error = "Passwords do not match.";
          require 'changePass.php';
        }
      } else if (empty($pass)) {
        $error ="You cannot have an empty password";
        require 'changePass.php';
      } else {
        $error = "Invalid Password";
        require 'changePass.php';
      }
    } else {
      logout();
    }
  } catch (Exception $e) {
    error();
  }
}

function error() {
  echo 'Error has occurred.';
  logout();
}

 ?>
