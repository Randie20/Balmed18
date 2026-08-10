<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

require_admin();
require_post();
verify_csrf();

$id = (int) ($_POST['id'] ?? 0);

$stmt = db()->prepare(
    'UPDATE products
     SET is_active = IF(is_active = 1, 0, 1)
     WHERE id = ?'
);

$stmt->execute([$id]);

flash('success', 'Product status updated.');
redirect('admin/products/index.php');
