<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

if (is_post()) {
    verify_csrf();

    try {
        $action = $_POST['action'] ?? '';

        if ($action === 'add') {
            add_to_cart(
                (int) ($_POST['product_id'] ?? 0),
                (int) ($_POST['quantity'] ?? 1)
            );
            flash('success', 'Product added to your bag.');
        }

        if ($action === 'update') {
            foreach ($_POST['quantities'] ?? [] as $productId => $quantity) {
                update_cart_item((int) $productId, (int) $quantity);
            }
            flash('success', 'Your bag has been updated.');
        }

        if ($action === 'remove') {
            remove_from_cart((int) ($_POST['product_id'] ?? 0));
            flash('success', 'Product removed from your bag.');
        }

        if ($action === 'clear') {
            clear_cart();
            flash('success', 'Your bag is now empty.');
        }
    } catch (Throwable $exception) {
        flash('error', $exception->getMessage());
    }

    redirect('cart.php');
}

$pageTitle = 'Your bag';
$items = get_cart_items();
$subtotal = cart_subtotal();

require __DIR__ . '/includes/store_header.php';
?>

<section class="page-hero compact">
    <div class="container">
        <span class="eyebrow">Your bag</span>
        <h1>Ready when you are.</h1>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if ($message = get_flash('success')): ?>
            <div class="store-alert success"><?= e($message) ?></div>
        <?php endif; ?>

        <?php if ($message = get_flash('error')): ?>
            <div class="store-alert error"><?= e($message) ?></div>
        <?php endif; ?>

        <?php if ($items): ?>
            <div class="cart-layout">
                <div class="cart-items">
                    <form method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="update">

                        <?php foreach ($items as $item): ?>
                            <article class="cart-item">
                                <img src="<?= e(product_image($item['image'])) ?>" alt="<?= e($item['name']) ?>">

                                <div class="cart-item-info">
                                    <span class="product-category"><?= e($item['category_name'] ?? 'Balmed 18') ?></span>
                                    <h3><?= e($item['name']) ?></h3>
                                    <span><?= money(product_price($item)) ?></span>
                                </div>

                                <div class="cart-quantity">
                                    <label for="qty-<?= (int) $item['id'] ?>">Qty</label>
                                    <input
                                        id="qty-<?= (int) $item['id'] ?>"
                                        type="number"
                                        min="0"
                                        max="<?= (int) $item['stock_quantity'] ?>"
                                        name="quantities[<?= (int) $item['id'] ?>]"
                                        value="<?= (int) $item['cart_quantity'] ?>"
                                    >
                                </div>

                                <strong class="cart-line-total"><?= money((float) $item['line_total']) ?></strong>
                            </article>
                        <?php endforeach; ?>

                        <div class="cart-actions">
                            <button class="btn btn-secondary" type="submit">Update bag</button>
                        </div>
                    </form>

                    <form method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="clear">
                        <button class="text-button danger" type="submit">Empty bag</button>
                    </form>
                </div>

                <aside class="cart-summary">
                    <span class="eyebrow">Order summary</span>
                    <h2>Your total</h2>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <strong><?= money($subtotal) ?></strong>
                    </div>

                    <div class="summary-row">
                        <span>Delivery</span>
                        <strong><?= money(DELIVERY_FEE) ?></strong>
                    </div>

                    <div class="summary-total">
                        <span>Total</span>
                        <strong><?= money($subtotal + DELIVERY_FEE) ?></strong>
                    </div>

                    <a class="btn btn-primary full-width" href="<?= url('checkout.php') ?>">Proceed to checkout</a>
                    <a class="text-link center-link" href="<?= url('products.php') ?>">Continue shopping</a>
                </aside>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h2>Your bag is empty.</h2>
                <p>Pick something lovely from the collection and come back here when you're ready.</p>
                <a class="btn btn-primary" href="<?= url('products.php') ?>">Shop the collection</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/store_footer.php'; ?>
