<?php
include_once("header.php");

if (isset($_GET["type"])) {
    $query_type = mysqli_query($conn, "SELECT id_type FROM dropdown_menu WHERE id_menu_item=" . $_GET["type"]);
    $type_id = mysqli_fetch_array($query_type, MYSQLI_ASSOC)['id_type'];
}

$category = isset($_GET["category"]) ? $_GET["category"] : 'Men';
?>
<p class="navigation-links"><a href='index.php'>Home</a> &gt; <a href='shop.php?category=<?php echo isset($_GET["category"]) ? $_GET["category"] : "Men"; ?>'><?php echo isset($_GET["category"]) ? $_GET["category"] : "Men"; ?></a><?php if (isset($_GET["type"])) { ?> &gt; <a href='shop.php?category=<?php echo isset($_GET["category"]) ? $_GET["category"] : "Men" . "&type=" . $_GET["type"]; ?>'><?php
            $query_type_name = mysqli_query($conn, "SELECT type_name FROM types WHERE id_type=" . $type_id);
            echo mysqli_fetch_array($query_type_name, MYSQLI_ASSOC)['type_name'];
            ?></a> <?php } ?></p>
<div class="products_left">
    <h3>FILTER BY:</h3>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <?php if (!isset($_GET["type"])) { ?>
            <p class="filter-title">Type:</p>
            <div class="products_filter_block">
                <?php
                if (!isset($_GET["category"]) || $_GET["category"] == "Men") {
                    $id_cat = 1;
                } else {
                    $id_cat = 2;
                }
                $query_types = mysqli_query($conn, "SELECT d.id_type, t.type_name, d.id_menu FROM dropdown_menu d JOIN types t ON d.id_type=t.id_type WHERE d.id_menu=" . $id_cat);
                while ($q = mysqli_fetch_array($query_types, MYSQLI_ASSOC)) {
                    echo "<span><input type='checkbox' name='types' value='" . $q['id_type'] . "' id='type" . $q['id_type'] . "'/> <label for='type" . $q['id_type'] . "'>" . $q['type_name'] . "</label></span>";
                }
                ?>
            </div>
        <?php } ?>
        <p class="filter-title">Color:</p>
        <div class="products_filter_block">
            <?php
            $color_type = "";
            if (isset($_GET["type"])) {
                $color_type = " AND id_type=" . $type_id;
            }
            $query_colors = mysqli_query($conn, "SELECT DISTINCT c.id_color, c.color_name, c.hex_code FROM colors c JOIN products p ON c.id_color=p.id_color JOIN categories cat ON p.id_category=cat.id_category WHERE cat.category_name='" . $category . "'" . $color_type);
            while ($q = mysqli_fetch_array($query_colors, MYSQLI_ASSOC)) {
                echo "<span><input style='outline: 1px solid " . $q['hex_code'] . "' type='checkbox' name='colors' value='" . $q['id_color'] . "' id='color" . $q['id_color'] . "'/> <label for='color" . $q['id_color'] . "'>" . $q['color_name'] . "</label></span>";
            }
            ?>
        </div>
        <p class="filter-title">Size:</p>
        <div class="products_filter_block">
            <?php
            $size_type = "";
            if (isset($_GET["type"])) {
                $size_type = " AND id_type=" . $type_id;
            }

            $query_sizes = mysqli_query($conn, "SELECT DISTINCT s.id_size, s.size_name FROM product_sizes ps JOIN products p ON ps.id_product=p.id_product JOIN sizes s ON ps.id_size=s.id_size JOIN categories cat ON p.id_category=cat.id_category WHERE cat.category_name='" . $category . "'" . $size_type);
            while ($q = mysqli_fetch_array($query_sizes, MYSQLI_ASSOC)) {
                echo "<span><input type='checkbox' name='sizes' value='" . $q['id_size'] . "' id='size" . $q['id_size'] . "'/> <label for ='size" . $q['id_size'] . "'>" . $q['size_name'] . "</label></span>";
            }
            ?>
        </div>
        <p>Price:</p>
        <div id="slider"></div>
        <p class="price-nums"><span id="price-min">$0</span><span id="price-max">$<?php
                $query_select_max_price = mysqli_query($conn, "SELECT MAX(price) FROM products");
                echo mysqli_fetch_array($query_select_max_price)[0];
                ?></span></p>
    </form>
</div>
<div class="products_right">
    <h3><?php
        if (isset($_GET["type"])) {
            $query_title = mysqli_query($conn, "SELECT type_name FROM types t JOIN dropdown_menu dm ON t.id_type=dm.id_type WHERE dm.id_menu_item=" . $_GET["type"]);
            echo isset($_GET["category"]) ? $_GET["category"] . "'S " . mysqli_fetch_array($query_title, MYSQLI_ASSOC)['type_name'] :  mysqli_fetch_array($query_title, MYSQLI_ASSOC)['type_name'];
        } else if(isset($_GET["category"])) {
            echo $_GET["category"] . "'S CLOTHING";
        }
        else{
            echo "SHOP MEN & WOMEN";
        }
        ?></h3>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <span class="sortBy">Sort by:
            <select name="sort" id="sort">
                <option value="0">Newest</option>
                <option value="1">A - Z</option>
                <option value="2">Price high to low</option>
                <option value="3">Price low to high</option>
            </select></span>
        <span style='padding-left: 100px;'>View: </span>
        <a href="javascript:void(0)" data-grid='2' onclick='getproducts(0, 2)' class="grid-view"><img src='img/grid2.png'/></a>
        <a href="javascript:void(0)" data-grid='3' onclick='getproducts(0, 3)' class="grid-view grid-view-active"><img src='img/grid3.png'/></a>
        <a href="javascript:void(0)" data-grid='4' onclick='getproducts(0, 4)' class="grid-view"><img src='img/grid4.png'/></a>
    </form>
    <div class="products_list" id="products_list">


    </div></div>
<script>
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

    var slider;
    function getproducts(page, grid) {
        if (typeof page == "undefined")
            page = 0;
        if (typeof grid == "undefined")
            grid = 3;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("products_list").innerHTML = xhttp.responseText;
            }
        };
        var colors = "", sizes = "", types = "";
        var sort_by = document.getElementById("sort").value;
<?php if (!isset($_GET["type"])) { ?>
            for (var i = 0; i < document.getElementsByName("types").length; i++) {
                var type = document.getElementsByName("types")[i];
                if (type.checked) {
                    if (types != "")
                        types += "," + type.value;
                    else
                        types += type.value;
                }
            }
<?php } ?>
        for (var i = 0; i < document.getElementsByName("colors").length; i++) {
            var color = document.getElementsByName("colors")[i];
            if (color.checked) {
                if (colors != "")
                    colors += "," + color.value;
                else
                    colors += color.value;
            }
        }
        for (var i = 0; i < document.getElementsByName("sizes").length; i++) {
            var size = document.getElementsByName("sizes")[i];
            if (size.checked) {
                if (sizes != "")
                    sizes += "," + size.value;
                else
                    sizes += size.value;
            }
        }
        var price_min = slider.noUiSlider.get()[0];
        var price_max = slider.noUiSlider.get()[1];
        xhttp.open("GET", "getproducts.php?category=<?php
echo $category;
?>&type=<?php
if (isset($_GET['type']))
    echo $_GET['type'];
else
    echo '0';
?>&colors=" + colors + "&types=" + types + "&sizes=" + sizes + "&sort=" + sort_by + "&page=" + page + "&grid=" + grid + "&price_min=" + price_min + "&price_max=" + price_max, true);
        xhttp.send();
    }

    $(document).ready(function () {
        const types = document.getElementsByName("types");
        const colors = document.getElementsByName("colors");
        const sizes = document.getElementsByName("sizes");

        types.forEach(type => type.addEventListener("change", function () {
                    getproducts(0, $(".grid-view-active").attr("data-grid"));
                }));
        colors.forEach(color => color.addEventListener("change", function () {
                    getproducts(0, $(".grid-view-active").attr("data-grid"));
                }));
        sizes.forEach(size => size.addEventListener("change", function () {
                    getproducts(0, $(".grid-view-active").attr("data-grid"));
                }));
        /*for (var i = 0; i < document.getElementsByName("types").length; i++) {
            (function (i) {
                document.getElementsByName("types")[i].addEventListener("change", function () {
                    getproducts(0, $(".grid-view-active").attr("data-grid"));
                });
            })(i);
        }
        for (var i = 0; i < document.getElementsByName("colors").length; i++) {
            (function (i) {
                document.getElementsByName("colors")[i].addEventListener("change", function () {
                    getproducts(0, $(".grid-view-active").attr("data-grid"));
                });
            })(i);
        }
        for (var i = 0; i < document.getElementsByName("sizes").length; i++) {
            (function (i) {
                document.getElementsByName("sizes")[i].addEventListener("change", function () {
                    getproducts(0, $(".grid-view-active").attr("data-grid"));
                });
            })(i);
        }*/
        document.getElementById("sort").addEventListener("change", function () {
            getproducts(0, $(".grid-view-active").attr("data-grid"));
        });

        slider = document.getElementById('slider');
        noUiSlider.create(slider, {
            start: [0, <?php
$query_select_max_price = mysqli_query($conn, "SELECT MAX(price) FROM products");
$max_price = mysqli_fetch_array($query_select_max_price)[0];
echo $max_price;
?>],
            connect: true,
            range: {
                'min': 0,
                'max': <?php echo $max_price; ?>
            }
        });
        slider.noUiSlider.on('slide', function () {
            document.getElementById("price-min").innerHTML = "$" + slider.noUiSlider.get()[0];
            document.getElementById("price-max").innerHTML = "$" + slider.noUiSlider.get()[1];
        });
        slider.noUiSlider.on('change', function () {
            getproducts(0, $(".grid-view-active").attr("data-grid"));
        });

        getproducts();

        var offsetY = $(".products_left").offset().top;

        $(window).scroll(function () {
            var scrollTop = $(window).scrollTop();

            if (scrollTop > offsetY && ($(document).scrollTop() + window.innerHeight < $('.footer-wrap').offset().top || ($(".products_left").css("position") == "absolute") && scrollTop < $('.products_left').offset().top)) {
                $(".products_left").css({"position": "fixed", "top": "0px"});
               
            }
            else if (scrollTop <= offsetY) {
                $(".products_left").css({"position": "relative", "top": "0px"});
                
            }
            else if ($('.products_left').offset().top + $('.products_left').height()
                    >= $('.footer-wrap').offset().top - 50) {
                $('.products_left').css({'position': 'absolute', 'top': $('.footer-wrap').offset().top - $('.products_left').height() - 50});
               
            }

            /*if (scrollTop <= offsetY) {
             $(".products_left").css({"position": "relative", "top": "0px"});
             }
             else if (scrollTop > offsetY || $(document).scrollTop() + window.innerHeight < $('.footer-wrap').offset().top){
             $(".products_left").css({"position": "fixed", "top": "0px"});
             }
             else if ($('.products_left').offset().top + $('.products_left').height()
             >= $('.footer-wrap').offset().top - 50){
             $('.products_left').css({'position': 'absolute', 'top' : $('.footer-wrap').offset().top - $('.products_left').height() - 50});
             }*/


            /*if (scrollTop > offsetY) {
             $(".products_left").css({"position": "fixed", "top": "0px"});
             }
             else if (scrollTop > $(document).height() - 800) {
             $(".products_left").css({"position": "relative", "top": ($(document).height() - 1000) + "px"});
             }
             else if (scrollTop <= offsetY) {
             $(".products_left").css({"position": "relative", "top": "0px"});
             }*/
        });

        $(".grid-view").click(function () {
            $(".grid-view").removeClass("grid-view-active");
            $(this).addClass("grid-view-active");
        });

        $("#products_list").on('click', '.add-to-wishlist', function () {
            $this = $(this);
            $this.next().stop(true).fadeIn(150, function () {
                $this.next().delay(1500).fadeOut(150);
            });
        });

        $(".filter-title").click(function () {
            $(this).next().stop().slideToggle();
            $(this).toggleClass("filter-title-rotate");
        });
    });
</script>
<?php
include_once("footer.php");
?>