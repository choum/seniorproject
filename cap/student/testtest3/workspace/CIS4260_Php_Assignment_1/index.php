<?php
    include ('header.php');
    require ('database.php');

    $orderBy = filter_input(INPUT_GET, 'order', FILTER_SANITIZE_STRING);
    $sortBy = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_NUMBER_INT);
    $opposite = 2;

    if($sortBy === false || empty($sortBy) || $sortBy == 1){
        $sortBy = 1;
        $opposite = 2;
    }
    else{
        $sortBy = 2;
        $opposite = 1;
    }

    if($orderBy === false || empty($orderBy)){
        $orderBy = "course";
    }

    switch($sortBy){
        case '1':
            $sort = 'ASC';
            break;
        case '2':
            $sort = 'DESC';
            break;
    }


    $db = DBAccess::getPDOConnection();
    $test = $db->query("SELECT * FROM COURSE, BOOK, COURSEBOOK WHERE courseID = course AND isbn13 = book");
    $total = $test->rowCount();
    $test->closeCursor();
    $limit = 6;

    $numPages = ceil($total/$limit);


    $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);

    if($page === false || empty($page)){
        $page = 1;
    }
    elseif($page > $numPages){
        $page = 1;
    }
    $offset = ($page-1)*$limit;

    if($page > 1) { $previous = '<li class="page-item"><a class="page-link" href="?page='.($page-1).'&order='.$orderBy.'&sort='.$sortBy.'">Previous</a></li>'; }
    else{ $previous = '<li class="page-item"><a class="page-link isDisabled" href="#">Previous</a></li>'; }

    if($page < $numPages){$next = '<li class="page-item"><a class="page-link" href="?page='.($page+1).'&order='.$orderBy.'&sort='.$sortBy.'">Next</a></li>';}
    else{$next = '<li class="page-item"><a class="page-link isDisabled" href="#">Next</a></li>';}



    $db = DBAccess::getPDOConnection();
    $query = "SELECT courseID, courseTitle, isbn13, CB.course, book, bookTitle, price, combo 
    FROM COURSE AS C, BOOK AS B, COURSEBOOK AS CB 
    LEFT OUTER JOIN (SELECT course, GROUP_CONCAT(isbn13) AS combo 
    FROM COURSE AS C2, COURSEBOOK CB2, BOOK 
    AS B2 WHERE book = isbn13 AND CB2.course = courseID 
    GROUP BY course HAVING COUNT(course) > 1) 
    AS comboISBN ON CB.course = comboISBN.course 
    WHERE courseID = CB.course AND isbn13 = book 
    GROUP BY CB.course ORDER BY $orderBy $sort 
    LIMIT :limitQ OFFSET :offsetQ";
    $result = $db->prepare($query);
    $result->bindValue(':limitQ', (int) $limit, PDO::PARAM_INT);
    $result->bindValue(':offsetQ', (int) $offset, PDO::PARAM_INT);
    $result->execute();
    $data = $result->fetchAll();
    $result->closeCursor();


?>

<!DOCTYPE html>

<html>
    <head>
        <style>
            .isDisabled {
                cursor: not-allowed;
                opacity: 0.5;
                pointer-events: none;
            }
            .isMultiple td {
                border-top: none;
            }

        </style>
        <meta charset="UTF-8">
        <title>Book List</title>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <h1>Book List</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope ="col"><a href="?page=1&order=course&sort=<?php echo $opposite;?>">Course #</a></th>
                            <th scope ="col">Course Title</th>
                            <th scope ="col">Book Image</th>
                            <th scope ="col">Book Title</th>
                            <th scope ="col"><a href="?page=1&order=price&sort=<?php echo $opposite;?>">Price</a></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach($data as $row){
                    ?>
                            <?php
                            $dbBookTitle = DBAccess::getPDOConnection();

                            if ($row['combo'] != null && $orderBy == "course") {
                                $eachISBN = explode(',', $row['combo']);
                            ?>
                                <th scope="row"><a href="http://www.cpp.edu/~cba/computer-information-systems/curriculum/courses.shtml" target="_blank"><?php echo $row['courseID'];?></a></th>
                                <td><?php echo $row['courseTitle'];?></td>
                                <td colspan="3">
                                    <table>
                            <?php

                                for($i = 0; $i < count($eachISBN); $i++){
                                    echo '<tr class="isMultiple"><td><a href="detail.php?isbn='.$eachISBN[$i].'"><img src="images/'.$eachISBN[$i].'.jpg"></a></td>';
                                    $book = $dbBookTitle->query("SELECT bookTitle, price FROM BOOK WHERE isbn13 = $eachISBN[$i]");
                                    $bookArray = $book->fetch();
                                    $book->closeCursor();
                                    echo '<td width="510px">'.$bookArray['bookTitle'].'</td>';
                                    echo '<td width="74px">$'.$bookArray['price'].'</td></tr>';


                                }

                                ?>
                                </td>
                                    </table>



                                <?php
                            }
                            elseif($row['combo'] != null){ $eachISBN = explode(',', $row['combo']);
                                for($i = 0; $i < count($eachISBN); $i++){
                                    ?>
                                    <tr>
                                        <th scope="row"><a href="http://www.cpp.edu/~cba/computer-information-systems/curriculum/courses.shtml" target="_blank"><?php echo $row['courseID'];?></a></th>
                                        <td><?php echo $row['courseTitle'];?></td>
                                    <?php

                                    echo '<td><a href="detail.php?isbn='.$eachISBN[$i].'"><img src="images/'.$eachISBN[$i].'.jpg"></a></td>';
                                    $book = $dbBookTitle->query("SELECT bookTitle, price FROM BOOK WHERE isbn13 = $eachISBN[$i]");
                                    $bookArray = $book->fetch();
                                    $book->closeCursor();
                                    echo '<td width="510px">'.$bookArray['bookTitle'].'</td>';
                                    echo '<td width="74px">$'.$bookArray['price'].'</td></tr>';
                                }

                            }
                            else{
                            ?>
                            <th scope="row"><a href="http://www.cpp.edu/~cba/computer-information-systems/curriculum/courses.shtml" target="_blank"><?php echo $row['courseID'];?></a></th>
                            <td><?php echo $row['courseTitle'];?></td>
                            <td><a href="detail.php?isbn=<?php echo $row['isbn13'] ?>"><img src="images/<?php echo $row['isbn13'];?>.jpg"></a></td>
                            <td><?php echo $row['bookTitle'];?></td>
                            <td>$<?php echo $row['price'];?></td>
                            <?php
                            }
                            ?>

                        </tr>
                    <?php
                        }
                    ?>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <?php echo $previous; ?>
                        <?php
                            $listPage = 1;
                            while($listPage <= $numPages){
                                if($listPage == $page){
                        ?>
                        <li class="page-item"><a class="page-link isDisabled" href="?page=<?php echo $listPage ?>&order=<?php echo $orderBy ?>&sort=<?php echo $sortBy; ?>"><?php echo $listPage ?></a></li>

                        <?php
                        }
                        else{
                        ?>
                            <li class="page-item"><a class="page-link" href="?page=<?php echo $listPage ?>&order=<?php echo $orderBy ?>&sort=<?php echo $sortBy; ?>"><?php echo $listPage ?></a></li>


                            <?php
                           }
                                $listPage++;
                            } ?>
                        <?php echo $next; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </body>
</html>