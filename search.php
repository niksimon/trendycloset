<?php
include_once("header.php");

$search = isset($_GET["search"]) ? $_GET["search"] : '';
?>
<p class="navigation-links"><a href='index.php'>Home</a></p>
<div class="products_left">
    <h3>FILTER BY:</h3>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <p class="filter-title">Category:</p>
        <div class="products_filter_block" style="height:70px">
            <?php
            $query_categories = mysqli_query($conn, "SELECT * FROM categories");
            while ($q = mysqli_fetch_array($query_categories, MYSQLI_ASSOC)) {
                echo "<span><input type='checkbox' name='categories' value='" . $q['id_category'] . "' id='category" . $q['id_category'] . "'/> <label for='category" . $q['id_category'] . "'>" . $q['category_name'] . "</label></span>";
            }
            ?>
        </div>
        <p class="filter-title">Type:</p>
        <div class="products_filter_block" style="height:70px">
            <?php
            $query_types = mysqli_query($conn, "SELECT * FROM types t");
            while ($q = mysqli_fetch_array($query_types, MYSQLI_ASSOC)) {
                echo "<span><input type='checkbox' name='types' value='" . $q['id_type'] . "' id='type" . $q['id_type'] . "'/> <label for='type" . $q['id_type'] . "'>" . $q['type_name'] . "</label></span>";
            }
            ?>
        </div>
        <p class="filter-title">Color:</p>
        <div class="products_filter_block" style="height:70px">
            <?php
            $query_colors = mysqli_query($conn, "SELECT * FROM colors");
            while ($q = mysqli_fetch_array($query_colors, MYSQLI_ASSOC)) {
                echo "<span><input style='outline: 1px solid " . $q['hex_code'] . "' type='checkbox' name='colors' value='" . $q['id_color'] . "' id='color" . $q['id_color'] . "'/> <label for='color" . $q['id_color'] . "'>" . $q['color_name'] . "</label></span>";
            }
            ?>
        </div>
        <p class="filter-title">Size:</p>
        <div class="products_filter_block">
            <?php
            $query_sizes = mysqli_query($conn, "SELECT * FROM sizes");
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
        echo "SEARCH RESULTS FOR <span style='font-weight:bold'>$search</span>";
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
                    var colors = "", sizes = "", types = "", categories = "";
                    var sort_by = document.getElementById("sort").value;
                    
                    for (var i = 0; i < document.getElementsByName("categories").length; i++) {
            var category = document.getElementsByName("categories")[i];
                    if (category.checked) {
            if (categories != "")
                    categories += "," + category.value;
                    else
                    categories += category.value;
            }
            }
                    
                    for (var i = 0; i < document.getElementsByName("types").length; i++) {
            var type = document.getElementsByName("types")[i];
                    if (type.checked) {
            if (types != "")
                    types += "," + type.value;
                    else
                    types += type.value;
            }
            }

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
                    xhttp.open("GET", "getproducts.php?colors=" + colors + "&types=" + types + "&categories=" + categories + "&sizes=" + sizes + "&sort=" + sort_by + "&page=" + page + "&grid=" + grid + "&price_min=" + price_min + "&price_max=" + price_max + "&search=<?php
if (isset($_GET['search']))
    echo $_GET['search'];
else
    echo '0';
?>", true);
                    xhttp.send();
            }

    $(document).ready(function () {
    const categories = document.getElementsByName("categories");
            const types = document.getElementsByName("types");
            const colors = document.getElementsByName("colors");
            const sizes = document.getElementsByName("sizes");
            categories.forEach(category => category.addEventListener("change", function () {
    getproducts(0, $(".grid-view-active").attr("data-grid"));
    }));
            types.forEach(type => type.addEventListener("change", function () {
            getproducts(0, $(".grid-view-active").attr("data-grid"));
            }));
            colors.forEach(color => color.addEventListener("change", function () {
            getproducts(0, $(".grid-view-active").attr("data-grid"));
            }));
            sizes.forEach(size => size.addEventListener("change", function () {
            getproducts(0, $(".grid-view-active").attr("data-grid"));
            }));
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