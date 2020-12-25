<?php
@session_start();
include("../connection.php");
if (!isset($_SESSION['role_name']) || $_SESSION['role_name'] != 'admin') {
    header("Location: ../index.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo isset($page_title) ? $page_title : "Trendy Closet - Admin Panel"; ?></title>
        <link rel="shortcut icon" href="../img/favicon.png" type="image/png"/>
        <link href='https://fonts.googleapis.com/css?family=Ubuntu+Condensed' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="../css/admin.css"/>
    </head>
    <body>
        <div class="menu">
            <h1>ADMIN PANEL</h1>
            <h2>Trendy Closet</h2>
            <ul>
                <li><a href="../index.php">Home page</a></li>
                <li><a href="add-product.php">Add product</a></li>
                <li><a href="list-products.php">Products</a></li>
                <li><a href="list-users.php">Users</a></li>
            </ul>
        </div>
        <div class="main">