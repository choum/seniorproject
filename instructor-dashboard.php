<?php
include('instructor-controller.php');
require('header.php');
?>

  <div class="row">
    <!--edit profile-->
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Add Project</h4>
          <hr>
        </div>
        <div class="card-body" style="padding-top: 0px;">
            <div class="form-group">
              <p><label for="classname">Available Classes</label></p>
              <form method="post">
                <select onchange='this.form.submit()' name='current_selected_course' class="form-control">
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
            </div>
            <form method='post' enctype="multipart/form-data">
              <input  type='hidden' name='course' value='<?php echo $current_selected_course->courseID; ?>' >
              <div class="form-group">
                <label for="projectname">Project Name</label>
                <input required type="text" name='name' class="form-control" id="section" placeholder="EX: Project 1">
              </div>
              <div class="form-group">
                <label for="description">Brief Project Description</label>
                <textarea class="form-control" name='description' id="description" rows="3"></textarea>
              </div>
              <div class="form-group">
                <p><label for="classname">Type of Project</label></p>
                <select name='type'>
                  <option value='PHP'>PHP</option>
                  <option value='JSP/Java'>JSP/Java</option>
                </select>
              </div>
              <div class="form-group">
                <label for="picture">Upload Project Instructions</label>
                <input type="file" name='file' class="form-control-file" id="picture" aria-describedby="fileHelp">
              </div>
              <input type='hidden' name='action' value='add_project' >
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
          <div style="float: right;">
            <p class="card-text">Filter by:
              <form method='post'>
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
                  <th>Term</th>
                  <th colspan="2">Assignment List</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (!empty($current_user_courses)) {
                  foreach ($current_user_courses as $user_course) {
                    echo "<tr>";
                      echo "<td>CIS " . $user_course[0]->courseNumber . "." . $user_course[0]->courseSection . "</td>";
                      echo "<td>" . $user_course[0]->courseTitle . "</td>";
                      echo "<td>" . $user_course[1] . "</td>";
                      echo "<td>" . $user_course[0]->term . "</td>";
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
    </div>
  </div>
</body>

</html>
