<?php
include 'includes/cis4270CommonIncludes.php';
include 'SQLHelper.php';
$post = hPost('action');
$error = "";
switch ($post) {
  case 'login':
  //check to see form tokens are equal
  if (csrf_token_is_valid()) {
    //validate via DB and get user type
    if () {
      //redirect to student
      include 'dashboard.html';
    } else if () {
      //redirect to instructor
      include 'instructor-dashboard.html';
    } else if () {
      //redirect to admin
      include 'admin-dashboard.html';
    } else {
      include 'login.html';
      end_session();
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
      $error += "Username should only be alphanumeric characters length greater than 5 and less than 31";
    }


    $sPass = hPOST("password");
    if ( !preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $sPass) ) {
      $error += "Password should only be alphanumeric characters length greater than 5 and less than 31";
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


    //sanitize Profile
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
    //store in db
  }

  //take to dashboard
  include 'dashboard.html';
  break;

  default:
  end_session();
  //end session
  include 'login.html';
}


?>
