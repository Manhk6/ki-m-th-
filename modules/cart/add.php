<?php
$id = $_GET['id'];
add_cart($id);
update_infor_cart();
//   session_unset();
redirect("?mod=cart&act=show");

?>