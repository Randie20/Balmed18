<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$pageTitle = 'Order received';

require __DIR__ . '/includes/store_header.php';
?>

<section class="section success-section">
    <div class="container empty-state">
        <div class="success-icon">✓</div>
        <span class="eyebrow">Thank you</span>
        <h1>Your order is in.</h1>
        <p>
            We received order <strong>#<?= $orderId ?></strong>.
            Our team can now confirm the order and arrange delivery.
        </p>

        <div class="hero-actions centered-actions">
            <a class="btn btn-primary" href="<?= url('products.php') ?>">Continue shopping</a>
            <a class="text-link" href="<?= url('index.php') ?>">Back home</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/store_footer.php'; ?>
