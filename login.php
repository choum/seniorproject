<?php
create_csrf_token();
?>
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
          if (!empty($error)) {
            echo $error;
          }?>
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
    <div style='position: absolute; bottom: 0; text-align: center;'>
      <a href='#team'><button id='dev' class='btn'>Meet the Development Team</button></a>
    </div>
  </div>
  <div id='team' style='min-height: 100vh;' >
    <div class='title'>
      <h3>Meet the Development Team</h3>
    </div>
    <div class='row'>
      <div class='col-xs-12 col-sm-6 col-xl-3'>
        <div class="card" style="width: 18rem;">
          <img class="card-img-top" src="https://www.cornwallbusinessawards.co.uk/wp-content/uploads/2017/11/dummy450x450.jpg" alt="Card image cap">
          <div class="card-body">
            <h3 class="card-title">Nat Rivera</h3>
            <h5>Team Lead / Front-End Developer</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="https://www.linkedin.com/in/natrivera/" class="btn btn-primary" target='_blank'>LinkedIn</a>
          </div>
        </div>
      </div>
      <div class='col-xs-12 col-sm-6 col-xl-3'>
        <div class="card" style="width: 18rem;">
          <img class="card-img-top" src="https://www.cornwallbusinessawards.co.uk/wp-content/uploads/2017/11/dummy450x450.jpg" alt="Card image cap">
          <div class="card-body">
            <h3 class="card-title">Heather Tran</h3>
            <h5>Scrumaster / Front-End Developer</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" class="btn btn-primary" target='_blank'>LinkedIn</a>
          </div>
        </div>
      </div>
      <div class='col-xs-12 col-sm-6 col-xl-3'>
        <div class="card" style="width: 18rem;">
          <img class="card-img-top" src="https://www.cornwallbusinessawards.co.uk/wp-content/uploads/2017/11/dummy450x450.jpg" alt="Card image cap">
          <div class="card-body">
            <h3 class="card-title">Justin Crest</h3>
            <h5>Database Developer / Quality Assurance</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" class="btn btn-primary" target='_blank'>LinkedIn</a>
          </div>
        </div>
      </div>
      <div class='col-xs-12 col-sm-6 col-xl-3'>
        <div class="card" style="width: 18rem;">
          <img class="card-img-top" src="https://www.cornwallbusinessawards.co.uk/wp-content/uploads/2017/11/dummy450x450.jpg" alt="Card image cap">
          <div class="card-body">
            <h3 class="card-title">Nareg Khodanian</h3>
            <h5>Back-End Developer / Hardware Specialist</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" class="btn btn-primary" target='_blank'>LinkedIn</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $('#dev').click(function(){
      $('html, body').animate({
        scrollTop: $('#team').offset().top
      }, 200);
    });
  </script>
</body>

</html>
