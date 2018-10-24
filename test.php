<?php

    include 'private/CreateFTP.php';
    include 'private/CreateDB.php';


    $ftp = new CreateFTP();

    $ftp->createUser('t8','t8');

//    $createDB =  new CreateDB();
//    $createDB::createDBUser('t7', 't7');




?>