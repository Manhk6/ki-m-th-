<?php get_header(); get_sidebar(); ?>

<div id="content" class="fl-right">
    <div id="home-wp">

        <?php
        $categories = get_all_categories();
        foreach ($categories as $cat):
            $list = get_list_product_by_catId($cat['id']);
            if (empty($list)) continue;
        ?>
        <div class="list-product-home" style="margin-bottom:30px;">
            <h3 class="title-section" style="text-transform:uppercase; font-size:18px; margin-bottom:15px; border-bottom:2px solid #e22929; padding-bottom:8px;">
                <?php echo ucfirst($cat['name_cat']); ?>
            </h3>
            <ul class="list-item clearfix">
                <?php foreach ($list as $product):
                    $detail = get_detail_product_byID($product['id']);
                ?>
                <li style="float:left; width:30%; margin-right:3%; margin-bottom:20px;">
                    <a href="<?php echo $detail['url_detailCart']; ?>">
                        <div class="thumb">
                            <img src="<?php echo $detail['avt']; ?>" alt="<?php echo $detail['name_product']; ?>" style="width:100%;">
                        </div>
                        <p class="name" style="margin:8px 0 4px; font-size:14px;"><?php echo $detail['name_product']; ?></p>
                    </a>
                    <p class="price" style="color:#e22929; font-weight:bold; margin-bottom:8px;">
                        <?php echo number_format($detail['price'], 0, ',', '.'); ?> đ
                    </p>
                    <a href="<?php echo $detail['url_addCart']; ?>"
                       style="display:inline-block; padding:6px 12px; background:#e22929; color:#fff; border-radius:3px; font-size:13px;">
                        Thêm vào giỏ
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endforeach; ?>

    </div>
</div>

<?php get_footer(); ?>
