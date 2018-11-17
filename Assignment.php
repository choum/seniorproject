<?PHP
  class Assignment {

    //instance variables
    public $ID;
    public $name;
    public $description;
    public $date;
    public $pdf;
    public $CourseID;
    public $teacherID;
    public $type;

    //constructor
    function __construct($aID , $aName , $desc , $aDate , $aPdf , $course , $teacher, $type) {
      $this->ID = $aID;
      $this->name = $aName;
      $this->description = $desc;
      $this->date = $aDate;
      $this->pdf = $aPdf;
      $this->CourseID = $course;
      $this->teacherID = $teacher;
      $this->type;
    }
  }

?>
