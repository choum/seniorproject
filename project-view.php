<?php
    require_once('project-controller.php');
    require('header.php');
?>

<html>

    <head>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">


        <style>
            #search {
                background-color: #fff;
            }

            .card-title {
                margin-bottom: 5px;
            }

            .card-header+.list-group .list-group-item:first-child {
                border-top: 1px solid rgba(0, 0, 0, .125);
            }

            a {
                color: #01426A;
            }

            label {
                font-size: 13pt;
            }

            .card-title {
                text-align: center;
            }
        </style>
    </head>

    <body>
        <div class="card">
            <div class="card-header">
                <a href="/" ><i class="fas fa-arrow-left"></i> Back</a>
            </div>
            <div class="card-body" style="padding-top: 3px;">
            <?php if ($currentAssignment->id != NULL AND $currentCourse->courseID != NULL): ?>
                <h4 class="card-text" style="text-align:center;"><?php echo $assignmentName; ?></h4>
                <table class="table" style="margin: 0 auto;">
                    <tr>
                        <th>Course</th>
                        <td><?php echo "$courseTitle $courseNumSection $courseTerm"; ?></td>
                    </tr>
                    <tr>
                        <th>Instructor</th>
                        <td><?php echo $instructorName ?></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td><?php echo $assignmentDescription ?></td>
                    </tr>
                </table>
                <?php if($pdfLocation != ""): ?>
                    <p class="card-title"><a href="<?php echo $pdfLocation; ?>" target="_blank">PDF Link</a></p>
                <?php endif; ?>

                <hr/>
                <?php if (!empty($studentIDs)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Upload Date</th>
                                <th>Link to Project Page</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php for ($i = 0; $i < $count; $i++): ?>
                            <tr>
                                <td><?php echo $studentNames[$i]; ?></td>
                                <td><?php echo $studentSubmissionDates[$i]; ?></td>
                                <td><a href="<?php echo $studentAssignmentDirectorys[$i]; ?>" target="_blank">Project Link</a></td>
                                 <!--echo('<td><a href="cap/' . $user->username . '/' . $current_course->courseID . '/' . $current_assignment->id . '">Project</a></td>');-->
                            </tr>
                        <?php endfor; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <h6 class="card-text" style="text-align:center;">There are no student submissions for this assignment.</h6>
                <?php endif; ?>
            <?php else: ?>
                <h4 class="card-text" style="text-align:center;">Course/Assignment could not be retrieved. Please return to previous page and try again.</h4>
            <?php endif; ?>
            </div>
        </div>
    </body>

</html>
