<?php ?>
<html>

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
  </style>
</head>

<body>
  <div class="card">
    <div class="card-header">
    </div>
    <div class="card-body">
      <h4 class="card-title">CIS 4260 - Server-side Web Development</h4>
      <p class="card-text" style="text-align:center;">Assignment 1</p>
      <hr/>
      <table class="table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Link to Project Page</th>
          </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($users)) {
          foreach ($users as $user) {
            echo('<td>' . $user->$username; . '</td>');
            echo('<td><a href="sdc.cpp.edu/cap/' . $user->$username . '/' . $course . '/' . $assignment . '"</a></td>');
          }
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>

?>
