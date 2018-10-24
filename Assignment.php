<?PHP
  class Assignment {

    //instance variables
    $assignmentID;
    $assingmentName;
    $description;
    $assingmentDate;
    $PDFLocation;
    $CourseID;
    $teacherID;

    //constructor
    function __construct($ID , $Name , $desc , $date , $pdf , $course , $teacher) {
      $assignmentID = $ID;
      $assingmentName = $name;
      $description = $desc;
      $assingmentDate = $date;
      $PDFLocation = $pdf;
      $CourseID = $course;
      $teacherID = $teacher;
    }
  }

?>
