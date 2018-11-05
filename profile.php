<?php include("profile-controller.php"); ?>

<!DOCTYPE HTML>
<html lang="en">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <style>
            .card-img-top {
                border-bottom: 10px #CEB888 solid;
            }
        </style>
    </head>

    <body>
        <div class="row">
            <div class="col-md-3">
                <!--nav bar-->
                <div class="card text-center" id="nav">
                    <div class="card-header">
                        <ul class="nav nav-pills card-header-pills">
                            <?php if ($loggedIn == TRUE): ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo $dashboardLink; ?>">Dashboard</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Logout</a>
                                    </li>
                                <?php else: ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="login.php">Login</a>
                                    </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="card" style="">
                    <img class="card-img-top" src="<?php echo imageLink; ?>" alt="Profile Picture">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo "$firstName $lastName"; ?></h3>
                        <p class="card-text"><?php echo $bio; ?></p>
                        <p class="card-text">No contact info in DB</p>
                    </div>
                    <div class="card-body">
                        <a href="<?php echo $linkedin; ?>" class="card-link">Linkedin</a>
                        <a href="<?php echo $website; ?>" class="card-link">Personal Website</a>
                    </div>
                </div>

            </div>
            <div class="col-md-9">
                <!--class & projects tab-->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Classes & Projects</h3>
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <?php foreach ($courses as $course): ?>
                        <li class="nav-item">
                            <a class="nav-link"
                               id="<?php echo $course->courseNumber; ?>-tab" data-toggle="tab"
                               href="#<?php echo $course->courseNumber; ?>" role="tab"
                               aria-controls="<?php echo $course->courseNumber; ?>" aria-selected="false">
                                CIS <?php echo $course->courseNumber; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                        <li class="nav-item">
                            <a class="nav-link active" id="4270-tab" data-toggle="tab" href="#4270" role="tab" aria-controls="contact" aria-selected="true">CIS 4270</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                    <?php foreach ($courses as $course): ?>
                            <div class="tab-pane fade" id="<?php echo $course->courseNumber; ?>"
                                 role="tabpanel" aria-labelledby="<?php echo $course->courseNumber; ?>-tab">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item" style="text-align:center;"><strong><?php echo $course->courseTitle; ?></strong></li>
                                    <?php foreach ($assignments as
                                                $assignment):
                                        ?>
                                    <li class="list-group-item"><strong> <?php echo $assignment[1]; ?></strong> - <?php echo $assignment[2]; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                    <?php endforeach; ?>
                        <div class="tab-pane fade show active" id="4270" role="tabpanel" aria-labelledby="4270-tab">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item" style="text-align:center;"><strong>Secure Web Applications</strong></li>
                                <li class="list-group-item"><strong>Final Project</strong> - Description will go here about the project <span style="float:right;"> Group Project</span><i class="fas fa-users" style="float:right; color: #01426A; margin-top: 2px; margin-right: 5px;"></i>
                                    <i class="fas fa-star" style="float:right; color:#FFB500;"></i>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <?php if ($featuredAssignment == NULL OR $featuredAssignment == FALSE): ?>
                                <h4 class="card-title">No featured assignment chosen.</h4>
                        <?php else: ?>
                                <h4 class="card-title">CIS <?php echo $featuredCourseInfo[2]; ?> - <?php echo $featuredAssignmentName; ?></h4>
                                <h6>
                                    <a href="<?php $featuredAssignment[6]; ?>">Project Link</a>
                                    <?php if ($featuredAssignment[7] == TRUE): ?>
                                        <span style="float:right;">Group Project </span>
                                        <i class="fas fa-users" style="float:right; color: #01426A; margin-right: 5px;"></i>
                                    <?php endif; ?>
                                </h6>
                                <hr/>
                                <p class="card-text"><?php echo $featuredAssignment[5]; ?></p>
                        <div id="carouselExampleControls1" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img class="d-block w-100" src="hhtran/img/download.png" alt="First slide">
                                </div>
                                <div class="carousel-item">
                                    <img class="d-block w-100" src="hhtran/img/download2.png" alt="Second slide">
                                </div>
                                <div class="carousel-item">
                                    <img class="d-block w-100" src="hhtran/img/download3.png" alt="Third slide">
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleControls1" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleControls1" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>