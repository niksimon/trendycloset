<?php
include_once("header.php");
?>
<div class="slideshow">
    <div class="slide-background active"></div>
    <div class="slide-background"></div>
    <div class="slide-background"></div>
    <div class="slide-background"></div>
</div>
<div class="main-content">
    <h3>SHOP NEW APPAREL</h3>
    <div class="new-items-wrap">
        <div class="slide-left"></div>
        <div class="slide-right"></div>
        <div class="new-items">
            <div class="new-items-slider">
                <?php
                $query = mysqli_query($conn, "SELECT * FROM products ORDER BY date_added DESC LIMIT 8");
                $wishlist = "";
                if (!isset($_SESSION["id_role"])) {
                    $notify = "You must be logged in to like!";
                } else {
                    $notify = "Item added to wishlist!";
                }
                while ($r = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                    $image_file = "image1Grid3";

                    if (isset($_SESSION["id_user"])) {
                        $wishlist = "onclick='addToWishlist(" . $r['id_product'] . ", " . $_SESSION["id_user"] . ")'";
                    }
                    echo "<div class='new-item'><div class='new-item-img'><a href='product.php?id_product=" . $r['id_product'] . "'><img src='uploads/product_images/" . $r['folder'] . "/$image_file.jpg'/></a><div class='product-pop-up'><i class='fa fa-heart add-to-wishlist' $wishlist></i><div class='wishlist-notify'><p>$notify</p></div></div></div><p>" . $r['product_name'] . "</p><p style='font-weight: 700'>$" . number_format($r['price'], 2) . "</p></div>";
                }
                ?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
<script>
    function changeImage() {
        var $currentImg = $(".slideshow .active");
        var $nextImg = $currentImg.next().is(".slide-background") ? $currentImg.next() : $(".slideshow .slide-background:first");
        $nextImg.css("z-index", 2);
        $currentImg.fadeOut(3000, function () {
            $currentImg.css("z-index", 1).show().removeClass("active");
            $nextImg.css("z-index", 3).addClass("active");
        });
        setTimeout("changeImage()", 6000);
    }
    
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
    
    $(document).ready(function () {
        setTimeout("changeImage()", 5000);

        var sliderIndex = 0;
        var positionX = 0;

        $(".slide-left").click(function () {
            if (sliderIndex > 0) {
                positionX += 240;
                $(".new-items-slider").css({"transform": "translateX(" + positionX + "px)"}, "slow");
                sliderIndex--;
            }
        });
        $(".slide-right").click(function () {
            if (sliderIndex < 4) {
                positionX -= 240;
                $(".new-items-slider").css({"transform": "translateX(" + positionX + "px)"}, "slow");
                sliderIndex++;
            }
        });
        
        $(".new-items-slider").on('click', '.add-to-wishlist', function () {
            $this = $(this);
            $this.next().stop(true).fadeIn(150, function () {
                $this.next().delay(1500).fadeOut(150);
            });
        });
    });
</script>
<?php
include_once("footer.php");
?>
