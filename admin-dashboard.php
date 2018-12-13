<?php
    /*
     * Created By: Heather Tran and updated by Nat Rivera
     * Description: This page serves as the view section of the administrator dashboard.
     * It is split into multiple sections. Creation and update of courses, creation and update
     * of instructors, and a list of every course and all of its assignments.
     */
  include('admin-controller.php');
  require('header.php');
?>



  <div class="row">
    <!--edit profile-->
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <ul class="nav nav-pills card-header-pills" id="toggle">
            <li class="nav-item">
              <a class="nav-link active" href="#" id="class">Class</a>
              <a class="nav-link" href="#" id="prof">Instructor</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="card" id="addClass">
        <div class="card-header">
          <h4 class="card-title">Add Class</h4>
          <hr>
        </div>
        <div class="card-body" style="padding-top: 0px;">
            <?php if(!empty($errorAddC)) {echo "<p style='color: red;'>$errorAddC</p>";} ?>
          <form  method="post" action=".">
            <div class="form-group">
              <label for="classname">Course ID</label>
              <input required type="number" min="1" max="9999" step="1" class="form-control" name='courseID' id="courseid" placeholder="EX: 4290">
            </div>
            <div class="form-group">
              <label for="classname">Section Number</label>
              <input required type="number" min="1" max="99" step="1" class="form-control" name='sectionNumber' id="section" min="1" placeholder="EX: 1">
            </div>
            <div class="form-group">
              <label for="classname">Year</label>
              <select name='term-year'>
                <?php
                  foreach ($date_years as $date_year) {
                      echo "<option value='" . $date_year . "'>" . $date_year . "</option>";
                  }
                 ?>
              </select>
              <label for="classname">Term</label>
              <select name='term'>
                <?php
                  foreach ($date_terms as $date_term) {
                      echo "<option value='" . $date_term . "'>" . $date_term . "</option>";
                  }
                 ?>
              </select>
            </div>
            <div class="form-group">
              <label for="description">Class Title</label>
              <input required type="text" class="form-control" name='classTitle' id="courseid" placeholder="EX: Intro to Computing">
            </div>
            <div class="form-group">
              <label for="description">Class Description</label>
              <textarea type="text" class="form-control" name='classDescription' id="courseid" placeholder="EX: Learn the basics of computing using current methods. (Optional)"></textarea>
            </div>
            <div class="form-group">
              <label>Professor</label>
              <select name='classInstructor'>
              <?php
                foreach($instructors as $instructor) {
                  echo "<option value='$instructor[0]'>" . $instructor[1] . "</option>";
                }
              ?>
              </select>
            </div>
            <input type='hidden' name='action' value='add_class' >
            <input type="submit" class="btn" value="Add Class">
          </form>
          <hr />
        </div>
      </div>
      <div class="card" id="updateClass">
        <div class="card-header">
          <h4 class="card-title">Update Class</h4>
          <hr>
        </div>
        <div class="card-body">
        <?php if(!empty($errorUpdateC)) {echo "<p style='color: red;'>$errorUpdateC</p>";} ?>
          <label>Select existing class</label>
          <form method="post" action='#updateClass'>
            <select onchange='this.form.submit()' name='course_change_select' class="form-control">
              <?php
                foreach($courses as $course) {
                  if($course->courseID == $current_selected_course->courseID) {
                    echo "<option  value='$course->courseID' selected >" . $course->term . " " . $course->courseNumber . "." . $course->courseSection . " " . $course->courseTitle .  "</option>";
                  } else {
                    echo "<option value='$course->courseID'>" . $course->term . " " . $course->courseNumber . "." . $course->courseSection . " " . $course->courseTitle .  "</option>";
                  }
                }
              ?>
            </select>
        </form>
          <form method="post" action="#updateClass">
            <input type='hidden' name='course_change_select' value='<?php echo $current_selected_course->courseID; ?>'>
            <div class="form-group">
              <label for="classname">Course ID</label>
              <input required type="number" min="1" max="9999" step="1" class="form-control" name='courseNumber' id="courseid" value="<?php echo $current_selected_course->courseNumber; ?>">
            </div>
            <div class="form-group">
              <label for="classname">Section Number</label>
              <input required type="number" min="1" max="99" step="1" class="form-control" name='sectionNumber' id="section" min="1" value="<?php echo $current_selected_course->courseSection; ?>">
            </div>
            <div class="form-group">
              <label for="classname">Year</label>
              <select name='term-year'>
                <?php
                  foreach ($date_years as $date_year) {
                    if(strpos($current_selected_course->term , $date_year) !== false) {
                      echo  "<option value='" . $date_year . "' selected>" . $date_year . "</option>";
                    } else {
                      echo "<option value='" . $date_year . "'>" . $date_year . "</option>";
                    }
                  }
                 ?>
              </select>
              <label for="classname">Term</label>
              <select name='term'>
                <?php
                  foreach ($date_terms as $date_term) {
                    if(strpos($current_selected_course->term , $date_term) !== false) {
                      echo  "<option value='" . $date_term . "' selected>" . $date_term . "</option>";
                    } else {
                      echo "<option value='" . $date_term . "'>" . $date_term . "</option>";
                    }
                  }
                 ?>
              </select>
            </div>
            <div class="form-group">
              <label for="description">Class Title</label>
              <input required type="text" class="form-control" name='classTitle' id="courseid" value="<?php echo $current_selected_course->courseTitle; ?>">
            </div>
            <div class="form-group">
              <label for="description">Class Description</label>
              <textarea type="text" class="form-control" name='classDescription' placeholder="(Optional)" id="courseid" placeholder=""><?php if(!empty($current_selected_course->description)) {echo $current_selected_course->description;} ?></textarea>
            </div>
            <div class="form-group">
              <label>Professor</label>
              <select name='classInstructor'>
              <?php
                foreach($instructors as $instructor) {
                  if($instructor[0] == $current_selected_course->teacherID) {
                    echo "<option value='$instructor[0]' selected>" . $instructor[1] . "</option>";
                  } else {
                    echo "<option value='$instructor[0]'>" . $instructor[1] . "</option>";
                  }
                }
              ?>
              </select>
            </div>
            <input type='hidden' name='action' value='update_class' >
            <input type="submit" class="btn" value="Update Class">
          </form>
          <hr />
        </div>
      </div>
      <div class="card" id="addProf">
        <div class="card-header">
          <h4 class="card-title">Add Instructor</h4>
          <hr>
        </div>
        <div class="card-body" style="padding-top: 0px;">
            <?php if(!empty($errorAddP)) {echo "<p style='color: red;'>$errorAddP</p>";} ?>
          <form method="post" action=".">
            <div class="form-group">
              <label for="firstName">First Name</label>
              <input required type="text" name='firstName' class="form-control" id="firstName">
              <label for="lastName">Last Name</label>
              <input required type="text" name='lastName' class="form-control" id="lastName">
              <label for="email">Email</label>
              <input required type="email" name='email' class="form-control" id="email">
            </div>
            <input type='hidden' name='action' value='add_instructor' >
            <input type="submit" class="btn" value="Add Instructor">
          </form>
          <hr />
        </div>
      </div>
      <div class="card" id="updateProf">
        <div class="card-header">
          <h4 class="card-title">Update Instructor</h4>
          <hr>
        </div>
        <div class="card-body" style="padding-top: 0px;">
            <?php if(!empty($errorUpdateP)) {echo "<p style='color: red;'>$errorUpdateP</p>";} ?>
          <form  method="post" action=".">
            <label>Select existing professor</label>
            <select name='instructorID' id="current_intructor_select" onchange='changeInstructorVars()' class="form-control">
              <?php
                foreach($instructors as $instructor) {
                  echo "<option value='$instructor[0]'>" . $instructor[1] . "</option>";
                }
              ?>
            </select>
          </form>
          <form method="post" action=".">
            <div class="form-group">
              <input type="hidden" id="update_instructor_id" name="instructorID" value="<?php echo $currrent_chosen_instructor[0]; ?>">
              <label for="classname">First Name</label>
              <input required id="update_instructor_fn" type="text" class="form-control" name='firstName' id="classname" value="<?php echo $currrent_chosen_instructor[3]; ?>">
              <label for="classname">Last Name</label>
              <input required id="update_instructor_ln" type="text" class="form-control" name='lastName' id="classname" value="<?php echo $currrent_chosen_instructor[4]; ?>">
              <label for="classname">Email</label>
              <input required id="update_instructor_email" type="text" class="form-control" name='email' id="classname" value="<?php echo $currrent_chosen_instructor[2]; ?>">
            </div>
            <input type='hidden' name='action' value='update_instructor' >
            <input type="submit" class="btn" value="Update Instructor">
          </form>
          <hr />
        </div>
      </div>
    </div>
    <!--my projects-->
    <div class="col-md-8" >
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">List of All Classes</h4>
          <hr />
          <div style="float: right;">
            <p class="card-text" >Filter by:
              <form method='post'action='.'>
                  <select onchange='this.form.submit()' name='user_selected_term'>
                    <?php
                      foreach($terms as $term) {
                        //check if the term was selected or is current if none was selected
                        if($term == $semester_year) {
                          echo    "<option value='$term' selected>" . $term . "</option>";
                        } else {
                            echo "<option value='$term'>" . $term . "</option>";
                        }
                      }//end of foreach
                    ?>
                  </select>
              </form>
            </p>
        </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table" style="margin: 0px auto;">
              <thead>
                <tr>
                  <th>Course ID</th>
                  <th>Name</th>
                  <th>Instructor</th>
                  <th colspan="2">Assignment List</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (!empty($current_user_courses)) {
                  foreach ($current_user_courses as $user_course ) {
                    echo "<tr>";
                      echo "<td>CIS " . $user_course[0]->courseNumber . "." . $user_course[0]->courseSection . "</td>";
                      echo "<td>" . $user_course[0]->courseTitle . "</td>";
                      echo "<td>" . $user_course[1] . "</td>";
                      echo "<td colspan='2'>";
                        echo "<ul>";
                            foreach ($user_course[2] as $assignment) {
                              echo "</li>
                                      <form  method='post' action='.'>
                                        <input type='hidden' name='action' value='project'>
                                        <input type='hidden' name='Course' value='" . $user_course[0]->courseID . "'>
                                        <input type='hidden' name='Assignment' value='" . $assignment[0] . "'>
                                        <input type='Submit' class='btn btn-link' value='" . $assignment[1] . "'>
                                      </form>
                                    </li>";
                            }
                        echo "</ul>
                            </td>
                          </tr>";
                  }
                }
                 ?>
              </tbody>
            </table>
            <hr />
          </div>
        </div>
      </div>
    </div>
</body>

</html>
<script>
  $('#class').click(function() {
    $('#addProf').hide();
    $('#addClass').show();
    $('#updateProf').hide();
    $('#updateClass').show();
    if (!($(this).hasClass('active'))) {
      $(this).toggleClass('active');
      $('#prof').toggleClass('active');
    }

  });
  $('#prof').click(function() {
    $('#addProf').show();
    $('#addClass').hide();
    $('#updateProf').show();
    $('#updateClass').hide();
    if (!($(this).hasClass('active'))) {
      $(this).toggleClass('active');
      $('#class').toggleClass('active');
    }
  });


  var instructors = <?php echo json_encode($instructors); ?>;
  function changeInstructorVars() {
    var current_ins = $("#current_intructor_select").val();

    for(var ins in instructors ) {
      if(ins == current_ins) {
        $("#update_instructor_id").val(instructors[ins][0]);
        $("#update_instructor_fn").val(instructors[ins][3]);
        $("#update_instructor_ln").val(instructors[ins][4]);
        $("#update_instructor_email").val(instructors[ins][2]);
      }
    }
  }//end of change instructor vars

</script>
