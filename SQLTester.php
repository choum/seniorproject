<?php
//MAKE SURE FIRST NAME & LAST NAME COLUMN DON'T HAVE A SPACE IN THEM BEFORE TESTING

include_once("./SQLHelper.php");

$querys = new SQLHelper();
//for testing add user function
$return = $querys->addUser("username", "password", "firstName", "lastName", "title", 1, 0, date("Y/m/d"),NULL, NULL, NULL. NULL, NULL);
echo $return;
//for testing update user function
$return = $querys->updateUser(992, "AH", "AB", "AS", "AF");
echo "<br/>" . $return;
//for testing add instructor function, which performs the same as user currently
$return = $querys->addInstructor("username", "password", "firstName", "lastName", "title", 1, 0, date("Y/m/d"));
echo "<br/>" . $return;
//for testing update instructor function
$return = $querys->updateInstructor(993, "firstName1", "lastName2");
echo "<br/>" . $return;
//for testing create course function
$return = $querys->addCourse("Intro to Accounting", 127, 012, "Fall 2018", "Description", 0, 20, 991, 993);
echo "<br/>" . $return;
//for testing update course function CURRENTLY NOT WORKING
$return = $querys->updateCourse(1234, "Apple", 123, 02, "Fall 2019", 991, 993);
echo "<br/>" . $return;
//for testing get instructors function
$return = $querys->getInstructors();
if(is_array($return)){
    foreach($return as $instructor):
        echo "<br/>" . $instructor[0] . " " . $instructor[1] . " " . $instructor[2];
    endforeach;
}
else
    echo "<br/>" . $return;
//for testing get courses function
$return = $querys->getCourses();
if(is_array($return)){
    foreach($return as $course):
        echo "<br/>";
        foreach($course as $key => $column):
            if(is_int($key) == FALSE)
                echo $column . " ";
        endforeach;
    endforeach;
}
else
    echo "<br/>" . $return;
//for testing get assignment list. Needs a complimentary create & update assignment query
//In normal cases it would use a presupplied courseid, from either getCourses or a single course fetch
//Assignment table needs to be renamed for this to work
$return = $querys->getAssignmentList(1113);
if(is_array($return)){
    foreach($return as $assignment):
        echo "<br/>";
        foreach($assignment as $key => $column):
            if(is_int($key) == FALSE)
                echo $column . " ";
        endforeach;
    endforeach;
}
else
    echo "<br/>" . $return;

$return = $querys->getUserAuth("password");
echo $return;