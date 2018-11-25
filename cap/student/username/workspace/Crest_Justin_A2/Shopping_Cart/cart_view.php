<?php
include '../View/header.php';

?>

<main>
    <h1>Your Cart</h1>
    <?php if (empty($_SESSION['cart']) || count($_SESSION['cart']) == 0) :  ?>
        <p>There are no items in your cart.</p>
    <?php else: ?>
        <form action="./index.php" method="post">
            <input type="hidden" name="action" value="update">
            <table>
                <tr id="cart_header">
                    <th class="left">Item</th>
                    <th class="right">Item Cost</th>
                    <th class="right">Discount %</th>
                    <th class="right">Your Cost</th>
                    <th class="right">Quantity</th>
                    <th class="right">Item Total</th>
                    <th class="right">Delete</th>
                </tr>

            <?php foreach ($_SESSION['cart'] as $key => $item) :
                $price = number_format($item['price'], 2);
                $discountPercent = number_format($item['discountPercent'],2);
                $cost = number_format($item['cost'], 2);
                $total = number_format($item['total'], 2);  ?>
                <tr>
                    <td>
                        <?php echo $item['name']; ?>
                    </td>
                    <td class="right">
                        $<?php echo $price; ?>
                    </td>
                    <td class="right">
                        <?php echo $discountPercent ?>%
                    </td>
                    <td class="right">
                        $<?php echo $cost; ?>
                    </td>
                    <td class="right">
                        <input type="text"  name="newqty[<?php echo $key; ?>]"
                            value="<?php echo $item['qty']; ?>" style="width: 30px">
                    </td>
                    <td class="right">
                        $<?php echo $total; ?>
                    </td>
                    <td style="text-align: center">
                        <input type="checkbox" name="del[<?php echo $key; ?>]" value="ON"/>
                    </td>
                </tr>
            <?php endforeach; ?>
                <tr id="cart_footer">
                    <td colspan="5"><b>Subtotal</b></td>
                    <td class="right">$<?php echo number_format(get_subtotal(), 2); ?></td>
                </tr>
                <tr>
                    <td colspan="6" class="right">
                        <input type="submit" value="Update Cart">
                    </td>
                </tr>
            </table>
        </form>
            
        </form>
        <p>
            Click "Update Cart" to update quantities in your cart. <br/>
            Enter a quantity of 0 or check the box to remove an item.
        </p>
        <div style="display: flex; justify-content: space-between; padding-bottom: 5px"> 
            <a href="index.php?action=checkout">Continue to Checkout</a>
            <a href="index.php?action=empty_cart">Empty Cart</a>
        </div>
<?php endif; ?>
    </main>
</body>

<?php include '../View/footer.php'; ?>
