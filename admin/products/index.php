<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

$products = db()->query(
    'SELECT p.*, c.name AS category_name
     FROM products p
     LEFT JOIN categories c ON c.id = p.category_id
     ORDER BY p.created_at DESC'
)->fetchAll();

$pageTitle = 'Products';
$currentAdminPage = 'products';


?>

<div class="page-actions">
    <p>Manage everything that appears on the public storefront.</p>
    <a class="admin-btn" href="<?= url('admin/products/create.php') ?>">+ Add product</a>
</div>

<div class="panel">
    <?php if ($products): ?>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <div class="table-product">
                                <img src="<?= e(product_image($product['image'])) ?>" alt="">
                                <div>
                                    <strong><?= e($product['name']) ?></strong>
                                    <small><?= e($product['short_description'] ?? '') ?></small>
                                </div>
                            </div>
                        </td>

                        <td><?= e($product['category_name'] ?? 'Uncategorised') ?></td>

                        <td>
                            <?php if ($product['sale_price'] !== null): ?>
                                <span class="old-price"><?= money((float) $product['price']) ?></span>
                            <?php endif; ?>
                            <?= money(product_price($product)) ?>
                        </td>

                        <td><?= (int) $product['stock_quantity'] ?></td>
                        <td><?= (int) $product['is_featured'] === 1 ? 'Yes' : 'No' ?></td>

                        <td>
                            <span class="status <?= (int) $product['is_active'] === 1 ? 'status-active' : 'status-inactive' ?>">
                                <?= (int) $product['is_active'] === 1 ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>

                        <td>
                            <div class="table-actions">
                                <a href="<?= url('admin/products/edit.php?id=' . (int) $product['id']) ?>">Edit</a>

                                <form method="post" action="<?= url('admin/products/toggle-status.php') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                                    <button type="submit">
                                        <?= (int) $product['is_active'] === 1 ? 'Deactivate' : 'Activate' ?>
                                    </button>
                                </form>

                                <a class="danger-link" href="<?= url('admin/products/delete.php?id=' . (int) $product['id']) ?>">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-admin">
            <h2>No products yet.</h2>
            <p>Add your first product to make it available to customers.</p>
            <a class="admin-btn" href="<?= url('admin/products/create.php') ?>">Add product</a>
        </div>
    <?php endif; ?>
</div>

<link rel="stylesheet" href="<?= e(url('assets/css/admin.css')) ?>">
<script src="<?= url('assets/js/admin.js') ?>"></script>

