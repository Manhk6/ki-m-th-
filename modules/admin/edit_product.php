<?php
if (!isset($_SESSION['user'])) {
    redirect('?mod=auth&act=login');
}
get_header();

global $pdo;
$id = (int)($_GET['id'] ?? 0);
$categories = get_all_categories();

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "<p style='padding:20px'>Sản phẩm không tồn tại.</p>";
    get_footer(); exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name_product']);
    $price   = (int)$_POST['price'];
    $code    = trim($_POST['code']);
    $content = trim($_POST['product_content']);
    $decs    = trim($_POST['product_decs']);
    $id_type = (int)$_POST['id_type'];
    $avt     = trim($_POST['avt']) ?: $product['avt']; // giữ ảnh cũ nếu không nhập URL mới

    if (empty($name) || empty($price) || empty($id_type)) {
        $error = "Vui lòng điền đầy đủ thông tin bắt buộc!";
    } else {

        // Xử lý upload ảnh mới nếu có
        if (isset($_FILES['avt_file']) && $_FILES['avt_file']['error'] === 0) {
            $file    = $_FILES['avt_file'];
            $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

            if (!in_array($ext, $allowed)) {
                $error = "Chỉ chấp nhận file ảnh: jpg, jpeg, png, webp, gif!";
            } elseif ($file['size'] > 5 * 1024 * 1024) {
                $error = "Ảnh không được vượt quá 5MB!";
            } else {
                $upload_dir = 'public/images/products/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $filename = time() . '_' . uniqid() . '.' . $ext;
                $dest     = $upload_dir . $filename;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $avt = $dest;
                } else {
                    $error = "Upload ảnh thất bại, thử lại!";
                }
            }
        }

        if (empty($error)) {
            $stmt = $pdo->prepare("UPDATE products SET name_product=?, price=?, code=?, avt=?, product_content=?, product_decs=?, id_type=? WHERE id=?");
            $stmt->execute([$name, $price, $code, $avt, $content, $decs, $id_type, $id]);
            redirect('?mod=admin&act=main&msg=updated');
        }
    }
}
?>

<div id="content" style="max-width:700px; margin:30px auto; padding:0 20px;">
    <h2 style="margin-bottom:20px;">Sửa sản phẩm</h2>

    <?php if ($error): ?>
        <p style="color:red; margin-bottom:15px;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:12px;">

        <label>Tên sản phẩm <span style="color:red">*</span></label>
        <input type="text" name="name_product"
               value="<?php echo htmlspecialchars($_POST['name_product'] ?? $product['name_product']); ?>"
               style="padding:10px; border:1px solid #ccc; border-radius:4px;">

        <label>Giá (VNĐ) <span style="color:red">*</span></label>
        <input type="number" name="price"
               value="<?php echo $_POST['price'] ?? $product['price']; ?>"
               style="padding:10px; border:1px solid #ccc; border-radius:4px;">

        <label>Mã sản phẩm</label>
        <input type="text" name="code"
               value="<?php echo htmlspecialchars($_POST['code'] ?? $product['code']); ?>"
               style="padding:10px; border:1px solid #ccc; border-radius:4px;">

        <label>Ảnh sản phẩm</label>
        <div style="border:1px solid #ccc; border-radius:4px; padding:15px; background:#fafafa;">

            <!-- Ảnh hiện tại -->
            <?php if ($product['avt']): ?>
            <p style="font-size:13px; color:#666; margin-bottom:6px;">Ảnh hiện tại:</p>
            <img id="preview" src="<?php echo $product['avt']; ?>" style="max-width:150px; border-radius:4px; margin-bottom:12px; display:block;">
            <?php else: ?>
            <img id="preview" src="" style="max-width:150px; border-radius:4px; margin-bottom:12px; display:none;">
            <?php endif; ?>

            <label style="font-weight:bold; font-size:13px;">📁 Upload ảnh mới từ máy tính:</label>
            <input type="file" name="avt_file" accept="image/*"
                   style="display:block; margin:6px 0 12px; padding:6px; border:1px solid #ddd; border-radius:4px; width:100%;"
                   onchange="previewImage(this)">

            <label style="font-weight:bold; font-size:13px;">🔗 Hoặc dán link URL ảnh mới:</label>
            <input type="text" name="avt" placeholder="https://example.com/image.jpg"
                   value="<?php echo htmlspecialchars($_POST['avt'] ?? ''); ?>"
                   style="display:block; width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; margin-top:6px;"
                   oninput="previewUrl(this.value)">
            <small style="color:#999;">Để trống = giữ ảnh cũ</small>
        </div>

        <label>Danh mục <span style="color:red">*</span></label>
        <select name="id_type" style="padding:10px; border:1px solid #ccc; border-radius:4px;">
            <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>"
                <?php echo (($_POST['id_type'] ?? $product['id_type']) == $cat['id']) ? 'selected' : ''; ?>>
                <?php echo ucfirst($cat['name_cat']); ?>
            </option>
            <?php endforeach; ?>
        </select>

        <label>Mô tả ngắn</label>
        <textarea name="product_decs" rows="3"
                  style="padding:10px; border:1px solid #ccc; border-radius:4px;"><?php echo htmlspecialchars($_POST['product_decs'] ?? $product['product_decs']); ?></textarea>

        <label>Nội dung chi tiết</label>
        <textarea name="product_content" rows="6"
                  style="padding:10px; border:1px solid #ccc; border-radius:4px;"><?php echo htmlspecialchars($_POST['product_content'] ?? $product['product_content']); ?></textarea>

        <div style="display:flex; gap:10px;">
            <button type="submit"
                    style="padding:10px 20px; background:#f0ad4e; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:15px;">
                Lưu thay đổi
            </button>
            <a href="?mod=admin&act=main"
               style="padding:10px 20px; background:#ccc; color:#333; border-radius:4px; line-height:1.5;">
                Hủy
            </a>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function previewUrl(url) {
    const preview = document.getElementById('preview');
    if (url) {
        preview.src = url;
        preview.style.display = 'block';
    }
}
</script>

<?php get_footer(); ?>
