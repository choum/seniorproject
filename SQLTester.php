<?php
//MAKE SURE FIRST NAME & LAST NAME COLUMN DON'T HAVE A SPACE IN THEM BEFORE TESTING

include_once("./SQLHelper.php");

$querys = new SQLHelper();
/*//for testing add user function
$returna = $querys->addUser(991, "username", "password", "firstName", "lastName", "title", 1, 0, date("Y/m/d"),NULL, NULL, NULL. NULL, NULL);
echo $returna;
//for testing update user function
$returnb = $querys->updateUser(992, "AH", "AB", "AS", "AF");
echo "<br/>" . $returnb;
//for testing add instructor function, which performs the same as user currently
$returnc = $querys->addInstructor(993, "username", "password", "firstName", "lastName", "title", 1, 0, date("Y/m/d"));
echo "<br/>" . $returnc;
//for testing update instructor function
$returnc = $querys->updateInstructor(993, "firstName1", "lastName2");
echo "<br/>" . $returnc;
//for testing create course function
$returnd = $querys->addCourse(1113, "Intro to Accounting", 127, 012, "Fall 2018", "Description", 0, 20, 991, 993);
echo "<br/>" . $returnd;
//for testing update course function CURRENTLY NOT WORKING
$returne = $querys->updateCourse(1234, "Apple", 123, 02, "Fall 2019", 991, 993);
echo "<br/>" . $returne;
//for testing get instructors function
$returnf = $querys->getInstructors();
if(is_array($returnf)){
    foreach($returnf as $instructor):
        echo "<br/>" . $instructor[0] . " " . $instructor[1] . " " . $instructor[2];
    endforeach;
}
else
    echo "<br/>" . $returnf;
//for testing get courses function
$returng = $querys->getCourses();
if(is_array($returng)){
    foreach($returng as $course):
        echo "<br/>";
        foreach($course as $key => $column):
            if(is_int($key) == FALSE)
                echo $column . " ";
        endforeach;
    endforeach;
}
else
    echo "<br/>" . $returng;
//for testing get assignment list. Needs a complimentary create & update assignment query
//In normal cases it would use a presupplied courseid, from either getCourses or a single course fetch
//Assignment table needs to be renamed for this to work
$returnh = $querys->getAssignmentList(1113);
if(is_array($returnh)){
    foreach($returnh as $assignment):
        echo "<br/>";
        foreach($assignment as $key => $column):
            if(is_int($key) == FALSE)
                echo $column . " ";
        endforeach;
    endforeach;
}
else
    echo "<br/>" . $returnh;
 * 
 */

$return = $querys->getUserAuth("password");
if(is_array($return))
    print_r($return);
else
    echo $return;