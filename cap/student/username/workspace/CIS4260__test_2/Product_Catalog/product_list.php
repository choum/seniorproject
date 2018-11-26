<?php include('../View/header.php'); ?> 

<main>
    <aside>
        <h1>Categories</h1>
        <nav>
        <ul>
            <!-- display links for all categories -->
            <?php foreach($categories as $category) : ?>
            <li>
                <a href="?category_id=<?php echo $category['categoryID']; ?>">
                    <?php echo $category['categoryName']; ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
        </nav>
    </aside>
    <section style="width: 80%">
        <h1><?php echo $category_name; ?></h1>
        <nav><p>
            <form action="index.php" method="get">
                <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                <label>Sort By: </label>
                <select name="sort">
                    <option value="11">Default</option>
                    <option value="41">Name: Ascending</option>
                    <option value="42">Name: Descending</option>
                    <option value="61">Price: Lowest to Highest</option>
                    <option value="62">Price: Highest to Lowest</option>
                </select>
                <input type="submit" value="Apply Changes">
            </form></p>
            <!-- display links for products in selected category and price -->
            <table style="border-style: hidden;">
                <tr id="cart_header">
                    <th class="left">Product Name</th>
                    <th class="right">Price</th>
                </tr>
                <?php foreach ($products as $product) : ?>
                <tr>
                    <td style="width: 80%">
                        <a href="index.php?action=view_product&amp;product_id=<?php 
                            echo $product['productID']; ?>">
                            <?php echo $product['productName']; ?>
                        </a>
                    </td>
                    <td style="width: 10%;" class="right">
                        <label>$<?php echo number_format($product['listPrice'],2);?></label>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </nav>
        <nav>
            <p align="center">
            <?php $counter = 0; for($i = 0; $i < $product_count; $i+=8){ $counter++;
            if($page_number != $counter) {?>
            <a href="index.php?action=list_products&amp;category_id=<?php
            echo $category_id; ?>&amp;sort=<?php echo $sort; ?>&amp;page_number=<?php
            echo $counter; ?>">&nbsp;<?php echo $counter ; ?>&nbsp;</a>
            <?php } else { echo " " . $counter . " "; } }?>
            </p>
        </nav>
    </section>
    
</main>
<?php include('../View/footer.php'); ?>
