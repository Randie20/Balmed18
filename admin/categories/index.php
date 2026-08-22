<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

$categories = db()->query(
    'SELECT c.*, COUNT(p.id) AS product_count
     FROM categories c
     LEFT JOIN products p ON p.category_id = c.id
     GROUP BY c.id
     ORDER BY c.name ASC'
)->fetchAll();

$pageTitle = 'Categories';
$currentAdminPage = 'categories';

?>

<div class="page-actions">
    <p>Organise the product collection into manageable groups.</p>
    <a class="admin-btn" href="<?= url('admin/categories/create.php') ?>">+ Add category</a>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Products</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><strong><?= e($category['name']) ?></strong></td>
                    <td><?= e($category['slug']) ?></td>
                    <td><?= (int) $category['product_count'] ?></td>
                    <td>
                        <span class="status <?= (int) $category['is_active'] === 1 ? 'status-active' : 'status-inactive' ?>">
                            <?= (int) $category['is_active'] === 1 ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="<?= url('admin/categories/edit.php?id=' . (int) $category['id']) ?>">Edit</a>
                            <a href="<?= url('admin/categories/delete.php?id=' . (int) $category['id']) ?>">Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../../assets/css/admin.css'; ?>
