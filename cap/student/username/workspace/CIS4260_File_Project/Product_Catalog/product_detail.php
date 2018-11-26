<?php include('../View/header.php'); ?>
<main>
    <aside>
        <h1>Categories</h1>
        <nav>
            <ul>
                <!-- display links for all categories -->
                <?php foreach($categories as $category) : ?>
                <li>
                    <a href="index.php?category_id=<?php 
                              echo $category['categoryID']; ?>">
                        <?php echo $category['categoryName']; ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </aside>
    <section>
        <h1><?php echo $detail; ?></h1>
        <div id="left_column">
            <p>
                <img src="<?php echo $image_filename; ?>"
                    alt="<?php echo $image_alt; ?>" height="150" width="auto"/>
            </p>
        </div>
        <div id="right_column">
            <p><b>List Price:</b> $<?php echo $list_price; ?></p>
            <p><b>Discount:</b> <?php echo $discount_percent; ?>%</p>
            <p><b>Your Price:</b> $<?php echo $unit_price_f; ?>
                 (You save $<?php echo $discount_amount_f; ?>)</p>
            <form action="<?php echo '../Shopping_Cart/index.php' ?>" method="post">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <input type="hidden" name="name" value="<?php echo $name; ?>">
                <input type="hidden" name="cost" value="<?php echo $unit_price; ?>">
                <b>Quantity:</b><input id="quantity" type="text" name="quantity" value="1" size="2">
                <br><br>
                <input type="submit" value="Add to Cart">
            </form>
        </div>
    </section>
</main>
<?php include('../View/footer.php'); ?>
