<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Bottled beauty, made with care';

/*
|--------------------------------------------------------------------------
| Product Collection
|--------------------------------------------------------------------------
|
| Products are managed through the admin dashboard.
| The homepage displays active products from the database.
|
*/

$collection = db()->query(
    'SELECT
        p.*,
        c.name AS category_name
     FROM products p
     LEFT JOIN categories c
        ON c.id = p.category_id
     WHERE p.is_active = 1
     ORDER BY p.created_at DESC
     LIMIT 6'
)->fetchAll();

require __DIR__ . '/includes/store_header.php';
?>

<section class="hero">
    <div class="container hero-grid">
        <div class="hero-copy">
            <span class="eyebrow">Lip care is also skin care</span>

            <h1>
                Lip love,<br>
                <em>bottled in a tube.</em>
            </h1>

            <p>
                Small-batch beauty made with care. Thoughtful products,
                warm scents and simple everyday rituals.
            </p>

            <div class="hero-actions">
                <a
                    class="btn btn-primary"
                    href="<?= url('products.php') ?>"
                >
                    Shop the collection
                </a>

                <a class="text-link" href="#story">
                    Our story <span>↗</span>
                </a>
            </div>

            <div class="hero-stats">
                <span>
                    <strong>Small batch</strong>
                    Thoughtfully made
                </span>

                <span>
                    <strong>Kenyan made</strong>
                    Made in Nairobi
                </span>

                <span>
                    <strong>Everyday care</strong>
                    Simple rituals
                </span>
            </div>
        </div>

        <div class="hero-art">
            <img
                src="<?= url('assets/images/trio.jpg') ?>"
                alt="Balmed 18 product illustration"
            >
        </div>
    </div>
</section>

<section class="section collection-section">
    <div class="container">

        <div class="section-heading">
            <div>
                <span class="eyebrow">The collection</span>
                <h2>Pick your favourite.</h2>
            </div>

            <a
                class="text-link"
                href="<?= url('products.php') ?>"
            >
                View all <span>↗</span>
            </a>
        </div>

        <?php if ($collection): ?>

            <div class="product-grid">

                <?php foreach ($collection as $product): ?>

                    <article class="product-card">

                        <a
                            class="product-image"
                            href="<?= url('product.php?id=' . (int) $product['id']) ?>"
                        >
                            <img
                                src="<?= e(product_image($product['image'])) ?>"
                                alt="<?= e($product['name']) ?>"
                            >
                        </a>

                        <div class="product-card-body">

                            <span class="product-category">
                                <?= e($product['category_name'] ?? 'Balmed 18') ?>
                            </span>

                            <h3>
                                <?= e($product['name']) ?>
                            </h3>

                            <p>
                                <?= e(
                                    $product['short_description']
                                    ?? $product['description']
                                ) ?>
                            </p>

                            <div class="product-footer">

                                <div class="price-wrap">

                                    <?php if (
                                        isset($product['sale_price'])
                                        && $product['sale_price'] !== null
                                    ): ?>

                                        <span class="old-price">
                                            <?= money((float) $product['price']) ?>
                                        </span>

                                    <?php endif; ?>

                                    <strong>
                                        <?= money(product_price($product)) ?>
                                    </strong>

                                </div>

                                <?php if (
                                    (int) $product['stock_quantity'] > 0
                                ): ?>

                                    <form
                                        method="post"
                                        action="<?= url('cart.php') ?>"
                                    >
                                        <?= csrf_field() ?>

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

                                        <input
                                            type="hidden"
                                            name="quantity"
                                            value="1"
                                        >

                                        <button
                                            class="small-add"
                                            type="submit"
                                        >
                                            Add to bag
                                        </button>
                                    </form>

                                <?php else: ?>

                                    <span class="out-of-stock">
                                        Out of stock
                                    </span>

                                <?php endif; ?>

                            </div>
                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-state">
                <h3>The collection is being prepared.</h3>

                <p>
                    Products added through the Balmed 18 admin dashboard
                    will appear here automatically.
                </p>
            </div>

        <?php endif; ?>

    </div>
</section>

<section class="story-section" id="story">

    <div class="container story-grid">

        <div class="story-art">

            <div class="story-card">
                <span class="eyebrow">Made with care</span>

                <strong>
                    From Nairobi,<br>
                    with love.
                </strong>
            </div>

        </div>

        <div class="story-copy">

            <span class="eyebrow">Our story</span>

            <h2>
                Simple ingredients.<br>
                Thoughtful rituals.
            </h2>

            <p>
                Balmed 18 is rooted in the small, beautiful moments that make
                everyday self-care feel special. We create considered products
                in small batches, with ingredients chosen for comfort and care.
            </p>

            <p>
                Our approach is intentionally simple: useful products, warm
                scents and a little joy in every application.
            </p>

        </div>

    </div>

</section>

<section class="why-section section" id="why-us">

    <div class="container">

        <div class="section-heading centered">

            <span class="eyebrow">Why Balmed 18</span>

            <h2>
                Care that fits your everyday.
            </h2>

        </div>

        <div class="feature-grid">

            <article>
                <span>01</span>

                <h3>
                    Small batch
                </h3>

                <p>
                    Made thoughtfully so every product gets the attention
                    it deserves.
                </p>
            </article>

            <article>
                <span>02</span>

                <h3>
                    Made in Nairobi
                </h3>

                <p>
                    Rooted locally, inspired by the warmth and creativity
                    around us.
                </p>
            </article>

            <article>
                <span>03</span>

                <h3>
                    Made to be used
                </h3>

                <p>
                    Simple, practical self-care designed for real everyday
                    routines.
                </p>
            </article>

        </div>

    </div>

</section>

<section class="quote-section">

    <div class="container">

        <blockquote>
            “The best little everyday luxury is the one you actually reach for.”

            <cite>
                — Balmed 18
            </cite>
        </blockquote>

    </div>

</section>

<section class="cta-section">

    <div class="container cta-inner">

        <div>

            <span class="eyebrow">
                Find your everyday favourite
            </span>

            <h2>
                Ready for a little<br>
                Balmed 18 love?
            </h2>

        </div>

        <a
            class="btn btn-light"
            href="<?= url('products.php') ?>"
        >
            Shop now
        </a>

    </div>

</section>

<?php require __DIR__ . '/includes/store_footer.php'; ?>