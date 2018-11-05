<?php
include_once 'includes/cis4270CommonIncludes.php';
include_once 'SQLHelper.php';
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
    $saltedPass = password_hash($password, PASSWORD_BCRYPT);
    $results = $sql->getUserAuth($username);
    if (!empty($results)) {
      $pass = $results['Password'];
      $role = $results['UserRole'];
    } else {
      $pass = "";
      $role = "";
    }
    //check if passwords are salted
    if ($password == $pass) {
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
          include 'instructor-dashboard.php';
        } else if ($role == 3) {
          //redirect to admin
          before_every_protected_page();
          include 'admin-dashboard.php';
        }
      }

    } else {
      $error = "Username and password combination does not exist";
    }
  }
  break;

  case 'register':
  //check to form tokens
  if (csrf_token_is_valid()) {
    //sanitize & validate user/password

    //make sure user is only
    $sUser = hPOST("username");
    if ( !preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $sUser) ) {
      $error += "Username should only be alphanumeric characters length greater than 5 and less than 31 <br/>";
    }


    $sPass = hPOST("password");
    if ( !preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $sPass) ) {
      $error += "Password should only be alphanumeric characters length greater than 5 and less than 31 <br/>";
    } else {
      //hash the password
      $sPass = password_hash($sPass, PASSWORD_BCRYPT);
    }

    //make sure both are only chars
    $sFirst = hPOST("firstname");
    $sLast = hPOST("lastname");
    if (!preg_match('/[^A-Za-z]/', $sFirst)) {
      $error += "First name should be only alphabet characters <br/>";
    }
    if (!preg_match('/[^A-Za-z]/', $sLast)) {
        $error += "Last name should be only alphabet characters <br/>";
    }

    //FTP
    if (empty($_POST['$ftpPass'])) {
      $ftpPass = hPOST("ftpPass");
      if (!preg_match('/[^A-Za-z]/', $ftpPass)) {
        $error += "FTP Password should be only alphanumeric characters <br/>";
      }
    }

    //SQL
    if (empty($_POST['$sqlPass'])) {
      $sqlPass = hPOST("sqlPass");
      if (!preg_match('/[^A-Za-z]/', $sqlPass)) {
        $error += "SQL Password should be only alphanumeric characters <br/>";
      }
    }

    //Profile
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
      include 'setup.php';
    } else {
      //store in db
      //take to dashboard
      include 'dashboard.php';
    }
  }
  break;

  case 'logout':
  if (is_logged_in() && is_session_valid()) {
  include 'login.php';
  after_successful_logout();
  }
  break;

  case 'change':
  if (is_session_valid()) {
        include 'changePass.php';
  } else {
    end_session();
    include 'login.php';
  }
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
      include 'instructor-dashboard.php';
    } else if ($role == 3) {
      //redirect to admin
      before_every_protected_page();
      include 'admin-dashboard.php';
    } else {
      include 'login.php';
      end_session();
    }

  } else {
    include 'login.php';
  }
}


?>
