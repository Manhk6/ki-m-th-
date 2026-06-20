<?php
function get_cat_byId($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $cat = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($cat) {
        $cat['url'] = "?mod=product&act=main&id_cat={$id}";
        return $cat;
    }
    return false;
}

function get_all_categories() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM categories");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_list_product_by_catId($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id_type = ?");
    $stmt->execute([$id]);
    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($list as &$p) {
        $p['url'] = "?mod=product&act=detail&id={$p['id']}";
    }
    return $list;
}

function get_detail_product_byID($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $p = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($p) {
        $p['url_addCart']    = "?mod=cart&act=add&id={$id}";
        $p['url_detailCart'] = "?mod=product&act=detail&id={$id}";
        return $p;
    }
    return false;
}

function get_all_products() {
    global $pdo;
    $stmt = $pdo->query("SELECT p.*, c.name_cat FROM products p LEFT JOIN categories c ON p.id_type = c.id ORDER BY p.id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
