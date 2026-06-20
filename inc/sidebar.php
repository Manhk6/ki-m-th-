<div id="sidebar" class="fl-left">
    <div id="main-menu-wp">
        <ul class="list-item">
            <li class="active">
                <a href="?" title="Trang chủ">Trang chủ</a>
            </li>
            <li>
                <a href="?mod=page&act=detail&id=1" title="Giới thiệu">Giới thiệu</a>
            </li>
            <?php
            // Lấy danh mục từ database
            global $pdo;
            $stmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
            $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($cats as $cat):
            ?>
            <li>
                <a href="?mod=product&act=main&id_cat=<?php echo $cat['id']; ?>" title="">
                    <?php echo ucfirst(htmlspecialchars($cat['name_cat'])); ?>
                </a>
            </li>
            <?php endforeach; ?>
            <li>
                <a href="?mod=page&act=detail&id=2" title="Liên hệ">Liên hệ</a>
            </li>
        </ul>
    </div>
</div>
