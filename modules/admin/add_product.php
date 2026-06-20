<?php
if (!isset($_SESSION['user'])) {
    redirect('?mod=auth&act=login');
}
get_header();

$error = '';
$categories = get_all_categories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $pdo;

    $name    = trim($_POST['name_product']);
    $price   = (int)$_POST['price'];
    $code    = trim($_POST['code']);
    $content = trim($_POST['product_content']);
    $decs    = trim($_POST['product_decs']);
    $id_type = (int)$_POST['id_type'];
    $avt     = trim($_POST['avt']); // link URL nếu có

    if (empty($name) || empty($price) || empty($id_type)) {
        $error = "Vui lòng điền đầy đủ thông tin bắt buộc!";
    } else {

        // Xử lý upload ảnh nếu có chọn file
        if (isset($_FILES['avt_file']) && $_FILES['avt_file']['error'] === 0) {
            $file     = $_FILES['avt_file'];
            $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed  = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

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
                    $avt = $dest; // dùng đường dẫn local
                } else {
                    $error = "Upload ảnh thất bại, thử lại!";
                }
            }
        }

        if (empty($error)) {
            $stmt = $pdo->prepare("INSERT INTO products (name_product, price, code, avt, product_content, product_decs, id_type) VALUES (?,?,?,?,?,?,?)");
            $stmt->execute([$name, $price, $code, $avt, $content, $decs, $id_type]);
            redirect('?mod=admin&act=main&msg=added');
        }
    }
}
?>

<div id="content" style="max-width:700px; margin:30px auto; padding:0 20px;">
    <h2 style="margin-bottom:20px;">Thêm sản phẩm mới</h2>

    <?php if ($error): ?>
        <p style="color:red; margin-bottom:15px;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:12px;">

        <label>Tên sản phẩm <span style="color:red">*</span></label>
        <input type="text" name="name_product" placeholder="Tên sản phẩm"
               value="<?php echo htmlspecialchars($_POST['name_product'] ?? ''); ?>"
               style="padding:10px; border:1px solid #ccc; border-radius:4px;">

        <label>Giá (VNĐ) <span style="color:red">*</span></label>
        <input type="number" name="price" placeholder="Ví dụ: 18900000"
               value="<?php echo $_POST['price'] ?? ''; ?>"
               style="padding:10px; border:1px solid #ccc; border-radius:4px;">

        <label>Mã sản phẩm</label>
        <input type="text" name="code" placeholder="Ví dụ: wa#7"
               value="<?php echo htmlspecialchars($_POST['code'] ?? ''); ?>"
               style="padding:10px; border:1px solid #ccc; border-radius:4px;">

        <label>Ảnh sản phẩm</label>
        <div style="border:1px solid #ccc; border-radius:4px; padding:15px; background:#fafafa;">
            <p style="margin-bottom:8px; font-size:13px; color:#666;">Chọn 1 trong 2 cách:</p>

            <!-- Cách 1: Upload từ máy -->
            <label style="font-weight:bold; font-size:13px;">📁 Upload từ máy tính:</label>
            <input type="file" name="avt_file" accept="image/*"
                   style="display:block; margin:6px 0 12px; padding:6px; border:1px solid #ddd; border-radius:4px; width:100%;"
                   onchange="previewImage(this)">

            <!-- Preview ảnh -->
            <img id="preview" src="" alt="" style="display:none; max-width:150px; border-radius:4px; margin-bottom:10px;">

            <!-- Cách 2: Dán link URL -->
            <label style="font-weight:bold; font-size:13px;">🔗 Hoặc dán link URL ảnh:</label>
            <input type="text" name="avt" placeholder="https://example.com/image.jpg"
                   value="<?php echo htmlspecialchars($_POST['avt'] ?? ''); ?>"
                   style="display:block; width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; margin-top:6px;"
                   oninput="previewUrl(this.value)">
        </div>

        <label>Danh mục <span style="color:red">*</span></label>
        <select name="id_type" style="padding:10px; border:1px solid #ccc; border-radius:4px;">
            <option value="">-- Chọn danh mục --</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>"
                <?php echo (isset($_POST['id_type']) && $_POST['id_type'] == $cat['id']) ? 'selected' : ''; ?>>
                <?php echo ucfirst($cat['name_cat']); ?>
            </option>
            <?php endforeach; ?>
        </select>

        <label>Mô tả ngắn</label>
        <textarea name="product_decs" rows="3" placeholder="Mô tả ngắn về sản phẩm..."
                  style="padding:10px; border:1px solid #ccc; border-radius:4px;"><?php echo htmlspecialchars($_POST['product_decs'] ?? ''); ?></textarea>

        <label>Nội dung chi tiết</label>
        <textarea name="product_content" rows="6" placeholder="Nội dung mô tả chi tiết..."
                  style="padding:10px; border:1px solid #ccc; border-radius:4px;"><?php echo htmlspecialchars($_POST['product_content'] ?? ''); ?></textarea>

        <div style="display:flex; gap:10px;">
            <button type="submit"
                    style="padding:10px 20px; background:#e22929; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:15px;">
                Thêm sản phẩm
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
    } else {
        preview.style.display = 'none';
    }
}
</script>

<?php get_footer(); ?>
