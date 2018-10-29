<?PHP
  class Assignment {

    //instance variables
    $ID;
    $name;
    $description;
    $date;
    $pdf;
    $CourseID;
    $teacherID;

    //constructor
    function __construct($aID , $aName , $desc , $adate , $apdf , $course , $teacher) {
      $ID = $aID;
      $name = $aname;
      $description = $desc;
      $date = $adate;
      $pdf = $apdf;
      $CourseID = $course;
      $teacherID = $teacher;
    }
  }

?>
