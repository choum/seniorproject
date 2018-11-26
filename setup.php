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

  <link rel="stylesheet" href="css/msform.css">

  <style>
    body {
      background-color: transparent;
    }
    label {
      text-align: left;
      font-size: 14px;
    }
  </style>
</head>

<body>
  <!-- multistep form -->
  <form id="msform" name="setup" method="post" action=".">
    <input type="hidden" name="action" value="setup" />
    <!-- progressbar -->
    <ul id="progressbar">
      <li class="active">SQL Account Setup</li>
    </ul>
    <!-- fieldsets -->
    <?php echo csrf_token_tag();if (isset($error)) {echo "<p style='color: red;'>". $error . "<p>";}  ?>
    <fieldset>
      <h2 class="fs-title">SQL Account Setup</h2>
      <h3 class="fs-subtitle">Your username will be your BroncoID</h3>
      <label for="SQLpass">Password</label>
      <input type="password" name="sqlPass" id="sqlPass" placeholder="Password" />
      <label for="cpass">Confirm Password</label>
      <input type="password" name="SQLcpass" id="SQLcPass" placeholder="Confirm Password" />
      <input type="submit" name="submit" id="submit" class="submit action-button" value="Submit" />
    </fieldset>
  </form>
</body>

</html>

<script>
  //jQuery time
  var current_fs, next_fs, previous_fs; //fieldsets
  var left, opacity, scale; //fieldset properties which we will animate
  var animating; //flag to prevent quick multi-click glitches

  $(document).keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
  });

  jQuery.validator.addMethod("alphanumeric", function(value, element) {
    return this.optional(element) || /^[\w.]+$/i.test(value);
  }, "Letters, numbers, and underscores only please");

  $(".next").click(function() {
    $("#msform").validate({
      rules: {
        sqlPass: {
          required: true,
          minlength: 5,
          maxlength: 13,
          alphanumeric: true
        },
        SQLcPass: {
          required: true,
          minlength: 5,
          maxlength: 13,
          alphanumeric: true,
          equalTo: "#sqlPass"
        },
        ftpPass: {
          required: true,
          minlength: 5,
          maxlength: 13,
          alphanumeric: true
        },
        ftpCpass: {
          required: true,
          minlength: 5,
          maxlength: 13,
          alphanumeric: true,
          equalTo: "#ftpCpass"
        }
      }
    });
    if ($('#msform').valid()) {
      if (animating) return false;
      animating = true;

      current_fs = $(this).parent();
      next_fs = $(this).parent().next();

      //activate next step on progressbar using the index of next_fs
      $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

      //show the next fieldset
      next_fs.show();
      //hide the current fieldset with style
      current_fs.animate({
        opacity: 0
      }, {
        step: function(now, mx) {
          //as the opacity of current_fs reduces to 0 - stored in "now"
          //1. scale current_fs down to 80%
          scale = 1 - (1 - now) * 0.2;
          //2. bring next_fs from the right(50%)
          left = (now * 50) + "%";
          //3. increase opacity of next_fs to 1 as it moves in
          opacity = 1 - now;
          current_fs.css({
            'transform': 'scale(' + scale + ')',
            'position': 'absolute'
          });
          next_fs.css({
            'left': left,
            'opacity': opacity
          });
        },
        duration: 800,
        complete: function() {
          current_fs.hide();
          animating = false;
        },
        //this comes from the custom easing plugin
        easing: 'easeInOutBack'
      });
    }
  });

  $(".previous").click(function() {
    if (animating) return false;
    animating = true;

    current_fs = $(this).parent();
    previous_fs = $(this).parent().prev();

    //de-activate current step on progressbar
    $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

    //show the previous fieldset
    previous_fs.show();
    //hide the current fieldset with style
    current_fs.animate({
      opacity: 0
    }, {
      step: function(now, mx) {
        //as the opacity of current_fs reduces to 0 - stored in "now"
        //1. scale previous_fs from 80% to 100%
        scale = 0.8 + (1 - now) * 0.2;
        //2. take current_fs to the right(50%) - from 0%
        left = ((1 - now) * 50) + "%";
        //3. increase opacity of previous_fs to 1 as it moves in
        opacity = 1 - now;
        current_fs.css({
          'left': left
        });
        previous_fs.css({
          'transform': 'scale(' + scale + ')',
          'opacity': opacity
        });
      },
      duration: 800,
      complete: function() {
        current_fs.hide();
        animating = false;
      },
      //this comes from the custom easing plugin
      easing: 'easeInOutBack'
    });
  });

  $("#submit").click(function() {
    if ($('#msform').valid()) {
      $('msform').submit();
    }
  });
</script>
