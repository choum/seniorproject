<?php
include 'includes/cis4270CommonIncludes.php';
$post = hPost('action');

switch ($post) {
  case 'login':
  //check to see form tokens are equal

  //validate via DB
  //take to correct dashboard
  break;

  case 'register':
  //check to form tokens
  //validate inputs
  //store in db

  //take to dashboard
  include 'dashboard.html';
  break;

  default:
  //end session
  include 'login.html';
}


?>
