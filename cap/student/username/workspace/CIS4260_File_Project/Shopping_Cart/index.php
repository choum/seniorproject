<?php
include_once('cart.php');
require('../Model/DB.php');
require('../Model/DB_Product.php');
session_start(); 
 
$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if($action == NULL OR $action == FALSE)
{
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}
if ($action === 'add')
{
    $product_id = filter_input(INPUT_POST, "product_id", FILTER_VALIDATE_INT);
    if ( $product_id != FALSE AND $product_id != NULL)
    {
        $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);
        if ($quantity != FALSE AND $quantity != NULL AND $quantity > 0)
        {
            add_item($product_id, $quantity);
 
            include('cart_view.php');     

        }
        else
        {
            $error = "Missing or invalid quantity";
            include('../Error/error.php');
        }
    }
    else
    {
        $error = "Missing or invalid product id.";
        include('../Error/error.php');
    }
}
else if ($action === 'update')
{
    $new_qty_list = filter_input(INPUT_POST, 'newqty', FILTER_DEFAULT, 
                                     FILTER_REQUIRE_ARRAY);
    $delete_item_list = filter_input(INPUT_POST, 'del', FILTER_DEFAULT,
                                        FILTER_REQUIRE_ARRAY);
    
    foreach($new_qty_list as $key => $qty) {
            if ($_SESSION['cart'][$key]['qty'] != $qty) {
                update_item($key, $qty);
            }
            if ($qty != 0 AND isset($delete_item_list[$key]))
            {
                update_item($key, 0);
            }
    
    }
    
    include('cart_view.php');
}
else if($action == 'empty_cart')
{
    unset($_SESSION['cart']);
    include('cart_view.php');
}
else if($action == 'checkout')
{
    include('checkout.php');
    unset($_SESSION['cart']);
}
else
{
    include('cart_view.php');
}
?>

