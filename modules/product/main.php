<?php
get_header();

?>

<?php
$id_cat = (int) $_GET['id_cat'];
$cat_name = get_cat_byId($id_cat);
$list_product_byId=get_list_product_by_catId($id_cat);
?>

<div id="main-content-wp" class="category-product-page">
    <div class="wp-inner clearfix">
        <?php get_sidebar(); ?>
        <div id="content" class="fl-right">
            <div class="section list-cat">
                <div class="section-head">
                    <h3 class="section-title">
                        <?php echo $cat_name['name_cat']; ?>
                    </h3>
                    <p class="section-desc">Có <?php echo count($list_product_byId); ?> sản phẩm trong mục này</p>
                </div>
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <?php
                        // show_array($list_product_byId);
                        foreach ($list_product_byId as $value) {
                            
                                ?>
                                <li>
                                    <a href="<?php echo $value['url'] ?>" title="" class="thumb">
                                        <img src=" <?php echo $value['avt']; ?>" alt="">
                                    </a>
                                    <a href="?page=detail_product" title="" class="title"><?php echo $value['name_product'] ?></a>
                                    <p class="price"><?php echo $value['price']; ?>đ</p>
                                </li>
                                <?php
                            }
                        ?>

                    </ul>
                </div>
            </div>
            <div class="section" id="pagenavi-wp">
                <div class="section-detail">
                    <ul id="list-pagenavi">
                        <li class="active">
                            <a href="" title="">1</a>
                        </li>
                        <li>
                            <a href="" title="">2</a>
                        </li>
                        <li>
                            <a href="" title="">3</a>
                        </li>
                    </ul>
                    <a href="" title="" class="next-page"><i class="fa fa-angle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>