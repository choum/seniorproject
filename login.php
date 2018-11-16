<?php
create_csrf_token();
?>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" ></script>
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
    #reg {
      display: inline;
      float: right;
    }
    form {
      display: inline;
    }
  </style>
</head>

<body>
  <div class="main a" style='min-height: 100vh;'>
    <div class="card">
      <div class="card-body">
        <h3 class="card-title">Login</h3>
        <br />
        <form method="post" action=".">
          <input name="action" type="hidden" value="login" />
          <?php echo csrf_token_tag();
          if (isset($error)) {echo "<p style='color: red;'>". $error . "<p>";}
          ?>
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Bronco ID" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
          </div>
          <button type="submit" class="btn" id="btn1">Login</button>
        </form>
        <form method="post" action=".">
          <input name="action" type="hidden" value="registerPage" />
          <button type="submit" class="btn btn-dark" id="reg">Register</button>
        </form>
      </div>
    </div>
    <div style='text-align:center; color: #FFF;'>
      <h5>Programmed with passion by <a href="team.php" target="_blank" style="color: #CEB888;">#team_name.</a></h5>
    </div>
  </div>
</body>

</html>
