<?php
    require('header.php');
    include('student-controller.php');   
    
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    }
    else {
        ?>
        <style>
            @media (max-width: 870px) {
                .col-md-4 {
                max-width: 100% !important;
                width: 100% !important;
            }
            }

            @media (max-width: 468px) {
                #welcome {
                    margin: 0;
                    padding-left: 5px;
                }

                .card #welcome,
                .card .nav,
                .card .nav-item,
                .card .search {
                    display: inline;
                }

                .card .nav {
                    float: left;
                }
            }

            @media (max-width: 867px) {
                #welcome {
                    margin: 0;
                    padding-left: 5px;
                    padding-top: 8px;
                    width: 100%;
                }

                .card #welcome,
                .card .nav,
                .card .nav-item,
                .card .search {
                    display: block;
                }

                .card .nav {
                    float: left;
                }

                .col-md-4,
                .col-md-12 {
                    padding-left: 5px !important;
                    padding-right: 5px !important;
                }
            }

            @media (max-width: 575px) {
                .box {
                    padding: 0px;
                }
            }
        </style>

        <div class="row">
            <div class="col-md-4">
                <!--Course Enrollment-->
                <div class="card">
                    <div class="card-header">
                        <h4 style="text-align:center;">Enroll In Course</h4>
                        <hr>
                        <p style="margin-bottom: 0;">Use Course ID given by Professor to enroll</p>
                    </div>
                    <div class="card-body">
                        <div id="enrollment-messages"></div>
                        <form id="enrollment-form" method="post" action="">
                            <div class="form-group">
                                <input type="text" name='enrollment_key'/>
                                <input type='hidden' name='action' value='enroll_course'>
                                <input type="submit" class="btn" value="Add">
                            </div>
                        </form>
                        <hr>
                    </div>
                </div>
                <!--Edit profile -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Profile</h4>
                        <p class="card-text">You can edit your profile page here below</p>
                        <hr>
                    </div>
                    <div class="card-body" style="padding-top: 0px;">
                        <div id="edit-messages"></div>
                        <form id="edit-profile-form" method="post" enctype="multipart/form-data" action="">
                            <div class="form-group">
                                <label for="about">About Me</label>
                                <textarea class="form-control" id="about" name="about" rows="3"><?php echo $current_user->bio ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="resume">Resume Link</label>
                                <input type="url" class="form-control" id="resume" name="resume"
                                       placeholder="LinkedIn or Online Resume" value="<?php echo $current_user->linkedin ?>">
                            </div>
                            <div class="form-group">
                                <label for="website">Personal Website</label>
                                <input type="url" class="form-control" id="website" name="website" placeholder="Portfolio Website" value="<?php echo $current_user->website ?>">
                            </div>
                            <div class="form-group">
                                <label for="picture">Profile Picture</label>
                                <?php if($current_user->imageLink != NULL): ?>
                                <img  src="<?php echo $imageDir . $current_user->imageLink; ?>" alt="Profile Picture" style="width: 100px; height: auto">
                                <?php endif; ?>
                                <input id="profile-image" type="file" name="image_files" class="form-control-file" aria-describedby="fileHelp">
                                <small id="fileHelp" class="form-text text-muted">Only supports PNG, JPG.</small>
                            </div>
                            <input type='hidden' name='action' value='edit_profile'>
                            <input id="submit-profile" type="submit" class="btn" value="Update">
                        </form>
                        <hr/>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <!--upload projects-->
                <div class="card" id="upload-section">
                    <div class="card-header" id="form-header">
                        <h4 class="card-title">Upload Project</h4>
                        <p class="card-text">You can access FTP and File Directory using the links below or upload a zip</p>
                        <hr>
                    </div>
                    <!-- form location -->
                </div>
            </div>
            <!--current courses-->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">My Projects</h4>
                        <hr/>
                        <p class="card-text">Links will lead to assignments, disabled links are not uploaded projects
                            yet but still are assigned</p>
                    </div>
                </div>
                <div class='lay'>
                    <div class='card-deck'>
                        <div class='container-fluid'>
                            <div class='row' id='sortable'>
                            <?php foreach($courses as $course): ?>
                                <div class="col-12 box" data-size='12'>
                                    <div class="card no-margin mb-3">
                                        <div class="card-header">
                                            <h5 class="card-title">CIS <?php echo "$course->courseNumber$course->courseSection $course->term - $course->courseTitle"; ?></h5>
                                            <p class="card-text" style="text-align: center;"><?php echo getTeacher($course->teacherID); ?></p>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                        <?php foreach($assignments as $assignment):  $bool = true; ?>
                                        <?php if($assignment->courseID == $course->courseID): ?>
                                            <li class="list-group-item">
                                            <?php foreach($studentAssignments as $studentAssignment): ?>
                                            <?php if($studentAssignment[1] == $assignment->id AND $bool == true): ?>
                                                <a href="<?php echo "/cap/student/$username/$course->courseID/" . $studentAssignment[2] . "/";?>" target="_blank" class="card-link"><?php echo $assignment->name; ?></a>
                                                <?php if($studentAssignment[5] == TRUE): ?>
                                                    <span style="float:right;">Group Project</span><i class="fas fa-users" style="float:right; color: #01426A; margin-top: 2px; margin-right: 5px;"></i>
                                                <?php endif; ?>
                                                <?php if($studentAssignment[6] == TRUE) : ?>
                                                    <i class="fas fa-star" style="float:right; color:#FFB500;"></i>
                                                <?php endif; $bool = false;?>
                                            <?php elseif($bool == true): echo $assignment->name; $bool = false; ?>
                                            <?php endif; ?>
                                            <?php endforeach; ?>
                                            </li>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                                <div class="col-12 box" data-size='12'>
                                    <div class="card no-margin mb-3">
                                        <div class="card-header">
                                            <h5 class="card-title">CIS 4260.01 Fall 2018 - Serverside Web
                                                Development</h5>
                                            <p class="card-text" style="text-align: center;">Zhongming Ma</p>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><a href="#" target="_blank" class="card-link">Assignment
                                                    1 </a> <span style="float:right;">Group Project</span><i
                                                    class="fas fa-users"
                                                    style="float:right; color: #01426A; margin-top: 2px; margin-right: 5px;"></i>
                                            </li>
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
                                            <li class="list-group-item">Final Project <i class="fas fa-star"
                                                                                         style="float:right; color:#FFB500;"></i>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-12 box" data-size='12'>
                                    <div class="card no-margin mb-3">
                                        <div class="card-header">
                                            <h5 class="card-title">CIS 4290.01 Fall 2018 - IS Project Management and
                                                Development </h5>
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
        </div>
        </body>
        <script>
            $(document).ready(function() {

                var loadForm = function () {
                    $.get("modules/assignment_upload_module.php", function (data) {
                        $("#form-header").after(data);
                    });
                    var r = $.Deferred();
                    console.log('Form loaded');
                    return r;
                }

                var onSelectChange = function () {
                    $(document).on('change', '#user-courses', function () {
                        var courseID = $(this).find(":selected").val();
                        if (courseID) {
                            $.ajax({
                                type: 'POST',
                                url: 'student-dashboard.php',
                                data: {course_id: courseID},
                                success: function (html) {
                                    $('#user-assignments').html(html);
                                }
                            });
                        }
                        else {
                            $('#choose-course').remove();
                            $('#user-assignments').html('<option id="choose-course" value="">Select a course first</option>');
                        }
                    });
                    var r = $.Deferred();
                    return r;
                }

                loadForm().done(onSelectChange());

                $(function () {
                    var enrollment = $('#enrollment-form');
                    var enMessages = $('#enrollment-messages');

                    $(enrollment).submit(function (event) {
                        event.preventDefault();
                        event.stopPropagation();
                        var formData = $(enrollment).serialize();
                        $.ajax({
                            type: 'POST',
                            url: $(enrollment).attr('action'),
                            data: formData
                        }).done(function (response) {
                            $(enMessages).text(response);
                            $('#upload-form').load("modules/assignment_upload_module.php #assignment-form");
                        }).fail(function (data) {
                            if (data.responseText !== '') {
                                $(enMessages).text(data.responseText);
                            } else {
                                $(enMessages).text('Oops! An error occured and your message could not be sent.');
                            }
                        })
                    });
                });

                $(function () {
                    var editProfile = $('#edit-profile-form');
                    var edMessages = $('#edit-messages');
                    $(editProfile).on('submit', function (e) {
                        e.preventDefault();
                        $.ajax({
                            type: 'POST',
                            url: 'student-dashboard.php',
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                $(edMessages).text(data);
                            }
                        });
                    });
                });

            });

            $('body').on('submit','#assignment-form', function(event) {
                event.preventDefault();
                if($('#user-assignments').val() == "" || $('#user-assignments').val() == null){
                    $('#upload-messages').text('Please choose an assignment first.');
                }
                else{
                    $.ajax({
                        type: 'POST',
                        url: 'student-dashboard.php',
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            $('#upload-messages').text(data);
                        }
                    });
                }
            });
        </script>
        </html>
<?php
    }
?>