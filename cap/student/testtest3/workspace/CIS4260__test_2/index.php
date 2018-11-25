<?php
include 'header.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(!isset($product)) {$product = '';}
if(!isset($unitPrice)) {$unitPrice = '';}
if(!isset($name)) {$name = '';}
if(!isset($shipping)) {$shipping = '';}
if(!isset($ccNumber)) {$ccNumber = '';}
if(!isset($ccNumberRepeat)) {$ccNumberRepeat = '';}
if(!isset($creditCard)) {$creditCard = 'visa';}
if(!isset($quantity)) {$quantity = 'one';}
?>
    <body>
    <div class="container">
        <div class="page-header">
            <h1>Please Insert the Information</h1>
        </div>
        <?php
            if(!empty($error)){
                echo "<div class=\"alert alert-danger\">";
               for($x = 0; $x < sizeof($error); $x++){
                   echo "<p>$error[$x]</p>";
               }
            }
            echo "</div>";
        ?>
        </div>
    </div>
        <div class="container">
        <form autocomplete="off" method="post" action="display.php" id="order">
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="productName">Product:</label>
                <div class="col-md-10">
                    <input class="form-control" id="productName" name="product" type="text" value="<?php echo $product ?>">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="productQuantity">Quantity:</label>
                <div class="col-md-10">
                    <select class="form-control" id="productQuantity" name="quantity">
                        <option value="one" <?php if($quantity == 'one') {echo ' selected ';} ?>>1</option>
                        <option value="two" <?php if($quantity == 'two') {echo ' selected ';} ?>>2</option>
                        <option value="three" <?php if($quantity == 'three') {echo ' selected ';} ?>>3</option>
                        <option value="four" <?php if($quantity == 'four') {echo ' selected ';} ?>>4</option>
                        <option value="five" <?php if($quantity == 'five') {echo ' selected ';} ?>>5</option>
                        <option value="six" <?php if($quantity == 'six') {echo ' selected ';} ?>>6</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="unitPrice">Unit Price:</label>
                <div class="col-md-10 input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">$</div>
                    </div>
                    <input class="form-control" id="unitPrice" name="price" type="number" step="any" value="<?php echo $unitPrice; ?>">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="purchaser">Name:</label>
                <div class="col-md-10">
                    <input class="form-control" id="purchaser" name="name" type="text" value="<?php echo $name ?>">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="shippingAddress">Shipping Address:</label>
                <div class="col-md-10">
                    <textarea class="form-control" name="shipping" id="shippingAddress" rows="3"><?php echo $shipping; ?></textarea>
                </div>
            </div>

            <div class="form-group row">
            <label class="col-md-2 col-form-label" for="ccBoxes">Credit Card:</label>
                <div class="col-md-10" id="ccBoxes">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="credit" id="visa" value="visa" <?php if($creditCard == 'visa') {echo ' checked ';} ?>>
                            Visa
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="credit" id="masterCard" value="master" <?php if($creditCard == 'master') {echo ' checked ';} ?>>
                            Master Card
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="credit" id="americanExpress" value="american" <?php if($creditCard == 'american') {echo ' checked ';} ?>>
                            American Express
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="credit" id="discover" value="discover" <?php if($creditCard == 'discover') {echo ' checked ';} ?>>
                            Discover
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="ccNumberInput" class="col-2 col-form-label">Credit Card Number:</label>
                <div class="col-10">
                    <input autocomplete="new-password" class="form-control" name="ccNumber" type="password" value="<?php echo $ccNumber ?>" id="ccNumberInput">
                </div>
            </div>

            <div class="form-group row">
                <label for="ccNumberRepeatInput" class="col-2 col-form-label">Repeat Credit Card Number:</label>
                <div class="col-10">
                    <input class="form-control" name="ccNumberRepeat" type="password" value="<?php echo $ccNumberRepeat ?>" id="ccNumberRepeatInput">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        </div>
    </body>
</html>

