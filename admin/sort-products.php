<?php
include("../connection.php");
if (isset($_GET["sort"])) {
    switch ($_GET["sort"]) {
        case 0:
            $order_by = " ORDER BY date_added DESC";
            break;
        case 1:
            $order_by = " ORDER BY date_added";
            break;
        case 2:
            $order_by = " ORDER BY product_name";
            break;
        case 3:
            $order_by = " ORDER BY product_name DESC";
            break;
        case 4:
            $order_by = " ORDER BY price DESC";
            break;
        case 5:
            $order_by = " ORDER BY price";
            break;
    }
}
$query_all = mysqli_query($conn, "SELECT * FROM products p JOIN colors c ON p.id_color=c.id_color JOIN types t ON p.id_type=t.id_type $order_by");
echo "<table border='1' style='border-collapse: collapse;' cellpadding='5'><tr style='background-color: #eee'><th>Name</th><th>Type</th><th>Color</th><th>Date added</th><th>Price</th><th>Image</th><th>Sizes</th><th>Edit</th><th>Delete</th></tr>";
while ($r = mysqli_fetch_array($query_all, MYSQLI_ASSOC)) {
    echo "<tr align='center'><td>" . $r['product_name'] . "</td><td>" . $r['type_name'] . "</td><td>" . $r['color_name'] . "</td><td>" . @date("m-d-Y h:i:s", $r['date_added']) . "</td><td>" . number_format($r['price'], 2) . "$</td><td><img width='128px' height='163px' src='../uploads/product_images/" . $r['folder'] . "/image1Thumb.jpg'/></td><td>";

    $query_sizes = mysqli_query($conn, "SELECT * FROM product_sizes ps JOIN sizes s ON ps.id_size=s.id_size WHERE id_product=" . $r['id_product']);
    while ($q = mysqli_fetch_array($query_sizes, MYSQLI_ASSOC)) {
        echo $q['size_name'] . "<br/>";
    }

    /* echo "</td><td><a class='edit-link' href='admin.php?page=7&id_product=".$r['id_product']."'><img width='36px' height='36px' src='img/edit_icon.png' alt='Edit icon'/></a></td><td><input type='checkbox' name='delete[]' value='".$r['id_product']."'/></td></tr>"; */
    echo "</td><td><a class='edit-link' href='edit-product.php?id_product=" . $r['id_product'] . "'><img width='36px' height='36px' src='../img/edit_icon.png' alt='Edit icon'/></a></td><td><a href='list-products.php?delete=" . $r['id_product'] . "'><img width='30px' height='30px' src='../img/delete_icon.png' alt='Delete icon'/></a></td></tr>";
}
echo "</table>";
?>