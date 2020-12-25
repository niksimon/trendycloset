<?php
include("connection.php");
$id_product = $_GET["id_product"];
$id_user = $_GET["id_user"];
$id_size = $_GET["size"];
$query_check = mysqli_query($conn, "SELECT * FROM cart WHERE id_product=$id_product AND id_user=$id_user AND id_size=$id_size");
if (mysqli_num_rows($query_check) == 0) {
    $query_add = mysqli_query($conn, "INSERT INTO cart VALUES('', $id_user, $id_product, 1, $id_size, " . time() . ")");
}
else{
    $quantity = mysqli_fetch_array($query_check, MYSQLI_ASSOC)["quantity"] + 1;
    $query_update = mysqli_query($conn, "UPDATE cart SET quantity = $quantity WHERE id_product=$id_product AND id_user=$id_user");
}

mysqli_close($conn);
?>