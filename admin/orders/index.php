<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

$orders = db()->query(
    'SELECT * FROM orders ORDER BY created_at DESC'
)->fetchAll();

$pageTitle = 'Orders';
$currentAdminPage = 'orders';


?>

<div class="page-actions">
    <p>Review customer orders and update fulfilment status.</p>
</div>

<div class="panel">
    <?php if ($orders): ?>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>
                            <a href="<?= url('admin/orders/view.php?id=' . (int) $order['id']) ?>">
                                #<?= (int) $order['id'] ?>
                            </a>
                        </td>
                        <td><?= e($order['customer_name']) ?></td>
                        <td><?= e($order['phone']) ?></td>
                        <td><?= money((float) $order['total']) ?></td>
                        <td>
                            <span class="status status-<?= strtolower(str_replace(' ', '-', $order['status'])) ?>">
                                <?= e($order['status']) ?>
                            </span>
                        </td>
                        <td><?= e(date('d M Y, H:i', strtotime($order['created_at']))) ?></td>
                        <td>
                            <a href="<?= url('admin/orders/view.php?id=' . (int) $order['id']) ?>">
                                View
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-admin">
            <h2>No orders yet.</h2>
            <p>Customer orders will appear here after checkout.</p>
        </div>
    <?php endif; ?>
</div>

<link rel="stylesheet" href="<?= e(url('assets/css/admin.css')) ?>">
<script src="<?= url('assets/js/admin.js') ?>"></script>

