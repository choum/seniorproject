<?php include('instructor-controller.php'); ?>

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
  <link rel="stylesheet" href="css/style.css">

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
    .card form {
      display: inline-block;
    }

    #welcome {
      margin: 0;
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
  </style>
</head>

<body>
  <!--nav bar-->
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <p id="welcome" class="nav-link">Welcome: <?php echo $current_user_name; ?></p>
          <ul class="nav nav-pills card-header-pills">
            <li class="nav-item">
              <a class="nav-link" href="#">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <!--edit profile-->
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Add Project</h4>
          <hr>
        </div>
        <div class="card-body" style="padding-top: 0px;">
          <form>
            <div class="form-group">
              <p><label for="classname">Available Classes</label></p>
              <select>
                <?php
                  foreach ($current_user_terms as $term) {
                    echo "<option value='" . $term . "'>" . $term . "</option>"
                  }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="projectname">Project Name</label>
              <input type="text" class="form-control" id="section" placeholder="EX: Project 1">
            </div>
            <div class="form-group">
              <label for="description">Brief Project Description</label>
              <textarea class="form-control" id="description" rows="3"></textarea>
            </div>
            <div class="form-group">
              <p><label for="classname">Type of Project</label></p>
              <select>
                <option>PHP</option>
                <option>JSP/Java</option>
              </select>
            </div>
            <div class="form-group">
              <label for="picture">Upload Project Instructions</label>
              <input type="file" class="form-control-file" id="picture" aria-describedby="fileHelp">
            </div>
            <input type="submit" class="btn" value="Add Project">
          </form>
          <hr />
        </div>
      </div>
    </div>
    <!--my projects-->
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">List of Your Classes</h4>
          <hr />
          <p class="card-text" style="float: right;">Sort by:
            <select>
              <?php
                foreach ($current_user_terms as $term) {
                  echo "<option value='" . $term . "'>" . $term . "</option>"
                }
              ?>
            </select>
          </p>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table" style="margin: 0px auto;">
              <thead>
                <tr>
                  <th>Course ID</th>
                  <th>Name</th>
                  <th>Instructor</th>
                  <th>Term</th>
                  <th colspan="2">Assignment List</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  foreach ($current_user_courses as $user_course) {
                    echo "<tr>";
                      echo "<td>" . $user_course->$name . "</td>";
                      echo "<td>" . $user_course->$description . "</td>";
                      echo "<td>" . $user_course->$teacherID . "</td>";
                      echo "<td>" . $user_course->$term . "</td>";
                      echo "<td colspan="2">";
                        echo "<ul>";
                            foreach ($current_user->$assignments as $assignment) {
                              echo "</li>
                                      <form action='project-view.php' method='POST'>
                                        <input type='hidden' name='Course' value='" . $user_course . "'>
                                        <input type='hidden' name='Assignment' value='" . $assignment->$name . "'>
                                        <input type='Submit'>
                                      </form>
                                    </li>";
                            }
                        echo "</ul>
                            </td>
                          </tr>";
                   }
                 ?>
              </tbody>
            </table>
            <hr />
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
