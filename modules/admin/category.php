<?php
if (!isset($_SESSION['user'])) {
    redirect('?mod=auth&act=login');
}
get_header();
global $pdo;

$error   = '';
$success = '';
$edit_cat = null;

// Xử lý THÊM danh mục
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name_cat']);
    if (empty($name)) {
        $error = "Vui lòng nhập tên danh mục!";
    } else {
        // Kiểm tra tên đã tồn tại chưa
        $check = $pdo->prepare("SELECT id FROM categories WHERE name_cat = ?");
        $check->execute([$name]);
        if ($check->rowCount() > 0) {
            $error = "Danh mục này đã tồn tại!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (name_cat) VALUES (?)");
            $stmt->execute([$name]);
            $success = "✅ Thêm danh mục thành công!";
        }
    }
}

// Xử lý SỬA danh mục
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id   = (int)$_POST['id'];
    $name = trim($_POST['name_cat']);
    if (empty($name)) {
        $error = "Vui lòng nhập tên danh mục!";
    } else {
        $stmt = $pdo->prepare("UPDATE categories SET name_cat = ? WHERE id = ?");
        $stmt->execute([$name, $id]);
        $success = "✅ Cập nhật danh mục thành công!";
    }
}

// Xử lý XÓA danh mục
if (isset($_GET['delete_cat'])) {
    $id = (int)$_GET['delete_cat'];
    // Kiểm tra còn sản phẩm trong danh mục không
    $check = $pdo->prepare("SELECT COUNT(*) FROM products WHERE id_type = ?");
    $check->execute([$id]);
    $count = $check->fetchColumn();
    if ($count > 0) {
        $error = "❌ Không thể xóa! Danh mục này còn {$count} sản phẩm.";
    } else {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $success = "✅ Xóa danh mục thành công!";
    }
}

// Lấy thông tin danh mục cần sửa
if (isset($_GET['edit_cat'])) {
    $id = (int)$_GET['edit_cat'];
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $edit_cat = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Lấy danh sách tất cả danh mục + đếm số sản phẩm
$stmt = $pdo->query("
    SELECT c.*, COUNT(p.id) as total_product
    FROM categories c
    LEFT JOIN products p ON c.id = p.id_type
    GROUP BY c.id
    ORDER BY c.id ASC
");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="content" style="padding:20px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>Quản lý danh mục</h2>
        <a href="?mod=admin&act=main"
           style="background:#555; color:#fff; padding:8px 16px; border-radius:4px;">
            ← Quản lý sản phẩm
        </a>
    </div>

    <?php if ($error): ?>
        <p style="color:red; background:#fff0f0; padding:10px; border-radius:4px; margin-bottom:15px;">
            <?php echo $error; ?>
        </p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color:green; background:#f0fff0; padding:10px; border-radius:4px; margin-bottom:15px;">
            <?php echo $success; ?>
        </p>
    <?php endif; ?>

    <div style="display:flex; gap:30px; align-items:flex-start; flex-wrap:wrap;">

        <!-- FORM THÊM / SỬA -->
        <div style="width:300px; background:#f9f9f9; border:1px solid #ddd; border-radius:6px; padding:20px;">
            <?php if ($edit_cat): ?>
                <h3 style="margin-bottom:15px;">✏️ Sửa danh mục</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?php echo $edit_cat['id']; ?>">
                    <label style="font-size:13px; color:#555;">Tên danh mục</label>
                    <input type="text" name="name_cat"
                           value="<?php echo htmlspecialchars($edit_cat['name_cat']); ?>"
                           style="display:block; width:100%; padding:9px; border:1px solid #ccc; border-radius:4px; margin:6px 0 12px;">
                    <div style="display:flex; gap:8px;">
                        <button type="submit"
                                style="flex:1; padding:9px; background:#f0ad4e; color:#fff; border:none; border-radius:4px; cursor:pointer;">
                            Lưu thay đổi
                        </button>
                        <a href="?mod=admin&act=category"
                           style="flex:1; padding:9px; background:#ccc; color:#333; border-radius:4px; text-align:center;">
                            Hủy
                        </a>
                    </div>
                </form>
            <?php else: ?>
                <h3 style="margin-bottom:15px;">➕ Thêm danh mục mới</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="add">
                    <label style="font-size:13px; color:#555;">Tên danh mục</label>
                    <input type="text" name="name_cat" placeholder="Ví dụ: tablet, smartwatch..."
                           style="display:block; width:100%; padding:9px; border:1px solid #ccc; border-radius:4px; margin:6px 0 12px;">
                    <button type="submit"
                            style="width:100%; padding:9px; background:#e22929; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:15px;">
                        Thêm danh mục
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <!-- DANH SÁCH DANH MỤC -->
        <div style="flex:1; min-width:300px;">
            <h3 style="margin-bottom:15px;">📋 Danh sách danh mục</h3>
            <?php if (empty($categories)): ?>
                <p style="color:#999;">Chưa có danh mục nào.</p>
            <?php else: ?>
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr style="background:#f5f5f5;">
                        <th style="padding:10px; border:1px solid #ddd;">ID</th>
                        <th style="padding:10px; border:1px solid #ddd;">Tên danh mục</th>
                        <th style="padding:10px; border:1px solid #ddd;">Số sản phẩm</th>
                        <th style="padding:10px; border:1px solid #ddd;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td style="padding:10px; border:1px solid #ddd; text-align:center;"><?php echo $cat['id']; ?></td>
                        <td style="padding:10px; border:1px solid #ddd; font-weight:bold;">
                            <?php echo ucfirst(htmlspecialchars($cat['name_cat'])); ?>
                        </td>
                        <td style="padding:10px; border:1px solid #ddd; text-align:center;">
                            <span style="background:#e22929; color:#fff; padding:2px 8px; border-radius:10px; font-size:12px;">
                                <?php echo $cat['total_product']; ?> sản phẩm
                            </span>
                        </td>
                        <td style="padding:10px; border:1px solid #ddd; text-align:center;">
                            <a href="?mod=admin&act=category&edit_cat=<?php echo $cat['id']; ?>"
                               style="background:#f0ad4e; color:#fff; padding:4px 10px; border-radius:3px; margin-right:5px;">
                                Sửa
                            </a>
                            <a href="?mod=admin&act=category&delete_cat=<?php echo $cat['id']; ?>"
                               style="background:#d9534f; color:#fff; padding:4px 10px; border-radius:3px;"
                               onclick="return confirm('Xóa danh mục này?')">
                                Xóa
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php get_footer(); ?>
