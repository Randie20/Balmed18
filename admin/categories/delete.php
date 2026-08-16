<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = db()->prepare('SELECT * FROM categories WHERE id = ?');
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    flash('error', 'Category not found.');
    redirect('admin/categories/index.php');
}

if (is_post()) {
    verify_csrf();

    $delete = db()->prepare('DELETE FROM categories WHERE id = ?');
    $delete->execute([$id]);

    flash('success', 'Category deleted.');
    redirect('admin/categories/index.php');
}

$pageTitle = 'Delete category';
$currentAdminPage = 'categories';

?>

<div class="panel danger-panel">
    <span class="eyebrow">Permanent action</span>
    <h2>Delete <?= e($category['name']) ?>?</h2>
    <p>
        Products assigned to this category will remain in the store but
        become uncategorised.
    </p>

    <form method="post" class="form-actions">
        <?= csrf_field() ?>
        <a class="admin-link" href="<?= url('admin/categories/index.php') ?>">Cancel</a>
        <button class="admin-btn danger-btn" type="submit">Yes, delete category</button>
    </form>
</div>

<link rel="stylesheet" href="<?= e(url('assets/css/admin.css')) ?>">