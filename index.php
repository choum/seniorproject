<?php
//require all docs needed to run this redirect controller
require_once 'includes/cis4270CommonIncludes.php';
require_once 'SQLHelper.php';
require_once 'User.php';
require_once 'private/CreateFTP.php';
require_once 'private/CreateDB.php';
require_once 'login-functions.php';

//starts a session if one does not exist
if (!isset($_SESSION)) {
  session_start();
}

//gets the action to decide
$post = hPost('action');

//declares error var
$error = "";
switch ($post) {

case 'login':
  //calls function in login-functions.php
  login();
  break;

case 'register':
  //calls function in login-functions.php
  register();
  break;

case 'setup':
  //calls function in login-functions.php
  setup();
break;

case 'instructorDash':
  //checks if session role is correct
  $role = $_SESSION['role'];
  //checks if user is logged in
  $bool = $_SESSION['logged_in'];

  //runs check for both and redirects
  if (($role == 2 || $role == 4) && $bool) {
    require 'instructor-dashboard.php';
  } else {
    //something went wrong so we end the session if it is set
    if (isset($_SESSION)) {
      end_session();
    }
    //redirect them to login
    require 'login.php';
   }
  break;
case 'registerPage':
  //sends them to register page
  require 'register.php';
  break;
case 'logout':
  //calls logout function in login-functions.php
  logout();
  break;
case 'changePage':
  $bool = $_SESSION['logged_in'];
  //checks to see if the session is valid and the boolean is true
  if (is_session_valid() && $bool) {
        require 'changePass.php';
  } else {
    if (isset($_SESSION)) {
      end_session();
    }
    require 'login.php';
  }
  break;

case 'change':
  //calls function in login functions.php
  change();
break;

case 'project':
  //get project variables
  $course = filter_input(INPUT_POST, 'Course');
  $assignment = filter_input(INPUT_POST, 'Assignment');

   //sql statement
  include 'project-view.php';
  break;

default:
  //redirects them to proper directory
  redirect();
}
?>
