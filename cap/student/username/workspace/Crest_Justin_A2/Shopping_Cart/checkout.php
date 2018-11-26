<?php
include('../View/header.php');
?>
<main>
    <body>
        <h1>Checkout</h1>
         <?php if (empty($_SESSION['cart']) || count($_SESSION['cart']) == 0) :  ?>
            <p>There are no items in your cart... Redirecting.</p>
            <script>
                setTimeout(function(){ window.location="index.php"; }, 1500);    
            </script>
        <?php else: ?>
            <section style='border-style: double; width:57%; margin-right: 15px; display: table-cell' class='left' />
                <ul>
                    <!-- displays items purchased by user -->
                    <?php foreach($_SESSION['cart'] as $key => $item): 
                    $item_total_f = number_format($item['total'], 2); ?>
                    <li style="padding:1.5em">
                        <section style='width: 75%' class='left'>
                            <?php echo $item['name']; ?>
                        </section>
                        <section style='width: 25%' class='right'>
                            <label>$<?php echo $item_total_f;  ?></label>
                        </section>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <section style='border-style: double; width: 39%; display: table-cell'>
                <ul>
                    <!-- displays subtotal, discount, tax, grandtotal -->
                    <?php foreach(get_checkout_info() as $key => $item): ?>
                    <li style="padding: 1em">
                        <section style='width: 50%' class='left'>
                            <?php echo $key; ?>: 
                        </section>
                        <?php if($key == 'Tax'){ ?>
                            <section style='width: 50%' class='right'>
                                <?php echo number_format($item,2); ?>%
                            </section>
                        <?php } else { ?>
                            <section style='width: 50%' class='right'>
                                $<?php echo number_format($item,2); ?>
                            </section>
                        <?php } ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <br/>
            <p>
                Shopping cart will now be emptied, thank you for your purchase!
            </p>
        <?php endif; ?>
    </body>
</main>


<?php include('../View/footer.php'); ?>