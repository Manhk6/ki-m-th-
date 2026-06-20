<!DOCTYPE html>
<html>
    <head>
        <title>WA Store</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="public/css/bootstrap/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="public/css/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="public/reset.css" rel="stylesheet" type="text/css"/>
        <link href="public/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="public/style.css" rel="stylesheet" type="text/css"/>
        <link href="public/responsive.css" rel="stylesheet" type="text/css"/>
        <script src="public/js/jquery-2.2.4.min.js" type="text/javascript"></script>
        <script src="public/js/bootstrap/bootstrap.min.js" type="text/javascript"></script>
        <script src="public/js/main.js" type="text/javascript"></script>
        <style>
            #user-nav { float: right; padding: 18px 10px; font-size: 14px; }
            #user-nav a { color: #e22929; margin-left: 10px; }
            #user-nav span { color: #555; }
        </style>
    </head>
    <body>
        <div id="site">
            <div id="container">
                <div id="header-wp" class="clearfix">
                    <div class="wp-inner">
                        <a href="?" title="" id="logo" class="fl-left">WA STORE</a>
                        <div id="btn-respon" class="fl-right"><i class="fa fa-bars" aria-hidden="true"></i></div>

                        <!-- Giỏ hàng -->
                        <div id="cart-wp" class="fl-right">
                            <a href="?mod=cart&act=show" title="" id="btn-cart">
                                <span id="icon"><img src="public/images/icon-cart.png" alt=""></span>
                                <span id="num"><?php if (isset($_SESSION['cart']['buy'])) {
                                    echo $_SESSION['cart']['infor']['num_order'];
                                } ?></span>
                            </a>
                        </div>

                        <!-- Đăng nhập / Đăng xuất -->
                        <div id="user-nav" class="fl-right">
                            <?php if (isset($_SESSION['user'])): ?>
                                <span>Xin chào, <strong><?php echo htmlspecialchars($_SESSION['user']['full_name']); ?></strong></span>
                                <a href="?mod=auth&act=logout">Đăng xuất</a>
                            <?php else: ?>
                                <a href="?mod=auth&act=login">Đăng nhập</a>
                                <a href="?mod=auth&act=register">Đăng ký</a>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
