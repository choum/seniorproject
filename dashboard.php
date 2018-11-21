<?php
    require('header.php');
    include('dashboard-controller.php');
?>

<div class="row">
    <!--edit profile-->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 style="text-align:center;">Enroll In Course</h4>
                <hr>
                <p style="margin-bottom: 0;">Use Course ID given by Professor to enroll</p>
            </div>
            <div class="card-body">
                <form method="post" action=".">
                    <div class="form-group">
                        <input type="text" />
                        <input type="hidden" name="action" value="addClass"
                               <input type="submit" class="btn" value="Add Class">
                    </div>
                </form>
                <hr>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Profile</h4>
                <p class="card-text">You can edit your profile page here below</p>
                <hr>
            </div>
            <div class="card-body" style="padding-top: 0px;">
                <form>
                    <div class="form-group">
                        <label for="about">About Me</label>
                        <textarea class="form-control" id="about" rows="3"><?php if ($aboutMe != NULL)
    {
        echo $aboutMe;
    } ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="resume">Resume Link</label>
                        <input type="url" class="form-control" id="resume" placeholder="LinkedIn or Online Resume" <?php if ($resume != NULL)
    {
        echo "value='$resume'";
    } ?>>
                    </div>
                    <div class="form-group">
                        <label for="website">Personal Website</label>
                        <input type="url" class="form-control" id="website" placeholder="Portfolio Website" <?php if ($portfolio != NULL)
    {
        echo "value='$portfolio'";
    } ?>>
                    </div>
                    <div class="form-group">
                        <label for="picture">Profile Picture</label>
<?php if ($picture != NULL)
    { ?><img src="" style="width: 100px; height: 100px" alt="Current Profile Picture"> <?php } ?>
                        <input type="file" class="form-control-file" id="picture" aria-describedby="fileHelp">
                        <small id="fileHelp" class="form-text text-muted">Only supports PNG, JPG.</small>
                    </div>
                    <input type="submit" class="btn" value="updateProfile">
                </form>
                <hr />
            </div>
        </div>
    </div>
    <!--upload projects-->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Upload Project</h4>
                <p class="card-text">You can access FTP and File Directory using the links below or upload a zip</p>
                <hr>
            </div>
            <div class="card-body" style="padding-top: 0px;">
                <div class="form-group">
                    <p><label for="classname">Available Classes</label></p>
                    <form method="post" action=".">
                        <select onchange='this.form.submit()' name='current_selected_course' class="form-control">
                        <?php foreach ($courses as $course) {
                                if ($course->courseID == $current_selected_course->courseID) {
                                    echo "<option  value='$course->courseID' selected >" . $course->term . "---" . $course->courseNumber . "." . $course->courseSection . "</option>";
                                } else {
                                    echo "<option value='$course->courseID'>CIS " . $course->courseNumber . "." . $course->courseSection . " - " . $course->term . "</option>";
                                }
                            }
                        ?>
                        </select>
                    </form>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <input  type='hidden' name='course' value="<?php echo $current_selected_course->courseID; ?>">
                    <div class="form-group">
                        <label>Class:</label><br />
                        <select>
                            <option>CIS 3090 Fall 2018</option>
                            <option>CIS 4260 Fall 2018</option>
                            <option>CIS 4290 Fall 2018</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Assignment Name</label><br />
                        <select>
                            <option>Project 1</option>
                            <option>Project 2</option>
                            <option>Final Project</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="zip">Project Files:</label>
                        <input type="file" class="form-control-file" name="zip" id="zip" aria-describedby="fileHelp">
                    </div>
                    <div class="form-group">
                        <label for="filesToUpload[]">Images for Carousel Display:</label><br />
                        <input name="filesToUpload[]" class="form-control-file" id="filesToUpload" type="file" multiple="" /><br />
                    </div>
                    <div class="form-group">
                        <p>Is this a group project? <input type="checkbox" name="group" value=""></p>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn" value="uploadProject">Upload</button>
                    </div>
                    <hr />
            </div>
        </div>
    </div>
    <!--my projects-->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">My Projects</h4>
                <hr />
                <p class="card-text">Links will lead to assignments, disabled links are not uploaded projects yet but still are assigned</p>
            </div>
        </div>
        <div class='lay'>
            <div class='card-deck'>
                <div class='container-fluid'>
                    <div class='row' id='sortable'>
                        <div class="col-12 box" data-size='12'>
                            <div class="card no-margin mb-3">
                                <div class="card-header">
                                    <h5 class="card-title">CIS 4260.01 Fall 2018 - Serverside Web Development</h5>
                                    <p class="card-text" style="text-align: center;">Zhongming Ma</p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><a href="#" target="_blank" class="card-link">Assignment 1 </a> <span style="float:right;">Group Project</span><i class="fas fa-users" style="float:right; color: #01426A; margin-top: 2px; margin-right: 5px;"></i></li>
                                    <li class="list-group-item">Assignment 2</li>
                                    <li class="list-group-item">Project</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 box" data-size='12'>
                            <div class="card no-margin mb-3">
                                <div class="card-header">
                                    <h5 class="card-title">CIS 4270.01 Fall 2018 - Secure Web Apps</h5>
                                    <p class="card-text" style="text-align: center;">John Miller</p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Final Project <i class="fas fa-star" style="float:right; color:#FFB500;"></i></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 box" data-size='12'>
                            <div class="card no-margin mb-3">
                                <div class="card-header">
                                    <h5 class="card-title">CIS 4290.01 Fall 2018 - IS Project Management and Development </h5>
                                    <p class="card-text" style="text-align: center;">Hui Shi</p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Final Project</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>

</html>