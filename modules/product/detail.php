<?php
get_header();
?>

<?php 
$id_detail=(int)$_GET['id'];
$product_detail=get_detail_product_byID($id_detail);
?>
<div id="main-content-wp" class="detail-product-page clearfix">
    <div class="wp-inner clearfix">
        <?php get_sidebar(); ?>
        <div id="content" class="fl-right">
            <div class="section" id="info-product-wp">
                <div class="section-detail clearfix">
                    <div class="thumb fl-left">
                        <img src="<?php echo $product_detail['avt'] ?>" alt="">
                    </div>
                    <div class="detail fl-right">
                        <h3 class="title"><?php echo $product_detail['name_product']; ?></h3>
                        <p class="price"><?php echo $product_detail['price']; ?>đ</p>
                        <p class="product-code">Mã sản phẩm: <span><?php echo $product_detail['code']; ?></span></p>
                        <div class="desc-short">
                            <h5>Mô tả sản phẩm:</h5>
                            <p><?php echo $product_detail['product_decs']; ?></p>
                        </div>
                        <div class="num-order-wp">
                            <span>Số lượng:</span>
                            <input type="text" id="num-order" name="num-order" value="1">
                            <a href="<?php echo $product_detail['url_addCart']?>" title="" class="add-to-cart">Thêm giỏ hàng</a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="section" id="desc-wp">
                <div class="section-head">
                    <h3 class="section-title">Chi tiết sản phẩm</h3>
                </div>
                <div class="section-detail">
                <?php echo $product_detail['product_content']; ?>                </div>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
?>