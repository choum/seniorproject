<?php
//functions for alerting, logging out, redirecting to dash
function logout() {
  after_successful_logout();
  header("refresh: 1;");
}
function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}
function redirect() {
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
function login() {
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
    var_dump($_SESSION);
    $error = "Form token error";
    require 'login.php';
  }
}
function register() {
  //check to form tokens
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

    //validate
    $sql = new SQLHelper;
    $bool = $sql->checkForDuplicate($sUser);
    var_dump($bool);
    if ($bool == false) {
      $error = "This Username is already taken. Please choose another username.";
      require 'register.php';
    } else if (!empty($error)) {
      require 'register.php';
    } else {
      //store in db
      $user = array($sUser, $sPass, $sFirst, $sLast, "student", $sAbout, $sEmail, NULL, $sResume, $sWebsite, 1, 0, date("Y-m-d"), date("Y-m-d"));
      $_SESSION['obj'] = $user;
      $_SESSION['user'] = $sUser;
      require 'setup.php';
    }
  }
}

function setup() {
  $createDB = new CreateDB;
  $createFTP = new CreateFTP;
  $sql = new SQLHelper;
  $user = $_SESSION['user'];
  if (csrf_token_is_valid()) {
    $sqlPass = hPOST("sqlPass");
    if ( !preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $sqlPass) ) {
      $error .= "SQL Password should only be alphanumeric characters length greater than 5 and less than 31 <br/>";
    } else if (empty($sqlPass)){
      $error .= "SQL Password cannot be empty<br/>";
    } else if ($sqlPass == $user) {
      $error .= "SQL Password cannot be your username<br/>";
    }
    $ftpPass = hPOST("ftpPass");
    if ( !preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $ftpPass) ) {
      $error .= "FTP Password should only be alphanumeric characters length greater than 5 and less than 31 <br/>";
    } else if (empty($ftpPass)){
      $error .= "FTP Password cannot be empty<br/>";
    } else if ($ftpPass == $user) {
      $error .= "FTP Password cannot be your username<br/>";
    }
    if (!empty($error)) {
      require 'setup.php';
    } else {
      $userArray = $_SESSION['obj'];
      $userObj = new User($userArray[0], $userArray[1], $userArray[2], $userArray[3], $userArray[4], $userArray[5], $userArray[6], $userArray[7], $userArray[8], $userArray[9], $userArray[10], $userArray[11], $userArray[12], $userArray[13]);
      $createDB->createDBUser($user, $sqlPass);
      $createFTP->createUser($user, $ftpPass);
      $sql->addUser($userObj);
      $_SESSION['obj'] = NULL;
      $_SESSION['role'] = 1;
      require 'dashboard.php';
      after_successful_login();
    }
  } else {
    var_dump("?");
  }
}
function change() {
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
}

 ?>
