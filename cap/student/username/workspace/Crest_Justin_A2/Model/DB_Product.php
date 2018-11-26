<?php
function get_products_by_category($category_id, $column, $type, $offset) {
    try{
        global $db;
        $query = "SELECT * FROM products
                  WHERE products.categoryID = :category_id
                  ORDER BY :column $type
                  LIMIT 8 OFFSET :offset";
        $statement = $db->prepare($query);

        $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        $statement->bindValue(':column', $column, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();  
        $products = $statement->fetchAll();
        $statement->closeCursor();
        return $products;
    }catch(Exception $e)
    {
        $error_message = $e->getMessage();
        include('../Error/database_error.php');
        exit();
    }
}
function get_product_count_by_category($category_id){
    try{
        global $db;
        $query = 'SELECT count(*) As Count FROM products
                  WHERE products.categoryID = :category_id';
        $statement = $db->prepare($query);
        $statement->bindValue(":category_id", $category_id);
        $statement->execute();
        $product = $statement->fetch();
        $statement->closeCursor();
        return $product;
    } catch (Exception $e) {
        $error_message = $e->getMessage();
        include('../Error/database_error.php');
        exit();
    }
    
}
function get_product($product_id) {
    
    try{
        global $db;
        $query = 'SELECT * FROM products
                  WHERE productID = :product_id';
        $statement = $db->prepare($query);
        $statement->bindValue(":product_id", $product_id, PDO::PARAM_INT);
        $statement->execute();
        $product = $statement->fetch();
        $statement->closeCursor();
        return $product;
    }catch(Exception $e)
    {
        $error_message = $e->getMessage();
        include('../Error/database_error.php');
        exit();
    }
}
