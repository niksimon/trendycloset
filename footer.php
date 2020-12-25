<div class="clear"></div>
</div>
</div>
<div class="footer-wrap">
    <div class="footer">
        <div class="footer-block">
            <p>SUBSCRIBE TO OUR NEWSLETTER</p>
            <form action='' method='post' class="subscribeForm">
                <input type="email"/>
                <input type="submit" value="Subscribe"/>
            </form>
            <p style='padding-top: 15px;padding-bottom: 0;'>SOCIAL MEDIA</p>
            <ul class='social-media'>
                <li><a href='#'><i class="fa fa-facebook-square"></i></a></li>
                <li><a href='#'><i class="fa fa-twitter-square"></i></a></li>
                <li><a href='#'><i class="fa fa-youtube-square"></i></a></li>
                <li><a href='#'><i class="fa fa-pinterest-square"></i></a></li>
                <li><a href='#'><i class="fa fa-google-plus-square"></i></a></li>
            </ul>
        </div>
        <div class="footer-block">
            <p>SHOP MEN'S</p>
            <ul>
                <?php
                $query_dropdown = mysqli_query($conn, "SELECT * FROM dropdown_menu dm WHERE id_menu=1");

                while ($q = mysqli_fetch_array($query_dropdown, MYSQLI_ASSOC)) {
                echo "<li><a href='shop.php?category=Men&amp;type=" . $q['id_type'] . "' class='menu-item' >" . $q['item_menu_name'] . "</a></li>\n";
                }
                ?>
            </ul></div>
        <div class="footer-block">
            <p>SHOP WOMEN'S</p>
            <ul>
                <?php
                $query_dropdown = mysqli_query($conn, "SELECT * FROM dropdown_menu dm WHERE id_menu=2");

                while ($q = mysqli_fetch_array($query_dropdown, MYSQLI_ASSOC)) {
                echo "<li><a href='shop.php?category=Women&amp;type=" . $q['id_type'] . "' class='menu-item' >" . $q['item_menu_name'] . "</a></li>\n";
                }
                ?>
            </ul>
        </div>
        <div class="footer-block">
            <p>TRENDY CLOSET</p>
            <ul>
                <li><a href='author.php'>About</a></li>
                <li><a href='#'>Blog</a></li>
                <?php
                if (!isset($_SESSION["id_user"])) {
                ?>
                <li><a href="login.php">Sign in</a></li>
                <li><a href="register.php">Register</a></li>
                <?php } 
                else {
                    ?>
                    <li><a href="account.php">My account</a></li>
                    <li><a href="logout.php">Sign out</a></li>
                    <?php if ($_SESSION["id_role"] == 1) { ?>
                        <li><a href="admin/index.php" target='_blank'>Admin</a></li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
        <div class="clear"></div>
        <p class="footer-info">Made in 2016 | Images from <a href="http://asos.com">asos.com</a></p>
    </div>
</div>
</body>
</html>