<?php include 'View/header.php'; 
// Start session management with a persistent cookie
$lifetime = 60 * 60 * 24 * 1;    // 1 day in seconds
session_set_cookie_params($lifetime, '/');
 session_start(); 
?>

<main>
    <h1>Main Menu</h1>
    <ul>
        <li>
            <a href="Product_Catalog/index.php?category_id=1">Animal Food</a>
        </li>
        <li>
            <a href="Product_Catalog/index.php?category_id=2">Animal Toys</a>
        </li>
        <li>
            <a href="Product_Catalog/index.php?category_id=3">Miscellaneous</a>
        </li>
    </ul>
</main>
<?php include 'view/footer.php'; ?>
