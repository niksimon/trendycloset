<?php
include("connection.php");
$id_user = $_GET["id_user"];
$id_product = $_GET["id_product"];

$query_delete = mysqli_query($conn, "DELETE FROM cart WHERE id_user=$id_user AND id_product=$id_product");

$sum = 0;
$query_select_products = mysqli_query($conn, "SELECT * FROM cart c JOIN products p ON c.id_product=p.id_product JOIN sizes s ON c.id_size=s.id_size WHERE c.id_user=" . $id_user);
if (mysqli_num_rows($query_select_products) > 0) {
    while ($q = mysqli_fetch_array($query_select_products, MYSQLI_ASSOC)) {
        $sum += $q["price"] * $q["quantity"];
        echo "<div class='product product-grid3 product-cart'><div class='cart-img product-img-grid3'><a href='product.php?id_product=" . $q['id_product'] . "'><img src='uploads/product_images/" . $q['folder'] . "/image1Grid3.jpg'/></a><div class='cart-delete' onclick='fadeOutCartItem($(this), " . $q['id_product'] . ", " . $id_user . ")'><img src='img/delete_icon2.png'/></div></div><p>" . $q['product_name'] . "</p><p style='font-weight: 700'>$" . number_format($q['price'], 2) . "</p><p>Size: " . $q['size_name'] . "</p><p>Quantity: " . $q['quantity'] . "</p></div>";
    }
    echo "<p style='font-size: 24px; font-weight: bold; padding-top: 60px;'>Total: $" . number_format($sum, 2) . "</p><p class='cart-buy'><a href='#'>Buy now</a></p>";
} else {
    echo "<p style='font-size: 24px; text-align: center; padding-top: 50px;'>Your cart is empty!</p>";
}
?>