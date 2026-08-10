<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Shop the collection';
$categoryId = isset($_GET['category']) ? (int) $_GET['category'] : 0;
$categories = get_categories();

$sql = 'SELECT p.*, c.name AS category_name
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        WHERE p.is_active = 1';
$params = [];

if ($categoryId > 0) {
    $sql .= ' AND p.category_id = ?';
    $params[] = $categoryId;
}

$sql .= ' ORDER BY p.created_at DESC';

$stmt = db()->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

require __DIR__ . '/includes/store_header.php';
?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">The collection</span>
        <h1>Find your everyday favourite.</h1>
        <p>Explore the current Balmed 18 collection, managed from the admin dashboard.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if ($categories): ?>
            <div class="category-pills">
                <a class="<?= $categoryId === 0 ? 'active' : '' ?>" href="<?= url('products.php') ?>">All</a>

                <?php foreach ($categories as $category): ?>
                    <a
                        class="<?= $categoryId === (int) $category['id'] ? 'active' : '' ?>"
                        href="<?= url('products.php?category=' . (int) $category['id']) ?>"
                    >
                        <?= e($category['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($products): ?>
            <div class="product-grid product-grid-large">
                <?php foreach ($products as $product): ?>
                    <article class="product-card">
                        <a class="product-image" href="<?= url('product.php?id=' . (int) $product['id']) ?>">
                            <img src="<?= e(product_image($product['image'])) ?>" alt="<?= e($product['name']) ?>">
                            <?php if ((int) $product['is_featured'] === 1): ?>
                                <span class="badge">Featured</span>
                            <?php endif; ?>
                        </a>

                        <div class="product-card-body">
                            <span class="product-category"><?= e($product['category_name'] ?? 'Balmed 18') ?></span>
                            <h3><?= e($product['name']) ?></h3>
                            <p><?= e($product['short_description'] ?? $product['description']) ?></p>

                            <div class="product-footer">
                                <div class="price-wrap">
                                    <?php if ($product['sale_price'] !== null): ?>
                                        <span class="old-price"><?= money((float) $product['price']) ?></span>
                                    <?php endif; ?>
                                    <strong><?= money(product_price($product)) ?></strong>
                                </div>

                                <?php if ((int) $product['stock_quantity'] > 0): ?>
                                    <form method="post" action="<?= url('cart.php') ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button class="small-add" type="submit">Add to bag</button>
                                    </form>
                                <?php else: ?>
                                    <span class="out-of-stock">Out of stock</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h3>No products found.</h3>
                <p>Try another category or check back after new products are added.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/store_footer.php'; ?>
