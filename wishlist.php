<?php
include_once("header.php");
?>
<h2>MY WISHLIST</h2>
<div id="wishlist-content">
    <?php
    $query_select_products = mysqli_query($conn, "SELECT * FROM wishlist w JOIN products p ON w.id_product=p.id_product WHERE w.id_user=" . $_SESSION["id_user"]);
    if (mysqli_num_rows($query_select_products) > 0) {
        while ($q = mysqli_fetch_array($query_select_products, MYSQLI_ASSOC))
            echo "<div class='product product-grid3 product-wishlist'><div class='wishlist-img product-img-grid3'><a href='product.php?id_product=" . $q['id_product'] . "'><img src='uploads/product_images/" . $q['folder'] . "/image1Grid3.jpg'/></a><div class='wishlist-delete' onclick='fadeOutWishlistItem($(this), " . $q['id_product'] . ", " . $_SESSION['id_user'] . ")'><img src='img/delete_icon2.png'/></div></div><p>" . $q['product_name'] . "</p><p style='font-weight: 700'>$" . number_format($q['price'], 2) . "</p></div>";
    } else {
        echo "<p style='font-size: 24px; text-align: center; padding-top: 50px;'>Your wishlist is empty!</p>";
    }
    ?>
</div>
<script>
    function deleteWishlistItem(id_product, id_user) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("wishlist-content").innerHTML = xhttp.responseText;
            }
        };
        xhttp.open("GET", "wishlist-update.php?id_product=" + id_product + "&id_user=" + id_user, true);
        xhttp.send();
    }

    function fadeOutWishlistItem(obj, id_product, id_user) {
        obj.parent().parent().fadeOut(500, function () {
            deleteWishlistItem(id_product, id_user);
        });
    }
</script>
<?php
include_once("footer.php");
?>