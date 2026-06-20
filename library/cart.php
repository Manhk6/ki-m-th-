<?php
function add_cart($id)
{

    $temp = get_detail_product_byID($id);
    $sl = 1;
    if (isset($_SESSION['cart']) && array_key_exists($id, $_SESSION['cart']['buy'])) {
        $sl = $_SESSION['cart']['buy'][$id]['sl'] + 1;
    }
    $_SESSION['cart']['buy'][$id] = array(
        'id' => $temp['id'],
        'url_detailCart' => $temp['url_detailCart'],
        'name_product' => $temp['name_product'],
        'price' => $temp['price'],
        'code' => $temp['code'],
        'avt' => $temp['avt'],
        'sl' => $sl,
        'sub_total' => $sl * $temp['price']
    );

}

function update_infor_cart()
{
    $num_order = 0;
    $total = 0;
    foreach ($_SESSION['cart']['buy'] as $value) {
        $num_order += $value['sl'];
        $total += $value['sub_total'];
    }

    $_SESSION['cart']['infor'] = array(
        'num_order' => $num_order,
        'total' => $total
    );
}

function get_list_buy_cart()
{
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart']['buy'] as &$value) {
            $value['url_delete'] = "?mod=cart&act=delete&id={$value['id']}";
        }
        return $_SESSION['cart']['buy'];
    }
    return false;
}



function delete_product($id = '')
{
    if (isset($_SESSION['cart'])) {
        if (!empty($id)) {
            unset($_SESSION['cart']['buy'][$id]);
            update_infor_cart();
        } else {
            unset($_SESSION['cart']);
        }
    }
}

function update_cart($update)
{
    foreach ($update as $key => $value) {
        $_SESSION['cart']['buy'][$key]['sl']=$value;
        $_SESSION['cart']['buy'][$key]['sub_total']=$value * $_SESSION['cart']['buy'][$key]['price'];
    }
    update_infor_cart();
}


?>