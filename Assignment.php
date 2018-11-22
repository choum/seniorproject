<?PHP
  class Assignment {

    //instance variables
<<<<<<< HEAD
    public $ID;
=======
    public $id;
>>>>>>> master
    public $name;
    public $description;
    public $date;
    public $pdf;
    public $CourseID;
    public $teacherID;
    public $type;

    //constructor
<<<<<<< HEAD
    function __construct($aID , $aName , $desc , $aDate , $aPdf , $course , $teacher, $type) {
      $this->ID = $aID;
      $this->name = $aName;
      $this->description = $desc;
      $this->date = $aDate;
      $this->pdf = $aPdf;
      $this->CourseID = $course;
      $this->teacherID = $teacher;
      $this->type;
=======
    function __construct($aID , $aName , $desc , $adate , $apdf , $course , $teacher , $atype) {
      $this->id = $aID;
      $this->name = $aName;
      $this->description = $desc;
      $this->date = $adate;
      $this->pdf = $apdf;
      $this->CourseID = $course;
      $this->teacherID = $teacher;
      $this->type = $atype;
>>>>>>> master
    }
  }

?>
