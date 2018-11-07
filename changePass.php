<?php
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .main {
      display: table;
      margin: 0 auto;
      padding-top: 5%;
    }

    #btn1 {
      background-color: #01426A;
      color: #fff;
    }

    .card-title {
      text-align: center;

    }
  </style>
</head>

<body>
  <div class="main a">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title">Change Password</h3>
        <?php if (isset($error)) {echo "<p style='color: red;'>". $error . "<p>";} ?>
        <form method="post" action=.>
        <input type="hidden" class="form-control" name="action" value="change">
          <div class="form-group">
            <label for="currentPass">Current Password</label>
            <input type="password" class="form-control" name="currentPass" id="currentPass" placeholder="Current Password" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="newPass" id="password" placeholder="New Password" required>
          </div>
          <div class="form-group">
            <label for="comfPass">Confirm Password</label>
            <input type="password" class="form-control" name="comfPass" id="comfPass" placeholder="Confirm Password" required>
          </div>
          <button type="submit" class="btn" id="btn1">Change Password</button>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
