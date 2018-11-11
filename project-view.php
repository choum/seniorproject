<?php
  require('header.php');

  $db = new SQLHelper();

  $current_course = $db->getCourse($course);
  $current_assignment = $db->getAssignment($assignment);
  $current_instructor = $db->getUserByID($current_course->teacherID);
  $students = [];
  $students_string = $db->getStudentsOfAssignment($assignment);
  foreach ($students_string as $student) {
    $temp = $db->getUserByID($student[0]);
    array_push($students , $temp);
  }
  $instructor_name = $current_instructor->firstName . " " . $current_instructor->lastName;
  $pdfLocation = "/cap/" . $current_instructor->username . "/" . $current_course->courseID . "/"  . $current_assignment->pdf;

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
    <div class="card-body">
      <h4 class="card-text" style="text-align:center;"><?php if (!empty($current_assignment)) { echo $current_assignment->name; } ?></h4>
      <p class="card-title"><?php if (!empty($current_course )) { echo $current_course->courseTitle; } ?></p>
      <p class="card-title"><?php if (!empty($current_course )) { echo "CIS " . $current_course->courseNumber . "." . $current_course->courseSection; } ?></p>
      <p class="card-title"><?php if (!empty($current_course )) { echo $current_course->term; } ?></p>
      <p class="card-title"><?php if (!empty($current_course )) { echo $instructor_name; } ?></p>
      <p class="card-title"><?php if (!empty($current_assignment )) { echo $current_assignment->description; } ?></p>
      <p class="card-title"><?php if (!empty($current_assignment )) { echo $current_assignment->type; } ?></p>
      <p class="card-title"><?php if (!empty($current_assignment )) { echo $pdfLocation; } ?></p>
      <hr/>
      <table class="table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Link to Project Page</th>
          </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($students)) {
          foreach ($students as $user) {
            echo('<td>' . $user->firstName . " " . $user ->lastName . '</td>');
            echo('<td><a href="cap/' . $user->username . '/' . $current_course->courseID . '/' . $current_assignment->id . '">Project</a></td>');
          }
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>
