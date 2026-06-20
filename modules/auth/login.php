<?php get_header(); ?>

<div id="content" style="max-width:500px; margin: 40px auto; padding: 0 20px;">
    <h2 style="margin-bottom:20px;">Đăng nhập</h2>

    <?php
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        global $pdo;

        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            $error = "Vui lòng điền đầy đủ thông tin!";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Lưu thông tin vào session
                $_SESSION['user'] = array(
                    'id'        => $user['id'],
                    'username'  => $user['username'],
                    'full_name' => $user['full_name'],
                    'email'     => $user['email']
                );
                redirect('?'); // Về trang chủ
            } else {
                $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
            }
        }
    }
    ?>

    <?php if ($error): ?>
        <p style="color:red; margin-bottom:15px;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" style="display:flex; flex-direction:column; gap:12px;">
        <input type="text" name="username" placeholder="Tên đăng nhập hoặc Email"
            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
            style="padding:10px; border:1px solid #ccc; border-radius:4px;">

        <input type="password" name="password" placeholder="Mật khẩu"
            style="padding:10px; border:1px solid #ccc; border-radius:4px;">

        <button type="submit"
            style="padding:10px; background:#e22929; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:16px;">
            Đăng nhập
        </button>
    </form>

    <p style="margin-top:15px;">Chưa có tài khoản? <a href="?mod=auth&act=register">Đăng ký ngay</a></p>
</div>

<?php get_footer(); ?>
