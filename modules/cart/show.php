<?php
get_header();
?>
<?php 
?>
<?php
if (isset($_SESSION['cart']['buy'])) {
    $show_cart = get_list_buy_cart();
    $pay = $_SESSION['cart']['infor'];
}
?>
<div id="main-content-wp" class="cart-page">
    <div class="section" id="breadcrumb-wp">
        <div class="wp-inner">
            <div class="section-detail">
                <h3 class="title">Giỏ hàng</h3>
            </div>
        </div>
    </div>
    <div id="wrapper" class="wp-inner clearfix">
        <div class="section" id="info-cart-wp">
            <div class="section-detail table-responsive">
                <form action="?mod=cart&act=update" method="post">
                <table class="table">
                    <thead>
                        <tr>
                            <td>Mã sản phẩm</td>
                            <td>Ảnh sản phẩm</td>
                            <td>Tên sản phẩm</td>
                            <td>Giá sản phẩm</td>
                            <td>Số lượng</td>
                            <td colspan="2">Thành tiền</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($_SESSION['cart']['buy'])) {
                            foreach ($show_cart as $value) { ?>
                                <tr>
                                    <td>
                                        <?php echo $value['code'] ?>
                                    </td>
                                    <td>
                                        <a href="" title="" class="thumb">
                                            <img src="<?php echo $value['avt'] ?>" alt="">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo $value['url_detailCart']; ?>" class="name-product"><?php echo $value['name_product'] ?></a>
                                    </td>
                                    <td>
                                        <?php echo $value['price'] ?>
                                    </td>
                                    <td>
                                        <input type="number" name="sl[<?php echo $value['id']?>]" min="1" max="10" value="<?php echo $value['sl'] ?>"
                                            class="num-order">
                                    </td>
                                    <td>
                                        <?php echo $value['sub_total'] ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo $value['url_delete'] ?>" title="" class="del-product"><i
                                                class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            <?php }
                        } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7">
                                <div class="clearfix">
                                    <?php
                                    if (empty($_SESSION['cart']['buy'])) {
                                        echo "<p>Không có sản phẩm nào trong giỏ!</p>";
                                    } ?>
                                    <p id="total-price" class="fl-right">Tổng giá: <span>
                                            <?php if (isset($_SESSION['cart']['infor'])) {
                                                echo $pay['total'];

                                            } ?>
                                        </span></p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7">
                                <div class="clearfix">
                                    <div class="fl-right">
                                        <input type="submit" value="Cập nhật giỏ hàng" name="update_cart" id="update-cart">
                                        <a href="?page=checkout" title="" id="checkout-cart">Thanh toán</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </form>
            </div>
        </div>
        <div class="section" id="action-cart-wp">
            <div class="section-detail">
                <p class="title">Click vào <span>“Cập nhật giỏ hàng”</span> để cập nhật số lượng. Nhập vào số lượng
                    <span>0</span> để xóa sản phẩm khỏi giỏ hàng. Nhấn vào thanh toán để hoàn tất mua hàng.
                </p>
                <a href="?page=home" title="" id="buy-more">Mua tiếp</a><br />
                <a href="?mod=cart&act=delete_all" title="" id="delete-cart">Xóa giỏ hàng</a>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
?>