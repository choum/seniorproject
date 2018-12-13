<?php 
    /*
     * Created By: Nat Rivera
     * Description: This pages serves as the place to find information and external links to the
     * team behind this websites creation. As of right now, it holds the info and titles of the original
     * four members in charge of this project. It is assumed that the next team will either add on to or
     * replace this page with their own information.
     */
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
</head>
<body>
<div style='min-height: 100vh;' >
  <div class='card'>
    <div class="card-header">
      <p style="display: inline;">Meet the Development Team</p>
      <form method="post" action="." style="float: right;">
        <input type="hidden" name="action" value="" />
        <input type="submit" class="btn btn-link" value="Home"/>
      </form>
    </div>

  </div>
  <div class='row'>
    <div class='col-xs-12 col-sm-6 col-xl-3'>
      <div class="card" style="width: 18rem;">
        <img class="card-img-top img img-responsive" width="auto" height="250" src="./img/nat_profile.jpg" alt="Card image cap">
        <div class="card-body">
          <h3 class="card-title">Nat Rivera</h3>
          <h5>Team Lead / Front-End Developer</h5>
          <p class="card-text">As team lead, Nat was responsible for the overall direction of the project and getting the team on the same page. Nat also served as front-end dveloper and aided in the creation of the admin portion of the site. </p>
          <a href="https://www.linkedin.com/in/natrivera/" class="btn btn-primary" target='_blank'>LinkedIn</a>
        </div>
      </div>
    </div>
    <div class='col-xs-12 col-sm-6 col-xl-3'>
      <div class="card" style="width: 18rem;">
        <img class="card-img-top img img-responsive" width="auto" height="250" src="./img/heather_team.jpg" alt="Card image cap">
        <div class="card-body">
          <h3 class="card-title">Heather Tran</h3>
          <h5>Scrum Master / Front-End Developer</h5>
          <p class="card-text">As Scrum Master, Heather was responsible for dividing work and tracking the progress made on the project. She also was in charge of designing the modern UI as well as working on connecting the back-end of the login and register with the front-end</p>
          <a href="https://www.linkedin.com/in/heather-tran-97092a108/" class="btn btn-primary" target='_blank'>LinkedIn</a>
        </div>
      </div>
    </div>
    <div class='col-xs-12 col-sm-6 col-xl-3'>
      <div class="card" style="width: 18rem;">
        <img class="card-img-top img img-responsive" width="auto" height="250" src="./img/34153198885_5e8f939c54_k.jpg" alt="Card image cap">
        <div class="card-body">
          <h3 class="card-title">Justin Crest</h3>
          <h5>Database Developer / Quality Assurance</h5>
          <p class="card-text">As one of the developers, Justin was responsible for back-end development, and nearly all of the queries that this website runs on.
              Along with this, Justin was responsible for bug testing and quality assurance of both front-end and back-end changes up until the first public implementation of the website.</p>
          <a href="https://www.linkedin.com/in/justin-crest-a1a679aa/" class="btn btn-primary" target='_blank'>LinkedIn</a>
        </div>
      </div>
    </div>
    <div class='col-xs-12 col-sm-6 col-xl-3'>
      <div class="card" style="width: 18rem;">
        <img class="card-img-top img img-responsive" width="auto" height="250" src="./img/linkedin_Nareg.jpg" alt="Card image cap">
        <div class="card-body">
          <h3 class="card-title">Nareg Khodanian</h3>
          <h5>Back-End Developer / Hardware Specialist</h5>
          <p class="card-text">Nareg was responsible for the setup of a barebones server and create a working architecture. Nareg was also responsible for the development of the student dashboard and its upload modules. Nareg used a combination of PHP and AJAX to generate a seamless user experience.</p>
          <a href="#" class="btn btn-primary" target='_blank'>LinkedIn</a>
        </div>
      </div>
    </div>
  </div>
</div>
<body>
</html>
