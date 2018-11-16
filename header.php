<?php
$db = new SQLHelper();
$username = $_SESSION['user'];
$current_user = $db->getUser($username);
$current_user_name = $current_user->firstName . " " . $current_user ->lastName;

$role = $_SESSION['role'];
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
  <script src="js/sortable.js"></script>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/sortable.css">

  <style>
    #search {
      background-color: #fff;
    }

    .card-title {
      margin-bottom: 5px;
    }

    .card-header+.list-group .list-group-item:first-child {
      border-top: 1px solid rgba(0, 0, 0, .125);
    }

    a {
      color: #01426A;
    }

    label {
      font-size: 13pt;
    }

    .card-title {
      text-align: center;
    }

    .card #welcome,
    .card .nav,
    .card .nav-item,
    .card .search {
      display: inline-table;
    }

    #welcome {
      margin: 0;
      float: left;
    }

    .card .nav {
      float: right;
    }

    @media (max-width: 468px) {
      #welcome {
        margin: 0;
        padding-left: 5px;
      }
      .card #welcome,
      .card .nav,
      .card .nav-item,
      .card .search {
        display: inline;
      }
      .card .nav {
        float: left;
      }
    }

    @media (max-width: 993px) {
      #welcome {
        padding-top: 12px;
      }
    }

    @media (max-width: 650px) {
      #welcome {
        margin: 0;
        padding-left: 5px;
        padding-top: 9px;
        width: 100%;
      }
      .card #welcome,
      .card .nav,
      .card .nav-item,
      .card .search {
        display: block;
      }
      .card .nav {
        float: left;
      }
      #search-input {
        margin-left: 12px;
      }
    }
    #updateProf, #addProf {
      display: none;
    }
    #toggle {
      float: left;
    }
    #toggle li a {
      display: inline;
    }
  </style>
</head>

<body>
  <!--nav bar-->
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <p id="welcome" class="nav-link">Welcome: <?php if (!empty($current_user_name)) { echo $current_user_name; } ?></p>
          <ul class="nav nav-pills card-header-pills" id="menu">
            <li class="nav-item">
              <?php
              if ($role == 1) {
                echo('<form method="post" action=".">
                  <input type="hidden" name="action" value="studentProf" />
                  <input type="submit" class="btn btn-link" value="Profile"/>
                </form>');
              } else if ($role == 4) {
                  if (empty($menu)) {
                    $menu = '<form method="post" action="." id="instructor">
                      <input type="hidden" name="action" value="instructorDash" />
                      <input type="submit" class="btn btn-link" value="Instructor Dashboard"/>
                    </form>';
                  }
                  echo ($menu);
              }

              ?>
              <form method="post" action=".">
                <input type="hidden" name="action" value="changePage" />
                <input type="submit" class="btn btn-link" value="Change Password"/>
              </form>
              <form method="post" action=".">
                <input type="hidden" name="action" value="logout" />
                <input type="submit" class="btn btn-link" value="Logout"/>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
