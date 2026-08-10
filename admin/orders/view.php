<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = db()->prepare('SELECT * FROM orders WHERE id = ?');
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) {
    http_response_code(404);
    exit('Order not found.');
}

$itemStmt = db()->prepare(
    'SELECT * FROM order_items WHERE order_id = ? ORDER BY id ASC'
);
$itemStmt->execute([$id]);
$items = $itemStmt->fetchAll();

$pageTitle = 'Order #' . $id;
$currentAdminPage = 'orders';

require __DIR__ . '/../../includes/admin_header.php';
?>

<div class="page-actions">
    <a class="admin-link" href="<?= url('admin/orders/index.php') ?>">← Back to orders</a>
</div>

<div class="order-detail-grid">
    <section class="panel">
        <div class="panel-heading">
            <div>
                <span class="eyebrow">Order details</span>
                <h2>#<?= (int) $order['id'] ?></h2>
            </div>

            <span class="status status-<?= strtolower(str_replace(' ', '-', $order['status'])) ?>">
                <?= e($order['status']) ?>
            </span>
        </div>

        <div class="order-items">
            <?php foreach ($items as $item): ?>
                <div class="order-item">
                    <div>
                        <strong><?= e($item['product_name']) ?></strong>
                        <span>
                            <?= (int) $item['quantity'] ?>
                            × <?= money((float) $item['price']) ?>
                        </span>
                    </div>

                    <strong><?= money((float) $item['subtotal']) ?></strong>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="order-total-block">
            <div>
                <span>Subtotal</span>
                <strong><?= money((float) $order['subtotal']) ?></strong>
            </div>

            <div>
                <span>Delivery</span>
                <strong><?= money((float) $order['delivery_fee']) ?></strong>
            </div>

            <div class="grand">
                <span>Total</span>
                <strong><?= money((float) $order['total']) ?></strong>
            </div>
        </div>
    </section>

    <aside class="panel">
        <span class="eyebrow">Customer</span>
        <h2><?= e($order['customer_name']) ?></h2>

        <div class="customer-info">
            <span><?= e($order['phone']) ?></span>
            <span><?= e($order['email']) ?></span>
            <span><?= e($order['delivery_location']) ?></span>
        </div>

        <?php if ($order['delivery_notes']): ?>
            <div class="notes-box">
                <strong>Delivery instructions</strong>
                <p><?= nl2br(e($order['delivery_notes'])) ?></p>
            </div>
        <?php endif; ?>

        <hr>

        <form method="post" action="<?= url('admin/orders/update-status.php') ?>" class="admin-form">
            <?= csrf_field() ?>
            <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">

            <div class="form-field">
                <label for="status">Update status</label>
                <select id="status" name="status">
                    <?php foreach (order_statuses() as $status): ?>
                        <option
                            value="<?= e($status) ?>"
                            <?= $order['status'] === $status ? 'selected' : '' ?>
                        >
                            <?= e($status) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="admin-btn" type="submit">Update order</button>
        </form>
    </aside>
</div>

<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
