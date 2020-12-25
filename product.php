<?php
include_once("header.php");

$cart = "";
if (!isset($_SESSION["id_user"])) {
    $notify_cart = "Sign in to add to cart!";
} else {
    $notify_cart = "Item added to cart!";
}

$product_id = $_GET['id_product'];
$query_select_product = mysqli_query($conn, "SELECT * FROM products p JOIN categories c ON p.id_category=c.id_category JOIN types t ON p.id_type=t.id_type WHERE id_product=" . $product_id);
$query_select_type = mysqli_query($conn, "SELECT * FROM dropdown_menu dm JOIN types t ON dm.id_type=t.id_type JOIN products p ON t.id_type=p.id_type WHERE p.id_product=" . $product_id);
$selected_product = mysqli_fetch_array($query_select_product, MYSQLI_ASSOC);
echo "<p class='navigation-links'><a href='index.php'>Home</a> &gt; <a href='shop.php?category=" . $selected_product['category_name'] . "'>" . $selected_product['category_name'] . "</a> &gt; <a href='shop.php?category=" . $selected_product['category_name'] . "&type=" . mysqli_fetch_array($query_select_type, MYSQLI_ASSOC)['id_menu_item'] . "'>" . $selected_product['type_name'] . "</a></p>";
?>

<div class="product-left">
    <div class="product-left-main">
        <a class="fancybox" data-fancybox-group="group" href="uploads/product_images/<?php echo $selected_product['folder'] . "/image1Big.jpg"; ?>"><img class="product-active-img" src="uploads/product_images/<?php echo $selected_product['folder'] . "/image1Main.jpg"; ?>"/></a>
        <a  class="fancybox" data-fancybox-group="group" href="uploads/product_images/<?php echo $selected_product['folder'] . "/image2Big.jpg"; ?>"><img src="uploads/product_images/<?php echo $selected_product['folder'] . "/image2Main.jpg"; ?>"/></a>
        <a  class="fancybox" data-fancybox-group="group" href="uploads/product_images/<?php echo $selected_product['folder'] . "/image3Big.jpg"; ?>"><img src="uploads/product_images/<?php echo $selected_product['folder'] . "/image3Main.jpg"; ?>"/></a>
    </div>
    <div class="product-left-imgs">
        <img  class="product-left-img" src='uploads/product_images/<?php echo $selected_product['folder'] . "/image1Thumb.jpg"; ?>'/>
        <img  class="product-left-img" src='uploads/product_images/<?php echo $selected_product['folder'] . "/image2Thumb.jpg"; ?>'/>
        <img  class="product-left-img" src='uploads/product_images/<?php echo $selected_product['folder'] . "/image3Thumb.jpg"; ?>'/>
    </div>
</div>
<div class="product-right">
    <h3 class="product-title"><?php echo $selected_product['product_name']; ?></h3>
    <p class="product-price">$<?php echo number_format($selected_product['price'], 2); ?></p>
    <select id="ddlSize">
        <option value='0'>Pick size...</option>
        <?php
        $query_select_sizes = mysqli_query($conn, "SELECT * FROM product_sizes ps JOIN sizes s ON ps.id_size=s.id_size WHERE id_product=" . $product_id);
        while ($q = mysqli_fetch_array($query_select_sizes, MYSQLI_ASSOC)) {
            echo "<option value='" . $q['id_size'] . "'>" . $q['size_name'] . "</option>";
        }
        ?>
    </select><br/>
    <?php
    if (isset($_SESSION["id_role"])) {
        $cart = "onclick='addToCart(" . $_GET["id_product"] . ", " . $_SESSION["id_user"] . ")'";
    }
    ?>
    <a href='javascript:void(0)' <?php echo $cart; ?> class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;ADD TO CART<span class="cart-pop-up"><?php echo $notify_cart; ?></span></a>
    <p class="product-add-wishlist"><a href='javascript:void(0);' onclick='addToWishlist(<?php echo "$product_id, " . $_SESSION['id_user']; ?>)'><i class="fa fa-heart"></i>Add to wishlist</a></p>
</div>
<script>
    $(document).ready(function () {
        $(".product-left-img").mouseover(function () {
            $(".product-left-img").css("border-color", "#fff");
            $(this).css("border-color", "#888");
            $(".product-left-main img").removeClass("product-active-img");
            $(".product-left-main img:eq(" + $(this).index() + ")").addClass("product-active-img");
        });
        $(".fancybox").fancybox({
            openEffect: "elastic",
            closeEffect: "elastic",
            openEasing: "easeOutBack",
            closeEasing: "easeInBack",
            helpers: {
                overlay: {
                    locked: !1
                },
                thumbs: {
                    width: 50,
                    height: 50
                }
            }
        });
    });
    function addToWishlist(id_product, id_user) {
        var xhttp = new XMLHttpRequest();
        /*xhttp.onreadystatechange = function () {
         if (xhttp.readyState == 4 && xhttp.status == 200) {
         document.getElementById("wishlist-count").innerHTML = xhttp.responseText;
         }
         };*/
        xhttp.open("GET", "add-to-wishlist.php?id_product=" + id_product + "&id_user=" + id_user, true);
        xhttp.send();
    }

    function addToCart(id_product, id_user) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {

            }
        };
        var size = document.getElementById("ddlSize").value;
        xhttp.open("GET", "add-to-cart.php?id_product=" + id_product + "&id_user=" + id_user + "&size=" + size, true);
        xhttp.send();
    }

    $(document).ready(function () {
        $(".add-to-cart-btn").click(function () {
            if ($("#ddlSize").val() == 0) {
                $("#ddlSize").css({"border-color": "#ff0000"});
            }
            else{
                $this = $(this);
                $(".cart-pop-up").stop(true).fadeIn(150, function () {
                    $(".cart-pop-up").delay(1500).fadeOut(150);
                });
                $("#ddlSize").css({"border-color": "#000"});
            }
        });
    });
</script>
<?php
include_once("footer.php");
?>