<?php
@session_start();
include("connection.php");

$page_name = basename($_SERVER["PHP_SELF"]);
if(!isset($_SESSION["id_user"]) && ($page_name == "account.php" || $page_name == "cart.php" || $page_name == "wishlist.php")){
    header("Location: login.php");
}

if (isset($_POST["btnLogin"])) {
    $username = $_POST["loginUsername"];
    $password = md5(trim($_POST["loginPassword"]));
    $query_user = mysqli_query($conn, "SELECT * FROM users u JOIN roles r ON u.id_role=r.id_role WHERE username='$username' AND password='$password'");
    if (mysqli_num_rows($query_user) == 0) {
        $login_error = true;
    } else {
        $r = mysqli_fetch_array($query_user, MYSQLI_ASSOC);
        $_SESSION["id_user"] = $r["id_user"];
        $_SESSION["id_role"] = $r["id_role"];
        $_SESSION["role_name"] = $r["role"];
        $_SESSION["username"] = $r["username"];

        header("Location: index.php");
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo isset($page_title) ? $page_title : "Trendy Closet"; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta name="description" content=""/>
        <meta name="keywords" content=""/>
        <meta name="author" content="Nikola Simonović"/>
        <meta name="robots" content="noindex, nofollow"/>
        <link rel="shortcut icon" href="img/favicon.png" type="image/png"/>
        <link href='https://fonts.googleapis.com/css?family=Ubuntu+Condensed|Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
        <script src="fancyBox/jquery.fancybox.pack.js"></script>
        <script src="fancyBox/jquery.fancybox-thumbs.js"></script>
        <link rel="stylesheet" type="text/css" href="css/main.css"/>
        <?php
        if (isset($css_files)) {
            foreach ($css_files as $file) {
                echo "<link href='css/" . $file . ".css' rel='stylesheet' type='text/css'>\n";
            }
        }
        if (isset($js_files)) {
            foreach ($js_files as $file) {
                echo "<script src='" . $file . "'></script>\n";
            }
        }
        ?>
        <link rel="stylesheet" type="text/css" href="fancyBox/jquery.fancybox.css"/>
        <link rel="stylesheet" type="text/css" href="fancyBox/jquery.fancybox-thumbs.css"/>
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css"/>
        <link href="noUiSlider.8.5.1/nouislider.css" type="text/css" rel="stylesheet">
        <script src="noUiSlider.8.5.1/nouislider.min.js" type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                $(".menu-item").mouseover(function () {
                    var img = $(this).attr("data-image");
                    $("." + $(this).attr("data-target")).attr("src", "img/dropdown_menu_img/" + img + ".jpg");
                });
                $(".hasdropdown").hover(function () {
                    $(this).find(".menu-block").stop(true, true).fadeIn(200);
                }, function () {
                    $(this).find(".menu-block").stop(true, true).fadeOut(200);
                });
                $("#search").focus(function () {
                    $(this).css("width", "280px");
                    $(this).next().css("opacity", 1);
                });
                ;
                $("#search").blur(function () {
                    $(this).css("width", "120px");
                    $(this).next().css("opacity", 0.7);
                });
                ;
            });
        </script>
    </head>
    <body>
        <div class="header-wrap">
            <div class="header">
                <div class="title">
                    <h1><a href="index.php">Trendy Closet</a></h1>
                    <h2>Online clothing shop</h2>
                </div>
                <div class="menu-upper">
                    <ul>
                        <li class="menu-first"><a href="wishlist.php"><i class="fa fa-heart-o"></i><i class="menu-tooltip">Wishlist</i></a></li>
                        <li class="menu-first"><a href="cart.php"><i class="fa fa-shopping-cart"></i><i class="menu-tooltip">Shopping cart</i></a></li>
                        <?php
                        if (!isset($_SESSION["id_user"])) {
                            ?>
                            <li><a href="login.php">Sign in</a></li>
                            <li><a href="register.php">Register</a></li>
                        <?php } else {
                            ?>
                            <li><a href="account.php">My account</a></li>
                            <li><a href="logout.php">Sign out</a></li>
                            <?php if ($_SESSION["id_role"] == 1) { ?>
                                <li><a href="admin/index.php" target='_blank'>Admin</a></li>
                                <?php
                            }
                        }
                        ?>
                        <li><a href="author.php">About</a></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <div class="menu-lower">
                <ul class="menu-main">
                    <?php
                    $query_menu = mysqli_query($conn, "SELECT * FROM main_menu");
                    $i = 1;
                    while ($r = mysqli_fetch_array($query_menu, MYSQLI_ASSOC)) {
                        echo "<li class='hasdropdown'><a href='shop.php?category=" . $r["name"] . "'>" . $r['name'] . "</a>\n";
                        $query_dropdown = mysqli_query($conn, "SELECT * FROM dropdown_menu dm JOIN main_menu m ON dm.id_menu=m.id_menu WHERE dm.id_menu=" . $r['id_menu']);

                        echo "<div class='menu-block'><div class='menu-block-left'>
          <ul>";
                        $j = 1;
                        while ($q = mysqli_fetch_array($query_dropdown, MYSQLI_ASSOC)) {
                            echo "<li><a href='shop.php?category=" . $q['name'] . "&amp;type=" . $q['id_menu_item'] . "' class='menu-item' data-target='type-img$i' data-image='menu_" . strtolower($r['name']) . "_$j'>" . $q['item_menu_name'] . "</a></li>\n";
                            $j++;
                        }
                        echo "</ul></div><div class='menu-block-right'>
          <img class='type-img$i' src='img/dropdown_menu_img/menu_" . strtolower($r['name']) . "_1.jpg' alt='clothing type image'/>
          </div></div></li>\n";
                        $i++;
                    }
                    ?>

                    <li class="menu-search">
                        <form method="get" action="search.php">
                            <input type="text" name="search" id="search" placeholder="Search..."/>
                            <i class="fa fa-search"></i>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-wrap">
            <div class="main">