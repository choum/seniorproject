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
            <?php if (!empty($currentAssignment) AND ! empty($currentCourse)): ?>
                <h4 class="card-text" style="text-align:center;"><?php echo $assignmentName; ?></h4>
                <p class="card-title">Course: <?php echo "$courseTitle $courseNumSection $courseTerm"; ?></p>
                <p class="card-title">Instructor: <?php echo $instructorName ?></p>
                <p class="card-title">Description: <?php echo $assignmentDescription ?></p>
                <!--<p class="card-title"><?php echo $assignmentType ?></p> -->
                <?php if($pdfLocation != ""): ?>
                    <p class="card-title"><a href="<?php echo $pdfLocation; ?>">PDF Link</a></p>
                <?php else: ?>
                    <p class="card-title">No PDF was submitted with this assignment.</p>
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
                                <td><a href="<?php echo $studentAssignmentDirectorys[$i]; ?>">Project Link</a></td>
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
