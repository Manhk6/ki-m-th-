<?php
function get_sidebar(){
    $path_sidebar='inc/sidebar.php';
    if(file_exists($path_sidebar)){
        require $path_sidebar;
    }
}
function get_header($version = '') {
    if (!empty($version)) {
        $path_header = "inc/header-{$version}.php";
    } else {
        
        $path_header = 'inc/header.php';
    }


    if (file_exists($path_header)) {
        require $path_header;
    } else {
        echo "Khong ton tai duong dan {$path_header}";
    }
}

function get_footer() {
    $path_footer = 'inc/footer.php';
    if (file_exists($path_footer)) {
        require 'inc/footer.php';
    } else {
        echo "Khong ton tai duong dan {$path_footer}";
    }
}
function redirect($url="?home=main"){
    if(!empty($url)){
        header("location:{$url}");
    }
}
?>

