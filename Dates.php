<?php
  class Dates {

    function __construct()
    {

    }

    function getSemesterYear() {

      $end_of_spring = date("Y-06-15");
    	$end_of_summer = date("Y-08-15");
    	$end_of_fall = date('Y-12-15');

      $semester_year = "";
      $today = date('Y-m-d');
      $year = substr($today , 0 , 4);

    	if( strtotime($today) < strtotime($end_of_spring)) {
        //in spring semester
        $semester_year = "Spring " . $year;
    	} else {
    		if(  strtotime($today) < strtotime($end_of_summer)) {
          //in summer session
          $semester_year = "Summer " . $year;
    		} else {
    			if( strtotime($today) < strtotime($end_of_fall) ) {
            //in fall semester
            $semester_year = "Fall " . $year;
    			} else {
            //after fall semester so in spring semester
            $semester_year = "Spring " . $year;
    			}
    		}
    	}//end of entire if statement
      return $semester_year;

    }//end of semester function

    function getCloseDate($term) {

      $today = date('Y-m-d');
      $year = substr($today , 0 , 4);

      $output = "";
      if(strpos($term, "Spring") !== false) {
        $temp = $year . "/7/15";
        $output = date($temp);
        $output = date($temp);
      } else if (strpos($term, "Summer") !== false) {
        $temp = $year . "/9/15";
        $output = date($temp);
      } else if (strpos($term, "Fall") !== false) {
        $temp = $year . "/12/31";
        $output = date($temp);
      }
      return $output;

    }



  }//end of class
?>
