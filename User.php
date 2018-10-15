<?php 

    class User {

        $userName = "user";
        $firstName = "first";
        $lastName = "last";
        $title = "Dr.";
        $bio = "This is a sample bio.";
        $imageLink = "/123.jpg";
        $linkedin = "abc123";
        $website = "www.google.com";
        $role = "Instructor";
        $suspended = 0;
        $created = "11/11/2001";
        $lastLog = "11/11/2017";


        function __construct() {

        }

        function __construct($name) {

            $lastName = $name;
        }

        function __construct($usr , $first , $last , $t , $b , $image , $linked , $site , $r , $s , $create , $last) {

            $userName = $user;
            $firstName = $first;
            $lastName = $last;
            $title = $t;
            $bio = $b;
            $imageLink = $iamge;
            $linkedin = $linked;
            $website = $site;
            $role = $r;
            $suspended = $s;
            $created = $create;
            $lastLog = $last;
        }
    }

?>