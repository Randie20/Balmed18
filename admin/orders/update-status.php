<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

require_admin();
require_post();
verify_csrf();

$orderId = (int) ($_POST['order_id'] ?? 0);
$status = $_POST['status'] ?? '';

if (!in_array($status, order_statuses(), true)) {
    flash('error', 'Invalid order status.');
    redirect('admin/orders/index.php');
}

$stmt = db()->prepare(
    'UPDATE orders SET status = ? WHERE id = ?'
);

$stmt->execute([$status, $orderId]);

flash('success', 'Order status updated.');
redirect('admin/orders/view.php?id=' . $orderId);
