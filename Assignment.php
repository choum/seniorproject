<?PHP
  class Assignment {

    //instance variables
    public $id;
    public $name;
    public $description;
    public $date;
    public $pdf;
    public $courseID;
    public $teacherID;
    public $type;

    //constructor
    function __construct($aID , $aName , $desc , $adate , $apdf , $course , $teacher , $atype) {
      $this->id = $aID;
      $this->name = $aName;
      $this->description = $desc;
      $this->date = $adate;
      $this->pdf = $apdf;
      $this->courseID = $course;
      $this->teacherID = $teacher;
      $this->type = $atype;
    }
  }

?>
