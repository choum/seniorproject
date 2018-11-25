<?php
require('../Model/DB.php');
require('../Model/DB_Product.php');
require('../Model/DB_Category.php');

 session_start(); 
 
$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if ($action == NULL)
{
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if ($action == NULL)
    {
        $action = 'list_products';
    }
}

if ($action == 'list_products')
{
    $category_id = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);
    $sort = filter_input(INPUT_GET, 'sort', FILTER_VALIDATE_INT);
    $page_number = filter_input(INPUT_GET, 'page_number', FILTER_VALIDATE_INT);
    if ($category_id == NULL || $category_id == FALSE)
    {
        $category_id = 1;
    }
    if($sort == NULL || $sort == FALSE)
    { 
        $sort = 11;
    }
    if($page_number == NULL || $page_number == FALSE)
    {
        $page_number = 1;
    }
    
    $column = (int)($sort/10);
    $type = $sort%10;
    $offset = ($page_number-1)*8;
    if($column != (int)1 && $column != (int)4 && $column != (int)6) { $column = 1; $sort = 11; }
    if($type === (int)1){ $type = "ASC"; }
    else if($type === (int)2){ $type = "DESC"; }
    else{ $type = ""; }
    
    $categories = get_categories();
    $category_name = get_category_name($category_id);
    $products = get_products_by_category($category_id, $column, $type, $offset);
    $product_count = get_product_count_by_category($category_id)['Count'];
    include('product_list.php');
}
else if ($action == 'view_product')
{
    $product_id = filter_input(INPUT_GET, 'product_id', FILTER_VALIDATE_INT);
    if ($product_id == NULL || $product_id == FALSE || is_int($product_id) == FALSE)
    {
        $error = 'Missing or incorrect product id.';
        include('../errors/error.php');
    }
    else
    {
        $categories = get_categories();
        $product = get_product($product_id);

        // Get product data
        $code = $product['productCode'];
        $name = $product['productName'];
        $detail = $product['description'];
        $list_price = $product['listPrice'];
        $discount_percent = $product['discountPercent'];
        $image = $product['imageName'];
        //Calculate discounted price
        $discount_amount = round($list_price * ($discount_percent / 100.0), 2);
        $unit_price = $list_price - $discount_amount;

        // Format the calculations
        $discount_amount_f = number_format($discount_amount, 2);
        $unit_price_f = number_format($unit_price, 2);

        // Get image URL and alternate text
        $image_filename = '../Images/' . $image . '.png';
        $image_alt = 'Image: ' . $image . '.png';

        include('product_detail.php');
    }
}
?>