<?php
require_once 'includes/cis4270CommonIncludes.php';
require_once 'SQLHelper.php';
require_once 'User.php';
if (!isset($_SESSION)) {
  session_start();
}
$sql = new SQLHelper;
$post = hPost('action');
$error = "";
switch ($post) {

case 'login':
  //check to see form tokens are equal
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
    if (($password == $pass) || password_verify($password, $pass)) {
      $_SESSION['user'] = $username;
      $_SESSION['role'] = $role;
      after_successful_login();
      if (is_session_valid()) {
        if ($role == 1) {
          //redirect to student
          before_every_protected_page();
          require 'dashboard.php';
        } else if ($role == 2) {
          //redirect to instructor
          before_every_protected_page();
          require 'instructor-dashboard.php';
        } else if ($role == 3) {
          //redirect to admin
          before_every_protected_page();
          require 'admin-dashboard.php';
        }
      }

    } else {
      $error = "Username and password combination does not exist";
      require 'login.php';
    }
  } else {
    $error = "Form token error";
    require 'login.php';
  }
  break;
case 'register':
  //check to form tokens
  if (csrf_token_is_valid()) {
  //sanitize & validate user/password
  var_dump($_POST);
    //make sure user is only
    $sUser = hPOST("username");
    if ( !preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $sUser) ) {
      $error += "Username should only be alphanumeric characters length greater than 5 and less than 31 <br/>";
    } else if (empty($sUser)){
      $error += "Username cannot be empty";
    }


    $sPass = hPOST("pass");
    if ( !preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $sPass) ) {
      $error += "Password should only be alphanumeric characters length greater than 5 and less than 31 <br/>";
    } else if (empty($sPass)){
      $error += "Password cannot be empty";
    } else {
      //hash the password
      $sPass = password_hash($sPass, PASSWORD_BCRYPT);
    }

    //make sure both are only chars
    $sFirst = hPOST("firstname");
    $sLast = hPOST("lastname");
    if (!preg_match('/[^A-Za-z]/', $sFirst)) {
      $error += "First name should be only alphabet characters <br/>";
    } else if (empty($sFirst)){
      $error += "First name cannot be empty";
    }
    if (!preg_match('/[^A-Za-z]/', $sLast)) {
        $error += "Last name should be only alphabet characters <br/>";
    } else if (empty($sLast)){
      $error += "Last name cannot be empty";
    }

    //email
    $sEmail = hPOST("email");
    if (!filter_var($sEmail, FILTER_VALIDATE_EMAIL) ) {
      $error += "Email is invalid <br/>";
    } else if (empty($sEmail)){
      $error += "Email cannot be empty";
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

    //validate
    if (!empty($error)) {
      require 'register.php';
    } else {
      //store in db
      $user = new User($sUser, $sPass, $sFirst, $sLast, "student", $sAbout, $sEmail, NULL, $sResume, $sWebsite, 1, 0, date("Y-m-d"), NULL);
      $result = $sql->addUser($user);
      //take to dashboard
    }
  }
  break;
case 'instructorDash':
  if (is_session_valid()) {
    require 'instructor-dashboard.php';
  } else {
    if (isset($_SESSION)) {
      end_session();
    }
    require 'login.php';
   }
  break;
case 'registerPage':
  require 'register.php';
  break;
case 'logout':
  after_successful_logout();
  require 'login.php';
  header("Refresh:0");
  break;
case 'changePage':
  if (is_session_valid()) {
        require 'changePass.php';
  } else {
    if (isset($_SESSION)) {
      end_session();
    }
    require 'login.php';
  }
  break;
//change password
case 'change':
  if (is_logged_in() && is_session_valid()) {
    $user = $_SESSION["user"];
    $pass = hPOST('currentPass');
    //$pass = password_hash($pass, PASSWORD_BCRYPT);
    $results = $sql->getUserAuth($user  );
    if ($results['Password'] == $pass) {
      $newPass = hPOST('newPass');
      $comfPass = hPOST('comfPass');
      if ($newPass == $comfPass) {
        if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $newPass) ) {
          $error += "Password should only be alphanumeric characters length greater than 5 and less than 31 <br/>";
          require 'changePass.php';
        } else {
          $sql->changePassword($user, $pass, $newPass);
          after_successful_logout();
          $error = "Password successfully changed. Please log back in again.";
          require 'login.php';

        }
      } else {
        $error = "Passwords do not match.";
        require 'changePass.php';
      }
    } else {
      $error = "Invalid Password";
      require 'changePass.php';
    }
  } else {
    require 'login.php';
    after_successful_logout();
  }
  break;
case 'project':
  //get project variables
  $course = filter_input(INPUT_POST, 'Course');
  $assignment = filter_input(INPUT_POST, 'Assignment');

   //sql statement
  include 'project-view.php';
  break;

default:
  if (is_logged_in()) {
    $role = $_SESSION['role'];
    if ($role == 1) {
      //redirect to student
      before_every_protected_page();
      require 'dashboard.php';
    } else if ($role == 2) {
      //redirect to instructor
      before_every_protected_page();
      require 'instructor-dashboard.php';
    } else if ($role == 3) {
      //redirect to admin
      before_every_protected_page();
      require 'admin-dashboard.php';
    } else {
      require 'login.php';
      if (isset($_SESSION)) {
        end_session();
      }

    }

  } else {
    require 'login.php';
  }
}


?>
