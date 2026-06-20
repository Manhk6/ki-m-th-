<?php get_header(); ?>

<div id="content" style="max-width:500px; margin: 40px auto; padding: 0 20px;">
    <h2 style="margin-bottom:20px;">Đăng ký tài khoản</h2>

    <?php
    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        global $pdo;

        $full_name = trim($_POST['full_name']);
        $username  = trim($_POST['username']);
        $email     = trim($_POST['email']);
        $password  = $_POST['password'];
        $repassword = $_POST['repassword'];

        // Validate
        if (empty($full_name) || empty($username) || empty($email) || empty($password)) {
            $error = "Vui lòng điền đầy đủ thông tin!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email không hợp lệ!";
        } elseif (strlen($password) < 6) {
            $error = "Mật khẩu phải có ít nhất 6 ký tự!";
        } elseif ($password !== $repassword) {
            $error = "Mật khẩu nhập lại không khớp!";
        } else {
            // Kiểm tra username/email đã tồn tại chưa
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);

            if ($stmt->rowCount() > 0) {
                $error = "Tên đăng nhập hoặc email đã tồn tại!";
            } else {
                // Lưu vào database
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $email, $hashed, $full_name]);
                $success = "Đăng ký thành công! <a href='?mod=auth&act=login'>Đăng nhập ngay</a>";
            }
        }
    }
    ?>

    <?php if ($error): ?>
        <p style="color:red; margin-bottom:15px;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color:green; margin-bottom:15px;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST" style="display:flex; flex-direction:column; gap:12px;">
        <input type="text" name="full_name" placeholder="Họ và tên"
            value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>"
            style="padding:10px; border:1px solid #ccc; border-radius:4px;">

        <input type="text" name="username" placeholder="Tên đăng nhập"
            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
            style="padding:10px; border:1px solid #ccc; border-radius:4px;">

        <input type="email" name="email" placeholder="Email"
            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
            style="padding:10px; border:1px solid #ccc; border-radius:4px;">

        <input type="password" name="password" placeholder="Mật khẩu (tối thiểu 6 ký tự)"
            style="padding:10px; border:1px solid #ccc; border-radius:4px;">

        <input type="password" name="repassword" placeholder="Nhập lại mật khẩu"
            style="padding:10px; border:1px solid #ccc; border-radius:4px;">

        <button type="submit"
            style="padding:10px; background:#e22929; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:16px;">
            Đăng ký
        </button>
    </form>

    <p style="margin-top:15px;">Đã có tài khoản? <a href="?mod=auth&act=login">Đăng nhập</a></p>
</div>

<?php get_footer(); ?>
