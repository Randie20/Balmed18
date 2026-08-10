<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = get_product($id, false);

if (!$product) {
    flash('error', 'Product not found.');
    redirect('admin/products/index.php');
}

if (is_post()) {
    verify_csrf();

    $stmt = db()->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$id]);

    delete_product_image($product['image']);

    flash('success', 'Product deleted.');
    redirect('admin/products/index.php');
}

$pageTitle = 'Delete product';
$currentAdminPage = 'products';

require __DIR__ . '/../../includes/admin_header.php';
?>

<div class="panel danger-panel">
    <span class="eyebrow">Permanent action</span>
    <h2>Delete <?= e($product['name']) ?>?</h2>
    <p>
        This removes the product from the database. Existing order items keep
        their recorded product name and price.
    </p>

    <form method="post" class="form-actions">
        <?= csrf_field() ?>
        <a class="admin-link" href="<?= url('admin/products/index.php') ?>">Cancel</a>
        <button class="admin-btn danger-btn" type="submit">Yes, delete product</button>
    </form>
</div>

<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
