<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

$slug = trim(
    $_GET['slug'] ?? ''
);

if ($slug === '') {
    redirect(BASE_URL . '/pages/shop.php');
}

$product = getProductBySlug($slug);

if ($product === null) {
    http_response_code(404);

    $pageTitle = 'Product not found';

    require_once __DIR__ . '/../includes/header.php';
    ?>

    <section class="empty-state">

        <div class="container">

            <p class="eyebrow">
                Sorry
            </p>

            <h1>
                Product not found.
            </h1>

            <a
                href="<?= BASE_URL ?>/pages/shop.php"
                class="button button-dark"
            >
                Back to shop
            </a>

        </div>

    </section>

    <?php
    require_once __DIR__ . '/../includes/footer.php';

    exit;
}

$pageTitle = $product['name'];

require_once __DIR__ . '/../includes/header.php';
?>

<section class="product-detail">

    <div class="container product-detail-grid">

        <div class="product-detail-image">

            <img
                src="<?= BASE_URL ?>/assets/images/<?= e($product['image']) ?>"
                alt="<?= e($product['name']) ?>"
            >

        </div>

        <div class="product-detail-content">

            <p class="eyebrow">
                <?= e($product['category']) ?>
            </p>

            <h1>
                <?= e($product['name']) ?>
            </h1>

            <p class="detail-price">
                <?= formatPrice($product['price']) ?>
            </p>

            <div class="detail-description">

                <p>
                    <?= nl2br(e($product['description'])) ?>
                </p>

            </div>

            <form
                action="<?= BASE_URL ?>/pages/cart.php"
                method="POST"
                class="add-to-cart-form"
            >

                <input
                    type="hidden"
                    name="action"
                    value="add"
                >

                <input
                    type="hidden"
                    name="product_id"
                    value="<?= (int) $product['id'] ?>"
                >

                <div class="quantity-control">

                    <label for="quantity">
                        Quantity
                    </label>

                    <input
                        id="quantity"
                        type="number"
                        name="quantity"
                        min="1"
                        max="<?= (int) $product['stock'] ?>"
                        value="1"
                    >

                </div>

                <button
                    type="submit"
                    class="button button-primary button-full"
                    <?= $product['stock'] <= 0 ? 'disabled' : '' ?>
                >
                    <?= $product['stock'] > 0
                        ? 'Add to bag'
                        : 'Out of stock'
                    ?>
                </button>

            </form>

            <div class="product-details-list">

                <div>
                    <span>Handmade</span>
                    <strong>In small batches</strong>
                </div>

                <div>
                    <span>Location</span>
                    <strong>Nairobi, Kenya</strong>
                </div>

                <div>
                    <span>Stock</span>
                    <strong>
                        <?= (int) $product['stock'] ?>
                        available
                    </strong>
                </div>

            </div>

        </div>

    </div>

</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>