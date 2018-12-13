<?php
    /*
     * Created By: Nat Rivera updated by Justin Crest
     * Description: This file is used to create a class object for users, which is used
     * whenever retrieving information about a user or when creating a new account, which is 
     * done on account registration and on the creation of an instructor.
     */
    class User
    {

        public $id;
        public $username;
        public $password;
        public $firstName;
        public $lastName;
        public $title;
        public $bio;
        public $email;
        public $imageLink;
        public $linkedin;
        public $website;
        public $role;
        public $suspended;
        public $courses;
        public $dateCreated;
        public $lastLoginDate;

        //constructor with all the info
        function __construct($user, $password, $first, $last, $title, $bio,
                $email, $image, $linked, $site, $role, $suspended, $create, $lastlog)
        {

            $this->username = $user;
            $this->password = $password;
            $this->firstName = $first;
            $this->lastName = $last;
            $this->title = $title;
            $this->bio = $bio;
            $this->email = $email;
            $this->imageLink = $image;
            $this->linkedin = $linked;
            $this->website = $site;
            $this->role = $role;
            $this->suspended = $suspended;
            $this->dateCreated = $create;
            $this->lastLoginDate = $lastlog;
        }

        function setID($userID)
        {
            $this->id = $userID;
        }
        
        function setCourses($courses)
        {
            $this->courses = $courses;
        }

    }

?>
