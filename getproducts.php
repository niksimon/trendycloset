<?php

@session_start();
include("connection.php");

$categories = isset($_REQUEST["categories"]) ? $_REQUEST["categories"] : null;
$types = $_REQUEST["types"];
$colors = $_REQUEST["colors"];
$sizes = $_REQUEST["sizes"];
$sort = $_REQUEST["sort"];
$category = isset($_REQUEST["category"]) ? $_REQUEST["category"] : null;
$type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : null;
$price_min = $_REQUEST["price_min"];
$price_max = $_REQUEST["price_max"];
$grid = isset($_REQUEST["grid"]) ? $_REQUEST["grid"] : 3;

$search = isset($_REQUEST["search"]) && !empty($_REQUEST["search"]) ? $_REQUEST["search"] : null;

$where = "WHERE price BETWEEN $price_min AND $price_max";

if ($type != null && $type != 0) {
    $query_type = mysqli_query($conn, "SELECT id_type FROM dropdown_menu WHERE id_menu_item=" . $_GET["type"]);
    $where .= " AND c.category_name='$category' AND t.id_type=" . mysqli_fetch_array($query_type, MYSQLI_ASSOC)['id_type'];
} else if($category != null){
    $where .= " AND c.category_name='$category'";
}

if ($categories != null) {
    $where .= " AND id_category IN ($categories)";
}

if (!empty($types)) {
    $where .= " AND id_type IN ($types)";
}

if (!empty($colors)) {
    $where .= " AND id_color IN ($colors)";
}

switch ($sort) {
    case 0:
        $order_by = "ORDER BY date_added DESC";
        break;
    case 1:
        $order_by = "ORDER BY product_name";
        break;
    case 2:
        $order_by = "ORDER BY price DESC";
        break;
    case 3:
        $order_by = "ORDER BY price";
        break;
    default:
        $order_by = "";
        break;
}

$size_array = explode(',', $sizes);

if (isset($_REQUEST["page"]))
    $page = $_REQUEST["page"];
else
    $page = 0;

if ($grid == 2)
    $per_page = 6;
else if ($grid == 3)
    $per_page = 9;
else
    $per_page = 12;

$offset = $page * $per_page;

if ($type != 0) {
    $join_types = "JOIN types t ON p.id_type=t.id_type";
} else {
    $join_types = "";
}

if ($search == null) {
    $query = mysqli_query($conn, "SELECT * FROM products p JOIN categories c ON p.id_category=c.id_category $join_types $where $order_by LIMIT $per_page OFFSET $offset");
    $query_all = mysqli_query($conn, "SELECT * FROM products p JOIN categories c ON p.id_category=c.id_category $join_types $where $order_by");
} else {
    $where .= " AND product_name LIKE '%".$search."%'";
    $query = mysqli_query($conn, "SELECT * FROM products $where $order_by LIMIT $per_page OFFSET $offset");
    $query_all = mysqli_query($conn, "SELECT * FROM products $where $order_by");
}
$count = 0;

$wishlist = "";
if (!isset($_SESSION["id_role"])) {
    $notify = "You must be logged in to like!";
} else {
    $notify = "Item added to wishlist!";
}

while ($r = mysqli_fetch_array($query_all, MYSQLI_ASSOC)) {
    if (empty($sizes)) {
        $show = true;
    } else {
        $show = false;
        $query_sizes = mysqli_query($conn, "SELECT * FROM product_sizes ps JOIN sizes s ON ps.id_size=s.id_size WHERE id_product=" . $r['id_product']);
        while ($q = mysqli_fetch_array($query_sizes, MYSQLI_ASSOC)) {
            if (in_array($q['id_size'], $size_array)) {
                $show = true;
                break;
            }
        }
    }
    if ($show) {
        $count++;
    }
}
if ($count > 0)
    echo "<p style='padding-bottom: 12px; margin-top: -15px;'>Found $count items</p>";
else
    echo "<p style='padding-bottom: 12px; margin-top: -15px;'>Nothing found...</p>";
while ($r = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
    if (empty($sizes)) {
        $show = true;
    } else {
        $show = false;
        $query_sizes = mysqli_query($conn, "SELECT * FROM product_sizes ps JOIN sizes s ON ps.id_size=s.id_size WHERE id_product=" . $r['id_product']);
        while ($q = mysqli_fetch_array($query_sizes, MYSQLI_ASSOC)) {
            if (in_array($q['id_size'], $size_array)) {
                $show = true;
                break;
            }
        }
    }
    if ($show) {
        if ($grid == 2) {
            $grid_name = "grid2";
            $image_file = "image1Grid2";
        } else if ($grid == 3) {
            $grid_name = "grid3";
            $image_file = "image1Grid3";
        } else {
            $grid_name = "grid4";
            $image_file = "image1Grid4";
        }
        if (isset($_SESSION["id_role"])) {
            $wishlist = "onclick='addToWishlist(" . $r['id_product'] . ", " . $_SESSION["id_user"] . ")'";
        }
        echo "<div class='product product-$grid_name'><div class='product-img product-img-$grid_name'><a href='product.php?id_product=" . $r['id_product'] . "'><img src='uploads/product_images/" . $r['folder'] . "/$image_file.jpg'/></a><div class='product-pop-up'><i class='fa fa-heart add-to-wishlist' $wishlist></i><div class='wishlist-notify'><p>$notify</p></div></div></div><p>" . $r['product_name'] . "</p><p style='font-weight: 700'>$" . number_format($r['price'], 2) . "</p></div>";
    }
}

$num_pages = ceil($count / $per_page);
echo "<div class='clear'></div>";
if ($num_pages > 1) {
    if ($page > 0)
        echo "<a onclick='getproducts(" . ($page - 1) . ", $grid)' class='nav-link' href='javascript:void(0)'>&lt;</a>";
    for ($i = 0; $i < $num_pages; $i++) {
        if ($i == $page)
            $b = "background-color: #89c4ff";
        else
            $b = "";
        echo "<a onclick='getproducts($i, $grid)' class='nav-link' href='javascript:void(0)' style='$b'>" . ($i + 1) . "</a>";
    }
    if ($page < $num_pages - 1)
        echo "<a onclick='getproducts(" . ($page + 1) . ", $grid)' class='nav-link' href='javascript:void(0)'>&gt;</a>";
}

mysqli_close($conn);
?>