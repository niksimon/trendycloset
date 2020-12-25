<?php
@session_start();
if (isset($_SESSION['role_name']) && $_SESSION['role_name'] == 'admin') {
    header("Location: list-products.php");
}
else{
    header("Location: ../index.php");
}