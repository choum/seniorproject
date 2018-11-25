<footer>
    <p>
        <a href="" id="home">Return to home page</a>
    </p>
    <script>
        if(window.location.pathname === "/Crest_Justin_A2/index.php"){
            document.getElementById("home").href="./index.php";
        }
        else{
            document.getElementById("home").href="../index.php";
        }
    </script>
    <p class="copyright">
        &copy; <?php echo date("Y"); ?> Pet Supply, Inc.
    </p>
</footer>
</body>
</html>
