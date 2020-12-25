<?php
$page_title = "Trendy Closet - Edit Product";

include_once("header.php");

$query_select_product = mysqli_query($conn, "SELECT * FROM products WHERE id_product=" . $_GET['id_product']);
$selected_product = mysqli_fetch_array($query_select_product, MYSQLI_ASSOC);
?>
<div class="form-block">
    <h2>EDIT PRODUCT</h2>
    <form action="edit-product.php?id_product=<?php echo $_GET['id_product']; ?>" method="post" enctype="multipart/form-data">
        <div class="row"><span>Name:</span><input type="text" name="productName" value="<?php echo $selected_product['product_name']; ?>"/></div>
        <div class="row"><span>Category:</span><select name="productCategory">
                <?php
                $query_category = mysqli_query($conn, "SELECT * FROM categories");
                while ($r = mysqli_fetch_array($query_category, MYSQLI_ASSOC)) {
                    if ($selected_product['id_category'] == $r['id_category'])
                        echo "<option value='" . $r['id_category'] . "' selected='selected'>" . $r['category_name'] . "</option>";
                    else
                        echo "<option value='" . $r['id_category'] . "'>" . $r['category_name'] . "</option>";
                }
                ?>
            </select></div>
        <div class="row"><span>Type:</span><select name="productType">
                <?php
                $query_types = mysqli_query($conn, "SELECT * FROM types");
                while ($r = mysqli_fetch_array($query_types, MYSQLI_ASSOC)) {
                    if ($selected_product['id_type'] == $r['id_type'])
                        echo "<option value='" . $r['id_type'] . "' selected='selected'>" . $r['type_name'] . "</option>";
                    else
                        echo "<option value='" . $r['id_type'] . "'>" . $r['type_name'] . "</option>";
                }
                ?>
            </select></div>
        <div class="row"><span>Color:</span><select name="productColor">
                <?php
                $query_colors = mysqli_query($conn, "SELECT * FROM colors");
                while ($r = mysqli_fetch_array($query_colors, MYSQLI_ASSOC)) {
                    if ($selected_product['id_color'] == $r['id_color'])
                        echo "<option value='" . $r['id_color'] . "' selected='selected'>" . $r['color_name'] . "</option>";
                    else
                        echo "<option value='" . $r['id_color'] . "'>" . $r['color_name'] . "</option>";
                }
                ?>
            </select></div>
        <div class="row"><span>Sizes:</span>
            <?php
            $query_selected_sizes = mysqli_query($conn, "SELECT * FROM product_sizes ps JOIN sizes s ON ps.id_size=s.id_size WHERE id_product=" . $_GET['id_product']);
            $selected_sizes = array();
            while ($q = mysqli_fetch_array($query_selected_sizes, MYSQLI_ASSOC)) {
                $selected_sizes[] = $q['id_size'];
            }

            $query_sizes = mysqli_query($conn, "SELECT * FROM sizes");
            while ($r = mysqli_fetch_array($query_sizes, MYSQLI_ASSOC)) {
                if (in_array($r['id_size'], $selected_sizes))
                    echo "<input name='sizes[]' id='size" . $r['id_size'] . "' checked='checked' type='checkbox' value='" . $r['id_size'] . "'> <label for='size" . $r['id_size'] . "'>" . $r['size_name'] . "</label> ";
                else
                    echo "<input name='sizes[]' id='size" . $r['id_size'] . "' type='checkbox' value='" . $r['id_size'] . "'> <label for='size" . $r['id_size'] . "'>" . $r['size_name'] . "</label> ";
            }
            ?></div>
        <div class="row"><span>Image:</span><input type="file" name="productImage"/></div>
        <div class="row"><span>Price:</span><input type="text" name="productPrice" value="<?php echo $selected_product['price']; ?>"/></div>
        <div class="row"><span><input type="submit" value="SAVE CHANGES" name="btnSave"/></div>
    </form>
    <?php
    if (isset($_REQUEST["btnSave"])) {
        $name = $_POST["productName"];
        $category = $_POST["productCategory"];
        $type = $_POST["productType"];
        $color = $_POST["productColor"];
        $sizes = $_POST["sizes"];
        $price = $_POST["productPrice"];
        $file_name = $_FILES["productImage"]["name"];
        $file_type = $_FILES["productImage"]["type"];
        $file_tmp = $_FILES["productImage"]["tmp_name"];

        if (!empty($file_name)) {
            if ($file_type == "image/jpeg" || $file_type == "image/png" || $file_type == "image/jpg") {
                $new = time() . "_" . $file_name;
                if (move_uploaded_file($file_tmp, "../uploads/product_images/" . $new)) {
                    $query_current_image = mysqli_query($conn, "SELECT image_file FROM products WHERE id_product=" . $_GET['id_product']);
                    $current_image = mysqli_fetch_array($query_current_image, MYSQLI_ASSOC);
                    unlink("../uploads/product_images/" . $current_image['image_file']);
                    $query_update = mysqli_query($conn, "UPDATE products SET product_name='$name', id_category=$category, id_type=$type, image_file='$new', id_color=$color, price=$price WHERE id_product=" . $_GET['id_product']);
                }
            } else {
                echo "<p>Wrong file type</p>";
            }
        } else {
            $query_update = mysqli_query($conn, "UPDATE products SET product_name='$name', id_category=$category, id_type=$type, id_color=$color, price=$price WHERE id_product=" . $_GET['id_product']);
        }

        $query_delete = mysqli_query($conn, "DELETE FROM product_sizes WHERE id_product=" . $_GET['id_product']);

        foreach ($sizes as $s) {
            $query_s = mysqli_query($conn, "INSERT INTO product_sizes VALUES(" . $_GET['id_product'] . ", " . $s . ")");
        }

        if ($query_update) {
            header("Location: list-products.php");
        } else {
            echo "<p>Failed to update!</p>";
        }
    }
    include_once("footer.php");
    ?>