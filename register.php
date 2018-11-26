<?php
create_csrf_token();
?>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .error {
      color: red !important;
    }
    body {
      margin: 5%;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="card-body">
      <h3 class="card-title" style="text-align:center; margin-bottom: 5px;">Register</h3>
      <hr />
      <form name="register" id="msform" method="post" action="." enctype="multipart/form-data" data-ajax="false">
        <input type="hidden" name="action" value="register" />
        <?php echo csrf_token_tag();
        if (isset($error)) {echo "<p style='color: red;'>". $error . "<p>";} ?>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control"placeholder="Username" id="username"/>
                <label for="email">Email</label>
                <input type="text" name="email" class="form-control"placeholder="Email" id="email"/>
              </div>
              <div class="form-group">
                  <label for="firstname">First Name</label>
                  <input type="text" name="firstname" class="form-control" placeholder="First Name" id="firstname"/>
                  <label for="lastname">Last Name</label>
                  <input type="text" name="lastname" class="form-control"placeholder="Last Name" id="lastname"/>
              </div>
              <div class="form-group">
                <label for="pass">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" id="password"/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="about">About Me</label>
                <textarea class="form-control" id="about" rows="3" placeholder="Type a little bit about yourself..."></textarea>
              </div>
              <div class="form-group">
                <label for="resume">Resume Link</label>
                <input type="url" class="form-control" id="resume" name="resume" placeholder="LinkedIn or Online Resume">
                <label for="website">Personal Website</label>
                <input type="url" class="form-control" id="website" name="website" placeholder="Portfolio Website">
              </div>
              <div class="form-group">
                <label for="image_files">Profile Picture</label>
                <input type="file" name='file' class="form-control-file" id="picture" aria-describedby="fileHelp">
                <small id="fileHelp" class="form-text text-muted">Only supports PNG, JPG.</small>
              </div>
            </div>
        </div>
          <input type="submit" id="submit" class="btn btn-dark" value="Submit">
      </form>
    </div>
  </div>
</body>
</html>
<script>

  jQuery.validator.addMethod("alphanumeric", function(value, element) {
    return this.optional(element) || /^[\w.]+$/i.test(value);
  }, "Letters, numbers, and underscores only please");

  jQuery.validator.addMethod("lettersonly", function(value, element) {
    return this.optional(element) || /^[a-z]+$/i.test(value);
  }, "Letters only please");

  jQuery.validator.addMethod("noSpace", function(value, element) {
  return value.indexOf(" ") < 0 ;
  }, "No spaces please");
    
    $("#msform").validate({
      rules: {
        username: {
          required: true,
          minlength: 5,
          maxlength: 13,
          alphanumeric: true,
          noSpace: true
        },
        firstname: {
          required: true,
          lettersonly: true,
          noSpace: true
        },
        lastname: {
          required: true,
          lettersonly: true,
          noSpace: true
        },
        password: {
          required: true,
          minlength: 5,
          maxlength: 13,
          alphanumeric: true,
          noSpace: true
        },
        email: {
          required: true,
          email: true,
          noSpace: true
        },
        resume: {
          required: false,
          url: true,
          noSpace: true
        },
        website: {
          required: false,
          url: true,
          noSpace: true
        }
      }
    });
  $("#submit").click(function() {
    if ($('#msform').valid()) {
      $('msform').submit();
    }
  });
</script>
