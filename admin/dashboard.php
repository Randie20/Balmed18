<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

$totalProducts = (int) db()->query('SELECT COUNT(*) FROM products')->fetchColumn();
$activeProducts = (int) db()->query('SELECT COUNT(*) FROM products WHERE is_active = 1')->fetchColumn();
$outOfStock = (int) db()->query('SELECT COUNT(*) FROM products WHERE stock_quantity = 0 AND is_active = 1')->fetchColumn();

$totalOrders = (int) db()->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$pendingOrders = (int) db()->query("SELECT COUNT(*) FROM orders WHERE status = 'Pending'")->fetchColumn();
$completedOrders = (int) db()->query("SELECT COUNT(*) FROM orders WHERE status = 'Completed'")->fetchColumn();
$totalSales = (float) db()->query(
    "SELECT COALESCE(SUM(total), 0) FROM orders WHERE status != 'Cancelled'"
)->fetchColumn();

$recentOrders = db()->query(
    'SELECT * FROM orders ORDER BY created_at DESC LIMIT 8'
)->fetchAll();

$pageTitle = 'Dashboard';
$currentAdminPage = 'dashboard';


?>

<div>
    <aside class="admin-sidebar" id="adminSidebar">

    <div class="admin-sidebar-brand">
        <!-- your existing Balmed 18 branding -->
    </div>

    <button
        type="button"
        class="admin-sidebar-toggle"
        id="adminSidebarToggle"
        aria-label="Collapse sidebar"
        aria-expanded="true"
    >
        <span></span>
        <span></span>
        <span></span>
    </button>


</aside>
</div>
<div class="metric-grid">
    <div class="metric-card">
        <span>Total Products</span>
        <strong><?= $totalProducts ?></strong>
    </div>

    <div class="metric-card">
        <span>Active Products</span>
        <strong><?= $activeProducts ?></strong>
    </div>

    <div class="metric-card warning">
        <span>Out of Stock</span>
        <strong><?= $outOfStock ?></strong>
    </div>

    <div class="metric-card">
        <span>Total Orders</span>
        <strong><?= $totalOrders ?></strong>
    </div>

    <div class="metric-card">
        <span>Pending Orders</span>
        <strong><?= $pendingOrders ?></strong>
    </div>

    <div class="metric-card">
        <span>Completed Orders</span>
        <strong><?= $completedOrders ?></strong>
    </div>

    <div class="metric-card sales">
        <span>Total Sales</span>
        <strong><?= money($totalSales) ?></strong>
    </div>
</div>

<div class="content-grid">
    <section class="panel">
        <div class="panel-heading">
            <div>
                <span class="eyebrow">Recent activity</span>
                <h2>Recent orders</h2>
            </div>

            <a class="admin-link" href="<?= url('admin/orders/index.php') ?>">View all</a>
        </div>

        <?php if ($recentOrders): ?>
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>
                                <a href="<?= url('admin/orders/view.php?id=' . (int) $order['id']) ?>">
                                    #<?= (int) $order['id'] ?>
                                </a>
                            </td>
                            <td><?= e($order['customer_name']) ?></td>
                            <td><?= money((float) $order['total']) ?></td>
                            <td>
                                <span class="status status-<?= strtolower(str_replace(' ', '-', $order['status'])) ?>">
                                    <?= e($order['status']) ?>
                                </span>
                            </td>
                            <td><?= e(date('d M Y, H:i', strtotime($order['created_at']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-admin">No orders have been placed yet.</div>
        <?php endif; ?>
    </section>

    <aside class="panel quick-panel">
        <span class="eyebrow">Quick actions</span>
        <h2>Manage the store.</h2>
        <a class="quick-action" href="<?= url('admin/products/create.php') ?>">+ Add product</a>
        <a class="quick-action" href="<?= url('admin/categories/create.php') ?>">+ Add category</a>
        <a class="quick-action" href="<?= url('admin/orders/index.php') ?>">View orders</a>
    </aside>
</div>

<link rel="stylesheet" href="<?= e(url('assets/css/admin.css')) ?>">

