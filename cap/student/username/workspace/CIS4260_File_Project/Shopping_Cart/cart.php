<?php
// Add an item to the cart
function add_item($product_id, $quantity) {

    if ($quantity < 1){ return; }
    // If item already exists in cart, update quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $quantity += $_SESSION['cart'][$product_id]['qty'];
        update_item($product_id, $quantity);
        return;
    }
    $product = get_product($product_id);
    $name = $product['productName'];
    $price = $product['listPrice'];
    $discountPercent = $product['discountPercent'];
    $discountAmount = $price * ($discountPercent/100);
    $cost = $price - $discountAmount;
    $total = $cost * $quantity;
    // Add item to session cart
    $item = array(
        'name' => $name,
        'price' => $price,
        'discountPercent' => $discountPercent,
        'discountAmount' => $discountAmount,
        'cost' => $cost,
        'qty'  => $quantity,
        'total' => $total
    );
    $_SESSION['cart'][$product_id] = $item;
}

// Update an item in the cart
function update_item($key, $qty) {
    $quantity = (int) $qty;
    if (isset($_SESSION['cart'][$key])) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$key]);
        } else {
            $_SESSION['cart'][$key]['qty'] = $quantity;
            $total = $_SESSION['cart'][$key]['cost'] * $_SESSION['cart'][$key]['qty'];
            $_SESSION['cart'][$key]['total'] = $total;
        }
    }
}

function get_savings() {
    $discountAmount = 0;
    foreach($_SESSION['cart'] as $item) {
        $discountAmount += $item['discountAmount'];
    }
    return $discountAmount;
}
// Get cart subtotal
function get_subtotal() {
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['total'];
    }
    return $subtotal;
}

function get_checkout_info(){
    $subtotal = get_subtotal();
    $discount = get_savings();
    $taxPercent = 9.5;
    $taxAmount = $subtotal * ($taxPercent/100);
    $grandTotal = $subtotal + $taxAmount;
    $finalDisplay = array('Subtotal' => $subtotal, 'Amount Saved' => $discount,
        'Tax' => $taxPercent, 'Tax Amount' => $taxAmount, 'Grand Total' => $grandTotal);
    return $finalDisplay;
}
?>