<?php
session_start();
ob_start();
// database
require 'library/db.php';
// dữ liệu 
require 'data/page.php';
require 'data/product.php';
// hàm thực hiện
require 'library/require_file.php';
require 'library/data.php';
require 'library/get_data_page.php';
require 'library/get_data_product.php';
require 'library/cart.php';

$mod = isset($_GET['mod']) ? $_GET['mod'] : 'home';
$act = isset($_GET['act']) ? $_GET['act'] : 'main';
$path = "modules/{$mod}/{$act}.php";

if (file_exists($path)) {
    require $path;
} else {
    require 'inc/404.php';
}
?>
