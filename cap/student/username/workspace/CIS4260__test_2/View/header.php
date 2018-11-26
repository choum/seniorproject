<!DOCTYPE html>
<html>
    <!-- the head section -->
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="./main.css">
        <title>Pet Supply</title>
    
    </head>

    <!-- the body section -->
    <body>
        <header>
            <h1>Pet Supply</h1>
            <a href="" id="cart">Shopping Cart</a>
            <script>
                if(window.location.pathname.toString().endsWith("/Crest_Justin_A2/index.php" || "/Crest_Justin_A2/") || window.location.pathname.toString().endsWith("/Crest_Justin_A2/")){
                document.getElementById("cart").href="./Shopping_Cart/index.php";
            }
            else{
                document.getElementById("cart").href="../Shopping_Cart/index.php";
            }
            </script>
        </header>
