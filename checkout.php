<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

$items = get_cart_items();

if (!$items) {
    redirect('cart.php');
}

$subtotal = cart_subtotal();
$deliveryFee = DELIVERY_FEE;
$total = $subtotal + $deliveryFee;
$errors = [];

if (is_post()) {
    verify_csrf();

    $customerName = trim($_POST['customer_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $location = trim($_POST['delivery_location'] ?? '');
    $notes = trim($_POST['delivery_notes'] ?? '');

    if ($customerName === '') {
        $errors[] = 'Please enter your name.';
    }

    if ($phone === '') {
        $errors[] = 'Please enter your phone number.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($location === '') {
        $errors[] = 'Please enter your delivery location.';
    }

    if (!$errors) {
        $pdo = db();

        try {
            $pdo->beginTransaction();

            $lockedItems = [];

            foreach (cart() as $productId => $quantity) {
                $stmt = $pdo->prepare(
                    'SELECT * FROM products
                     WHERE id = ? AND is_active = 1
                     FOR UPDATE'
                );
                $stmt->execute([(int) $productId]);
                $product = $stmt->fetch();

                if (!$product || (int) $product['stock_quantity'] < (int) $quantity) {
                    throw new RuntimeException(
                        'One or more products no longer have enough stock. Please review your bag.'
                    );
                }

                $lockedItems[] = [
                    'product' => $product,
                    'quantity' => (int) $quantity,
                    'price' => product_price($product),
                ];
            }

            $lockedSubtotal = 0.0;

            foreach ($lockedItems as $item) {
                $lockedSubtotal += $item['price'] * $item['quantity'];
            }

            $lockedTotal = $lockedSubtotal + DELIVERY_FEE;

            $orderStmt = $pdo->prepare(
                'INSERT INTO orders
                (customer_name, phone, email, delivery_location, delivery_notes,
                 subtotal, delivery_fee, total)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
            );

            $orderStmt->execute([
                $customerName,
                $phone,
                $email,
                $location,
                $notes ?: null,
                $lockedSubtotal,
                DELIVERY_FEE,
                $lockedTotal,
            ]);

            $orderId = (int) $pdo->lastInsertId();

            $itemStmt = $pdo->prepare(
                'INSERT INTO order_items
                (order_id, product_id, product_name, quantity, price, subtotal)
                VALUES (?, ?, ?, ?, ?, ?)'
            );

            $stockStmt = $pdo->prepare(
                'UPDATE products
                 SET stock_quantity = stock_quantity - ?
                 WHERE id = ?'
            );

            foreach ($lockedItems as $item) {
                $lineTotal = $item['price'] * $item['quantity'];

                $itemStmt->execute([
                    $orderId,
                    (int) $item['product']['id'],
                    $item['product']['name'],
                    $item['quantity'],
                    $item['price'],
                    $lineTotal,
                ]);

                $stockStmt->execute([
                    $item['quantity'],
                    (int) $item['product']['id'],
                ]);
            }

            $pdo->commit();
            clear_cart();

            redirect('order-success.php?id=' . $orderId);
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $errors[] = $exception->getMessage();
        }
    }
}

$pageTitle = 'Checkout';

require __DIR__ . '/includes/store_header.php';
?>

<section class="page-hero compact">
    <div class="container">
        <span class="eyebrow">Checkout</span>
        <h1>Let's get it to you.</h1>
        <p>
            Complete your details below. The order is created as Pending so
            the admin can confirm it.
        </p>
    </div>
</section>

<section class="section">
    <div class="container checkout-layout">
        <div>
            <?php if ($errors): ?>
                <div class="store-alert error">
                    <strong>Please check the following:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= e($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form class="checkout-form" method="post">
                <?= csrf_field() ?>

                <div class="form-section">
                    <span class="eyebrow">1. Your details</span>

                    <div class="form-grid">
                        <div class="form-field">
                            <label for="customer_name">Full name</label>
                            <input
                                id="customer_name"
                                name="customer_name"
                                required
                                value="<?= old('customer_name') ?>"
                            >
                        </div>

                        <div class="form-field">
                            <label for="phone">Phone number</label>
                            <input
                                id="phone"
                                name="phone"
                                required
                                value="<?= old('phone') ?>"
                                placeholder="07XX XXX XXX"
                            >
                        </div>

                        <div class="form-field full">
                            <label for="email">Email address</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                required
                                value="<?= old('email') ?>"
                            >
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <span class="eyebrow">2. Delivery</span>

                    <div class="form-grid">
                        <div class="form-field full">
                            <label for="delivery_location">Delivery location</label>
                            <input
                                id="delivery_location"
                                name="delivery_location"
                                required
                                value="<?= old('delivery_location') ?>"
                                placeholder="Estate, building, area or landmark"
                            >
                        </div>

                        <div class="form-field full">
                            <label for="delivery_notes">
                                Delivery instructions <span>(optional)</span>
                            </label>
                            <textarea
                                id="delivery_notes"
                                name="delivery_notes"
                                rows="4"
                                placeholder="Any useful directions for the rider?"
                            ><?= old('delivery_notes') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section payment-note">
                    <span class="eyebrow">3. Payment</span>
                    <h3>M-Pesa ready</h3>
                    <p>
                        The checkout is structured so an M-Pesa payment workflow
                        can be connected later. For now, the order is created as
                        <strong>Pending</strong>.
                    </p>
                </div>

                <button class="btn btn-primary full-width" type="submit">
                    Place order
                </button>
            </form>
        </div>

        <aside class="checkout-summary cart-summary">
            <span class="eyebrow">Your order</span>

            <?php foreach ($items as $item): ?>
                <div class="checkout-item">
                    <div>
                        <strong><?= e($item['name']) ?></strong>
                        <span>
                            <?= (int) $item['cart_quantity'] ?>
                            × <?= money(product_price($item)) ?>
                        </span>
                    </div>

                    <strong><?= money((float) $item['line_total']) ?></strong>
                </div>
            <?php endforeach; ?>

            <div class="summary-row">
                <span>Subtotal</span>
                <strong><?= money($subtotal) ?></strong>
            </div>

            <div class="summary-row">
                <span>Delivery</span>
                <strong><?= money($deliveryFee) ?></strong>
            </div>

            <div class="summary-total">
                <span>Total</span>
                <strong><?= money($total) ?></strong>
            </div>
        </aside>
    </div>
</section>

<?php require __DIR__ . '/includes/store_footer.php'; ?>
