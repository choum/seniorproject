<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$product = filter_input(INPUT_POST, 'product', FILTER_SANITIZE_SPECIAL_CHARS);
$unitPrice = filter_input(INPUT_POST, 'price',FILTER_VALIDATE_FLOAT);
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
$creditCard = filter_input(INPUT_POST, 'credit', FILTER_DEFAULT);
$ccNumber = filter_input(INPUT_POST, 'ccNumber', FILTER_VALIDATE_INT);
$ccNumberRepeat = filter_input(INPUT_POST, 'ccNumberRepeat', FILTER_VALIDATE_INT);
$quantity = filter_input(INPUT_POST, 'quantity', FILTER_DEFAULT);

if(empty(trim($product))){
    $error[] = "Please insert a product name.";
}

if(empty(trim($name))){
    $error[] = "Please insert a name.";
}

if($unitPrice === FALSE){
    $error[] = "Please insert a price.";
}

if(!isset($_POST['shipping']) || empty(trim($_POST['shipping']))){
    $error[] = "Please insert a shipping address.";
}
else{
    $shipping = $_POST['shipping'];
}

if($ccNumber === FALSE){
    $error[] = "Please insert a credit card number.";
}

if($ccNumberRepeat === FALSE){
    $error[] = "Please confirm the credit card number.";
}

if($ccNumber != $ccNumberRepeat){
    $error[] = "Credit card number does not match";
}

if(isset($error)){
    include('index.php');
    exit();
}

include 'header.php';

switch($creditCard){
    case "visa":
        $creditType = "Visa";
        break;
    case "american":
        $creditType = "American Express";
        break;
    case "discover":
        $creditType = "Discover";
        break;
    case "master":
        $creditType = "MasterCard";
        break;
}

switch($quantity){
    case "one":
        $quantityNum = 1;
        break;
    case "two":
        $quantityNum = 2;
        break;
    case "three":
        $quantityNum = 2;
        break;
    case "four":
        $quantityNum = 4;
        break;
    case "five":
        $quantityNum = 5;
        break;
    case "six":
        $quantityNum = 6;
        break;
}


$price = $unitPrice * $quantityNum;

?>

<body>
    <div class="container">
        <div class="page-header">
            <h1>Results</h1>

            <p><?php echo $name ?>, you placed an order with <?php echo $creditType ?> for a total of $<?php echo number_format($price, 2) ?>.</p>
            <p>Your order will be shipped to</p>
            <?php echo nl2br($shipping) ?>
        </div>
    </div>

</body>






