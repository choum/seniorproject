<?php

//check if user is logged in
if(isset($_SESSION)) {
  //get the username from session
  $username = $_SESSION["user"];
  //load the variables arrays for dashboard
  loadVariables();
}

function loadVariables() {
  //create an istance of SQLHelper to get data from database
  //load the arrays to use on the UI
  $db = new SQLHelper();

  //get user object from database
  $current_user = $db->getUser($username);
  //concat first and lasat name for display
  $current_user_name = $current_user->$firstName . " " . $current_user->$lastName;
  //get array of user courses
  $current_user_courses = $db->getUserCourses($current_user->$ID);
  //get array of user terms
  $current_user_terms = $db->getUserTerms($current_user->$ID);

  //get the action form the request
  $action = filter_input(INPUT_POST , 'action');
}

?>
