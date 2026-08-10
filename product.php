<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = get_product($id);

if (!$product) {
    http_response_code(404);
    $pageTitle = 'Product not found';

    require __DIR__ . '/includes/store_header.php';
    ?>
    <section class="section">
        <div class="container empty-state">
            <h1>Product not found.</h1>
            <p>This product may have been removed or deactivated.</p>
            <a class="btn btn-primary" href="<?= url('products.php') ?>">Back to shop</a>
        </div>
    </section>
    <?php
    require __DIR__ . '/includes/store_footer.php';
    exit;
}

$pageTitle = $product['name'];
$categoryId = (int) ($product['category_id'] ?? 0);

$relatedStmt = db()->prepare(
    'SELECT p.*, c.name AS category_name
     FROM products p
     LEFT JOIN categories c ON c.id = p.category_id
     WHERE p.is_active = 1
       AND p.id != ?
       AND (? = 0 OR p.category_id = ?)
     ORDER BY p.created_at DESC
     LIMIT 3'
);

$relatedStmt->execute([$id, $categoryId, $categoryId]);
$related = $relatedStmt->fetchAll();

require __DIR__ . '/includes/store_header.php';
?>

<section class="section product-detail-section">
    <div class="container product-detail-grid">
        <div class="product-detail-image">
            <img src="<?= e(product_image($product['image'])) ?>" alt="<?= e($product['name']) ?>">
        </div>

        <div class="product-detail-copy">
            <span class="eyebrow"><?= e($product['category_name'] ?? 'Balmed 18') ?></span>
            <h1><?= e($product['name']) ?></h1>

            <div class="detail-price">
                <?php if ($product['sale_price'] !== null): ?>
                    <span class="old-price"><?= money((float) $product['price']) ?></span>
                <?php endif; ?>
                <strong><?= money(product_price($product)) ?></strong>
            </div>

            <?php if ($product['short_description']): ?>
                <p class="lead"><?= e($product['short_description']) ?></p>
            <?php endif; ?>

            <div class="detail-description"><?= nl2br(e($product['description'])) ?></div>

            <?php if ((int) $product['stock_quantity'] > 0): ?>
                <form class="add-detail-form" method="post" action="<?= url('cart.php') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">

                    <label for="quantity">Quantity</label>

                    <div class="quantity-row">
                        <input
                            id="quantity"
                            type="number"
                            name="quantity"
                            min="1"
                            max="<?= (int) $product['stock_quantity'] ?>"
                            value="1"
                        >
                        <button class="btn btn-primary" type="submit">Add to bag</button>
                    </div>

                    <small><?= (int) $product['stock_quantity'] ?> available</small>
                </form>
            <?php else: ?>
                <div class="stock-warning">Currently out of stock.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if ($related): ?>
<section class="section">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">You may also like</span>
                <h2>More from the collection.</h2>
            </div>
        </div>

        <div class="product-grid">
            <?php foreach ($related as $item): ?>
                <article class="product-card">
                    <a class="product-image" href="<?= url('product.php?id=' . (int) $item['id']) ?>">
                        <img src="<?= e(product_image($item['image'])) ?>" alt="<?= e($item['name']) ?>">
                    </a>

                    <div class="product-card-body">
                        <span class="product-category"><?= e($item['category_name'] ?? 'Balmed 18') ?></span>
                        <h3><?= e($item['name']) ?></h3>

                        <div class="product-footer">
                            <strong><?= money(product_price($item)) ?></strong>
                            <a class="small-add" href="<?= url('product.php?id=' . (int) $item['id']) ?>">View</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/includes/store_footer.php'; ?>
