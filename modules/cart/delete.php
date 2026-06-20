<?php 
echo$id=$_GET['id'];
delete_product($id);
redirect("?mod=cart&act=show");
?>