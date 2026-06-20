<?php
if (!isset($_SESSION['user'])) {
    redirect('?mod=auth&act=login');
}

global $pdo;
$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
}

redirect('?mod=admin&act=main&msg=deleted');
?>
