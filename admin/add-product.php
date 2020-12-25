<?php
$page_title = "Trendy Closet - Add Product";

include_once("header.php");
?>
<div class="form-block">
    <h2>ADD PRODUCT</h2>
    <form action="add-product.php" method="post" enctype="multipart/form-data">
        <div class="row"><span>Name:</span><input type="text" name="productName"/></div>
        <div class="row"><span>Category:</span><select name="productCategory">
                <?php
                $query_category = mysqli_query($conn, "SELECT * FROM categories");
                while ($r = mysqli_fetch_array($query_category, MYSQLI_ASSOC)) {
                    echo "<option value='" . $r['id_category'] . "'>" . $r['category_name'] . "</option>";
                }
                ?>
            </select></div>
        <div class="row"><span>Type:</span><select name="productType">
                <?php
                $query_types = mysqli_query($conn, "SELECT * FROM types");
                while ($r = mysqli_fetch_array($query_types, MYSQLI_ASSOC)) {
                    echo "<option value='" . $r['id_type'] . "'>" . $r['type_name'] . "</option>";
                }
                ?>
            </select></div>
        <div class="row"><span>Color:</span><select name="productColor">
                <?php
                $query_colors = mysqli_query($conn, "SELECT * FROM colors");
                while ($r = mysqli_fetch_array($query_colors, MYSQLI_ASSOC)) {
                    echo "<option value='" . $r['id_color'] . "'>" . $r['color_name'] . "</option>";
                }
                ?>
            </select></div>
        <div class="row"><span>Sizes:</span>
            <?php
            $query_sizes = mysqli_query($conn, "SELECT * FROM sizes");
            while ($r = mysqli_fetch_array($query_sizes, MYSQLI_ASSOC)) {
                echo "<input name='sizes[]' id='size" . $r['id_size'] . "' type='checkbox' value='" . $r['id_size'] . "'> <label for='size" . $r['id_size'] . "'>" . $r['size_name'] . "</label> ";
            }
            ?></div>
        <div class="row"><span>Image 1:</span><input type="file" name="productImage[]"/></div>
        <div class="row"><span>Image 2:</span><input type="file" name="productImage[]"/></div>
        <div class="row"><span>Image 3:</span><input type="file" name="productImage[]"/></div>
        <div class="row"><span>Price:</span><input type="text" name="productPrice"/></div>
        <div class="row"><input type="submit" value="Add" name="btnUpload"/></div>
    </form>
</div>
<?php

function resizeImage($src, $dst, $new_w) {
    $source = imagecreatefromjpeg($src);
    $width = imagesx($source);
    $height = imagesy($source);
    $new_h = floor($height * ($new_w / $width));
    $copy = imagecreatetruecolor($new_w, $new_h);
    imagecopyresampled($copy, $source, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
    imagejpeg($copy, $dst, 98);
}

if (isset($_POST["btnUpload"])) {
    $name = $_POST["productName"];
    $category = $_POST["productCategory"];
    $type = $_POST["productType"];
    $color = $_POST["productColor"];
    $sizes = $_POST["sizes"];
    $price = $_POST["productPrice"];
    $file_name = $_FILES["productImage"]["name"][0];
    $file_tmp = $_FILES["productImage"]["tmp_name"][0];
    $file_type = $_FILES["productImage"]["type"][0];
    $time = time();

    $query_last_id = mysqli_query($conn, "SELECT MAX(id_product) FROM products");

    if ($file_type == "image/jpg" || $file_type == "image/jpeg") {
        $folder = time() . "_" . dechex(rand(10000, 99999));
        mkdir("../uploads/product_images/" . $folder);
        $new = "../uploads/product_images/" . $folder . "/image1Big." . explode('.', $file_name)[1];
        if (move_uploaded_file($file_tmp, $new)) {
            move_uploaded_file($_FILES["productImage"]["tmp_name"][1], "../uploads/product_images/" . $folder . "/image2Big.jpg");
            resizeImage("../uploads/product_images/" . $folder . "/image2Big.jpg", "../uploads/product_images/" . $folder . "/image2Main.jpg", 400);
            resizeImage("../uploads/product_images/" . $folder . "/image2Big.jpg", "../uploads/product_images/" . $folder . "/image2Thumb.jpg", 128);
            move_uploaded_file($_FILES["productImage"]["tmp_name"][2], "../uploads/product_images/" . $folder . "/image3Big.jpg");
            resizeImage("../uploads/product_images/" . $folder . "/image3Big.jpg", "../uploads/product_images/" . $folder . "/image3Main.jpg", 400);
            resizeImage("../uploads/product_images/" . $folder . "/image3Big.jpg", "../uploads/product_images/" . $folder . "/image3Thumb.jpg", 128);

            resizeImage($new, "../uploads/product_images/" . $folder . "/image1Main.jpg", 400);
            resizeImage($new, "../uploads/product_images/" . $folder . "/image1Grid2.jpg", 358);
            resizeImage($new, "../uploads/product_images/" . $folder . "/image1Grid3.jpg", 235);
            resizeImage($new, "../uploads/product_images/" . $folder . "/image1Grid4.jpg", 174);
            resizeImage($new, "../uploads/product_images/" . $folder . "/image1Thumb.jpg", 128);
            $query_insert = mysqli_query($conn, "INSERT INTO products (product_name, id_color, folder, price, date_added, id_type, id_category) VALUES('$name', '$color', '$folder', $price, $time, $type, $category)");
            if ($query_insert) {
                $id_product = mysqli_insert_id($conn);
                foreach ($sizes as $s) {
                    $query_s = mysqli_query($conn, "INSERT INTO product_sizes VALUES(" . $id_product . ", " . $s . ")");
                }
                if ($query_s) {
                    echo "<p>Upload complete!</p>";
                }
            } else {
                echo "<p>Failed to upload!</p>";
                echo mysqli_error($conn);
            }
        }
    } else {
        echo "<p>Wrong file type</p>";
    }
}
include_once("footer.php");
?>