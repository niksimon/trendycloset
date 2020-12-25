<?php
include("connection.php");
$id_product = $_GET["id_product"];
$id_user = $_GET["id_user"];
$query_check = mysqli_query($conn, "SELECT * FROM wishlist WHERE id_product=$id_product AND id_user=$id_user");
if (mysqli_num_rows($query_check) == 0) {
    $query_add = mysqli_query($conn, "INSERT INTO wishlist (id_product, id_user, date_added) VALUES($id_product, $id_user, " . time() . ")");
}
$query_wishlist = mysqli_query($conn, "SELECT COUNT(id_item) FROM wishlist w JOIN users u ON w.id_user=u.id_user WHERE w.id_user=" . $id_user);
if (mysqli_num_rows($query_wishlist) > 0)
    $wishlist_count = mysqli_fetch_array($query_wishlist)[0];
else
    $wishlist_count = 0;
echo $wishlist_count;
mysqli_close($conn);
?>