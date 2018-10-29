<?php
include 'includes/cis4270CommonIncludes.php';
include 'SQLHelper.php';
$post = hPost('action');
$error = "";
switch ($post) {
  case 'login':
  var_dump("we in the case");
  //check to see form tokens are equal
  if (csrf_token_is_valid()) {
    //validate via DB and get user type
    $username = hPOST('username');
    $pass = hPOST('password');
    $saltedPass = password_hash($pass, PASSWORD_BCRYPT);
    $results = getUserAuth($username);

    $role = $results['UserRole'];
    $pass = $results['Password'];


    if ($saltedPass == $pass) {
        if ($role == 1) {
          //redirect to student
          include 'dashboard.html';
        } else if ($role == 2) {
          //redirect to instructor
          include 'instructor-dashboard.html';
        } else if ($role == 3) {
          //redirect to admin
          include 'admin-dashboard.html';
        } else {
          include 'login.html';
          end_session();
        }
      }
    } else {

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
      include 'dashboard.html';
    }
  }
  break;

  default:
  end_session();
  //end session
  include 'login.html';
}


?>
