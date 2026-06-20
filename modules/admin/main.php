<?php
if (!isset($_SESSION['user'])) {
    redirect('?mod=auth&act=login');
}
get_header();
$products = get_all_products();
?>

<div id="content" style="padding: 20px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>Quản lý sản phẩm</h2>
        <div style="display:flex; gap:10px;">
            <a href="?mod=admin&act=category"
               style="background:#5bc0de; color:#fff; padding:8px 16px; border-radius:4px;">
                🗂️ Quản lý danh mục
            </a>
            <a href="?mod=admin&act=add_product"
               style="background:#e22929; color:#fff; padding:8px 16px; border-radius:4px;">
                + Thêm sản phẩm
            </a>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <p style="color:green; background:#f0fff0; padding:10px; border-radius:4px; margin-bottom:15px;">
            <?php
            $msgs = [
                'added'   => '✅ Thêm sản phẩm thành công!',
                'updated' => '✅ Cập nhật sản phẩm thành công!',
                'deleted' => '✅ Xóa sản phẩm thành công!',
            ];
            echo $msgs[$_GET['msg']] ?? '';
            ?>
        </p>
    <?php endif; ?>

    <table style="width:100%; border-collapse:collapse; font-size:14px;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th style="padding:10px; border:1px solid #ddd;">ID</th>
                <th style="padding:10px; border:1px solid #ddd;">Ảnh</th>
                <th style="padding:10px; border:1px solid #ddd;">Tên sản phẩm</th>
                <th style="padding:10px; border:1px solid #ddd;">Giá</th>
                <th style="padding:10px; border:1px solid #ddd;">Danh mục</th>
                <th style="padding:10px; border:1px solid #ddd;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
                <td style="padding:8px; border:1px solid #ddd; text-align:center;"><?php echo $p['id']; ?></td>
                <td style="padding:8px; border:1px solid #ddd; text-align:center;">
                    <img src="<?php echo $p['avt']; ?>" width="60" alt="">
                </td>
                <td style="padding:8px; border:1px solid #ddd;"><?php echo htmlspecialchars($p['name_product']); ?></td>
                <td style="padding:8px; border:1px solid #ddd; color:#e22929;">
                    <?php echo number_format($p['price'], 0, ',', '.'); ?> đ
                </td>
                <td style="padding:8px; border:1px solid #ddd;"><?php echo ucfirst($p['name_cat']); ?></td>
                <td style="padding:8px; border:1px solid #ddd; text-align:center;">
                    <a href="?mod=admin&act=edit_product&id=<?php echo $p['id']; ?>"
                       style="color:#fff; background:#f0ad4e; padding:4px 10px; border-radius:3px; margin-right:5px;">Sửa</a>
                    <a href="?mod=admin&act=delete_product&id=<?php echo $p['id']; ?>"
                       style="color:#fff; background:#d9534f; padding:4px 10px; border-radius:3px;"
                       onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php get_footer(); ?>
