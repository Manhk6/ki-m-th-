<?php 
if(isset($_POST['update_cart'])){
    update_cart($_POST['sl']);
    redirect("?mod=cart&act=show");
}
?> 