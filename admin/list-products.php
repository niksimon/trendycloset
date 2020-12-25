<?php
$page_title = "Trendy Closet - Products";

include_once("header.php");
?>
<h2>PRODUCTS</h2>
<?php
$query_all = mysqli_query($conn, "SELECT * FROM products p JOIN colors c ON p.id_color=c.id_color JOIN types t ON p.id_type=t.id_type ORDER BY date_added DESC");
if (mysqli_num_rows($query_all) > 0) {
    ?>
    <form method="post" action="list-products.php">
        Sort by: <select id="sort" name="sort">
            <option value='0'>Newest</option>
            <option value='1'>Oldest</option>
            <option value='2'>A-Z</option>
            <option value='3'>Z-A</option>
            <option value='4'>Price - High to low</option>
            <option value='5'>Price - Low to high</option>
        </select>
        <div id="products_list">
            <?php
            if (isset($_GET['delete'])) {
                $delete = $_GET["delete"];
                $query_delete_product = mysqli_query($conn, "DELETE FROM products WHERE id_product=" . $delete);
                header("Location: list-products.php");
            }

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
            echo "</table></div></form>";
        } else {
            echo "<p>No products found in database!</p>";
        }
        ?>
        <script>
            window.addEventListener("load", function () {
                document.getElementById("sort").addEventListener("change", function () {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (xhttp.readyState == 4 && xhttp.status == 200) {
                            document.getElementById("products_list").innerHTML = xhttp.responseText;
                        }
                    };
                    xhttp.open("GET", "sort-products.php?sort=" + this.value, true);
                    xhttp.send();
                });
            });
        </script>
        <?php
        include_once("footer.php");
        ?>