<?php
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
          <form  method="post" >
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
                    if(strpos($current_selected_course->term , $year) !== false) {
                      echo  "<option value='" . $date_year . "' >" . $date_year . "</option>";
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
                    if(strpos($current_selected_course->term , $year) !== false) {
                      echo  "<option value='" . $date_term . "' >" . $date_term . "</option>";
                    } else {
                      echo "<option value='" . $date_term . "'>" . $date_term . "</option>";
                    }
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
                foreach($instructors as $instructor=> $instructor_value) {
                  echo "<option value='$instructor_value[0]'>" . $instructor_value[0] . "</option>";
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
          <label>Select existing class</label>
          <form method="post" action='#updateClass'>
            <select onchange='this.form.submit()' name='course_change_select' class="form-control">
              <?php
                foreach($courses as $course) {
                  if($course->courseID == $current_selected_course->courseID) {
                    echo "<option  value='$course->courseID' selected >" . $course->term . "---" . $course->courseNumber . "." . $course->courseSection . "---" . $course->courseTitle .  "</option>";
                  } else {
                    echo "<option value='$course->courseID'>" . $course->term . "---" . $course->courseNumber . "." . $course->courseSection . "---" . $course->courseTitle .  "</option>";
                  }
                }
              ?>
            </select>
        </form>
          <form method="post" >
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
                foreach($instructors as $instructor => $instructor_value ) {
                  if($instructor == $current_selected_course->teacherID) {
                    echo "<option value='$instructor' selected>" . $instructor_value[0] . "</option>";
                  } else {
                    echo "<option value='$instructor'>" . $instructor_value[0] . "</option>";
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
          <form method="post" >
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
          <form  method="post" >
            <label>Select existing professor</label>
            <select name='instructorID' onchange='this.form.submit()' class="form-control">
              <?php
                foreach($instructors as $instructor => $instructor_value ) {
                  echo "<option value='$instructor'>" . $instructor_value[0] . "</option>";
                }
              ?>
            </select>
          </form>
          <form method="post">
            <div class="form-group">
              <input type="hidden" name="instructorID" value="">
              <label for="classname">First Name</label>
              <input required type="text" class="form-control" name='firstName' id="classname" value="">
              <label for="classname">Last Name</label>
              <input required type="text" class="form-control" name='lastName' id="classname" value="">
              <label for="classname">Email</label>
              <input type="text" class="form-control" name='email' id="classname" value="">
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
              <form method='post'action='#welcome'>
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
                                      <form  method='post' action='#'>
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
</script>
