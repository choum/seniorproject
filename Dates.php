<?php
  class Dates {

    public $terms = ["Winter" , "Spring" , "Summer" , "Fall"];
    public $years = ["2015" , "2016" , "2017" , "2018" , "2019" , "2020" , "2021" , "2022" , "2023" , "2024" , "2025" ];

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

      $year = substr($term, -4);

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

    function countTerm($term) {
      $output = "0";
      if($term == "Winter") {
        $output = "1";
      } else if($term == "Spring") {
        $output = "2";
      } else if($term == "Summer") {
        $output = "3";
      } else if($term == "Fall") {
        $output = "4";
      }
      return $output;
    }



  }//end of class
?>
