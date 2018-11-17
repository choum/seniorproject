<?php

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
        public $coursesEnrolled;
        public $dateCreated;
        public $lastLoginDate;

        //constructor with all the info
        function __construct($userID, $user, $password, $first, $last, $title, $bio, $email,
                $image, $linked, $site, $role, $suspended, $coursesEnrolled, $create, $lastlog)
        {
            $this->id = $userID;
            $this->username = $user;
            $this->password = $password;
            $this->firstName = $first;
            $this->lastName = $last;
            $this->title = $title;
            $this->email = $email;
            $this->bio = $bio;
            $this->imageLink = $image;
            $this->linkedin = $linked;
            $this->website = $site;
            $this->role = $role;
            $this->suspended = $suspended;
            $this->coursesEnrolled = $coursesEnrolled;
            $this->dateCreated = $create;
            $this->lastLoginDate = $lastlog;
        }

    }

?>
