<?php
include 'includes/cis4270CommonIncludes.php';
$post = hPost('action');

switch ($post) {
  case 'login':
  //check to see form tokens are equal
  if (csrf_token_is_valid()) {
    //validate via DB and get user type
    if () {
      //redirect to student
    } else if () {
      //redirect to instructor
    } else if () {
      //redirect to admin
    } else {
      include 'login.html';
      end_session();
    }
  }
  break;

  case 'register':
  //check to form tokens
  if (csrf_token_is_valid()) {
    //sanitize user/password
    $sUser = hPOST("username");
    $sPass = hPOST("password");
    $sFirst = hPOST("firstname");
    $sLast = hPOST("lastname");
    //sanitize Profile
    $sResume = hPOST("resume");
    $sWebsite = hPOST("website");
    $sAbout = hPOST("about");

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
