<?php
include("connection.php");
$id_user = $_GET["id_user"];
$id_product = $_GET["id_product"];

$query_delete = mysqli_query($conn, "DELETE FROM wishlist WHERE id_user=$id_user AND id_product=$id_product");

$query_select_products = mysqli_query($conn, "SELECT * FROM wishlist w JOIN products p ON w.id_product=p.id_product WHERE w.id_user=$id_user");
if (mysqli_num_rows($query_select_products) > 0) {
    while ($q = mysqli_fetch_array($query_select_products, MYSQLI_ASSOC))
        echo "<div class='product product-grid3'><div class='wishlist-img product-img-grid3'><a href='product.php?id_product=" . $q['id_product'] . "'><img src='uploads/product_images/" . $q['folder'] . "/image1Grid3.jpg'/></a><div class='wishlist-delete' onclick='deleteWishlistItem(" . $q['id_product'] . ", " . $q['id_user'] . ")'><img src='img/delete_icon2.png'/></div></div><p>" . $q['product_name'] . "</p><p style='font-weight: 700'>$" . number_format($q['price'], 2) . "</p></div>";
} else {
    echo "<p style='font-size: 24px; text-align: center; padding-top: 50px;'>Your wishlist is empty!</p>";
}
?>