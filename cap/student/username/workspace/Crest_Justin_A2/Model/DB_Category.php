<?php
function get_categories() {
    try{
        global $db;
        $query = 'SELECT * FROM categories
                  ORDER BY categoryID';
        $statement = $db->prepare($query);
        $statement->execute();
        $categories = $statement->fetchAll();
        $statement->closeCursor();
        return $categories;  
    } catch (PDOException $ex) {
        $error_message = $e->getMessage();
        include('../Error/database_error.php');
        exit();
    }
      
}

function get_category_name($category_id) {
    try{
        global $db;
        $query = 'SELECT * FROM categories
                  WHERE categoryID = :category_id';    
        $statement = $db->prepare($query);
        $statement->bindValue(':category_id', $category_id);
        $statement->execute();    
        $category = $statement->fetch();
        $statement->closeCursor();    
        $category_name = $category['categoryName'];
        return $category_name;
    } catch (PDOException $ex) {
        $error_message = $e->getMessage();
        include('../Error/database_error.php');
        exit();
    }
    
}
?>
