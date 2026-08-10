<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'Shop';

$products = getProducts();

require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-header">

    <div class="container">

        <p class="eyebrow">
            the collection
        </p>

        <h1>
            Everything you need
            <br>
            to feel good.
        </h1>

        <p>
            Thoughtfully made body care,
            handcrafted in Nairobi.
        </p>

    </div>

</section>

<section class="shop-section">

    <div class="container">

        <div class="shop-toolbar">

            <span>
                <?= count($products) ?> products
            </span>

            <span>
                Made in Kenya
            </span>

        </div>

        <div class="product-grid product-grid-large">

            <?php foreach ($products as $product): ?>

                <article class="product-card">

                    <a
                        href="<?= BASE_URL ?>/pages/product.php?slug=<?= urlencode($product['slug']) ?>"
                        class="product-image"
                    >
                        <img
                            src="<?= BASE_URL ?>/assets/images/<?= e($product['image']) ?>"
                            alt="<?= e($product['name']) ?>"
                        >
                    </a>

                    <div class="product-info">

                        <div>

                            <span class="product-category">
                                <?= e($product['category']) ?>
                            </span>

                            <h3>
                                <?= e($product['name']) ?>
                            </h3>

                            <p>
                                <?= e($product['description']) ?>
                            </p>

                        </div>

                        <div class="product-bottom">

                            <strong>
                                <?= formatPrice($product['price']) ?>
                            </strong>

                            <a
                                href="<?= BASE_URL ?>/pages/product.php?slug=<?= urlencode($product['slug']) ?>"
                                class="small-button"
                            >
                                Add to bag
                            </a>

                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    </div>

</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>