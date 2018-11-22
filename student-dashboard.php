<?php
    include 'student-controller.php';
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    }
    else {
        ?>

        <!DOCTYPE HTML>
        <html lang="en">

        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
                  integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4"
                  crossorigin="anonymous">
            <script
                    src="../seniorproject/js/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
                    integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
                    crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
                    integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
                    crossorigin="anonymous"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
                  integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
                  crossorigin="anonymous">
            <script src="js/sortable.js"></script>
            <link rel="stylesheet" href="css/style.css">
            <link rel="stylesheet" href="css/sortable.css">

            <style>
                #search {
                    background-color: #fff;
                }

                .card-title {
                    margin-bottom: 5px;
                }

                .card-header + .list-group .list-group-item:first-child {
                    border-top: 1px solid rgba(0, 0, 0, .125);
                }

                a {
                    color: #01426A;
                }

                @media (max-width: 870px) {
                    .col-md

                .4 {
                    max-width: 100% !important;
                    width: 100% !important;
                }
                }

                label {
                    font-size: 13pt;
                }

                .card-title {
                    text-align: center;
                }

                .card #welcome,
                .card .nav,
                .card .nav-item,
                .card form {
                    display: inline-block;
                }

                #welcome {
                    margin: 0;
                }

                .card .nav {
                    float: right;
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

                @media (max-width: 993px) {
                    #welcome {
                        padding-top: 12px;
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
        </head>

        <body>
        <!--nav bar-->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <p id="welcome" class="nav-link">Welcome: Billy Bronco <?php echo $userID; ?></p>
                        <ul class="nav nav-pills card-header-pills">
                            <li class="nav-item">
                                <a class="nav-link" href="profile.html">Profile</a>
                            </li>
                            <li class="nav-item">
                                <form method="post" action=".">
                                    <input type="hidden" name="action" value="change"/>
                                    <input type="submit" class="btn btn-link" value="Change Password"/>
                                </form>
                                <form method="post" action=".">
                                    <input type="hidden" name="action" value="logout"/>
                                    <input type="submit" class="btn btn-link" value="Logout"/>
                                </form>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
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
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Profile</h4>
                        <p class="card-text">You can edit your profile page here below</p>
                        <hr>
                    </div>
                    <?php $user = getUser($username);
                    ?>
                    <div class="card-body" style="padding-top: 0px;">
                        <div id="edit-messages"></div>
                        <form id="edit-profile-form" method="post" enctype="multipart/form-data" action="">
                            <div class="form-group">
                                <label for="about">About Me</label>
                                <textarea class="form-control" id="about" name="about" rows="3"><?php echo $user->bio ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="resume">Resume Link</label>
                                <input type="url" class="form-control" id="resume" name="resume"
                                       placeholder="LinkedIn or Online Resume" value="<?php echo $user->linkedin ?>">
                            </div>
                            <div class="form-group">
                                <label for="website">Personal Website</label>
                                <input type="url" class="form-control" id="website" name="website" placeholder="Portfolio Website" value="<?php echo $user->website ?>">
                            </div>
                            <div class="form-group">
                                <label for="picture">Profile Picture</label>
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
            <!--upload projects-->
            <div class="col-md-4">
                <div class="card" id="upload-section">
                    <div class="card-header" id="form-header">
                        <h4 class="card-title">Upload Project</h4>
                        <p class="card-text">You can access FTP and File Directory using the links below or upload a
                            zip</p>
                        <hr>
                    </div>
                    <!-- form location -->
                </div>
            </div>
            <!--my projects-->
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