<?php

    include 'private/CreateFTP.php';
    include 'private/CreateDB.php';


    $ftp = new CreateFTP();

    $ftp->createUser('t7','t7');

    $createDB =  new CreateDB();
    $createDB::createDBUser('b1', 'b1');




?>