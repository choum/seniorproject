<?php
include 'header.php';
require 'database.php';

$isbn = filter_input(INPUT_GET, 'isbn', FILTER_SANITIZE_NUMBER_INT);
if($isbn == false || empty($isbn)){
    header("Location: index.php");
}

$db = DBAccess::getPDOConnection();
$query = "SELECT GROUP_CONCAT(DISTINCT course, ' ', courseTitle, ' (', credit, ')' SEPARATOR ';') AS courses, bookTitle, isbn13, price, publishDate, edition, length, description, BP.publisher, pubName, publisherID, GROUP_CONCAT(DISTINCT firstName, ' ', lastName SEPARATOR ';') AS authors FROM AUTHOR A, AUTHORBOOK AB, COURSEBOOK CB, COURSE C, (SELECT bookTitle, edition, isbn13, price, publishDate, length, description, P.publisher AS pubName, B.publisher, publisherID FROM BOOK AS B, PUBLISHER AS P WHERE B.publisher = publisherID) AS BP WHERE AB.book = isbn13 AND AB.author = authorID AND CB.book = isbn13 AND courseID = course AND isbn13 = :isbn";
$result = $db->prepare($query);
$result->bindValue(':isbn', $isbn);
$result->execute();

$book = $result->fetch();
$result->closeCursor();

$i = 0;

$authors = explode(';', $book['authors']);
$courses = explode(';', $book['courses']);

$authCount = count($authors);
$courseCount = count($courses);

?>

<html>
<head>
    <style>
        .isDisabled {
            cursor: not-allowed;
            opacity: 0.5;
            pointer-events: none;
        }
    </style>
    <meta charset="UTF-8">
    <title>Book Detail</title>
</head>
<body>

<div class="container">
    <h1>Book Detail</h1>
    <div class="row">
        <div class="col-lg-2 col-md-2"><img src="images/<?php echo $isbn ?>.jpg"></div>
        <div class="col-lg-10 col-md-10">
            <p>For course:
                <?php
                for($i = 0; $i < $courseCount; $i++)
                    if($i == $courseCount - 1){
                        echo $courses[$i];
                    }
                    else{
                        echo "$courses[$i], ";
                    }
                ?>
            </p>
            <p>Book Title: <?php echo $book['bookTitle']; ?></p>
            <p>Price: $<?php echo $book['price']; ?></p>
            <p>Authors:
                <?php
                for($i = 0; $i < $authCount; $i++)
                    if($i == $authCount - 1){
                        echo $authors[$i];
                    }
                    else{
                        echo "$authors[$i], ";
                    }
                ?>
            </p>
            <p>Publisher: <?php echo $book['pubName']; ?></p>
            <p>Edition: <?php echo $book['edition']; ?> edition (<?php echo date("Y/m/d", strtotime($book['publishDate'])); ?>)</p>
            <p>Length: <?php echo $book['length']; ?></p>
            <p>ISBN-13: <?php echo $book['isbn13']; ?></p>
        </div>
        <div class="col">
            <h2>Product Description:</h2>
            <p><?php echo $book['description']; ?></p>
        </div>
    </div>
</div>
</body>
</html>

