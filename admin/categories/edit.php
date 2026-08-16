<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = db()->prepare('SELECT * FROM categories WHERE id = ?');
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    http_response_code(404);
    exit('Category not found.');
}

$errors = [];

if (is_post()) {
    verify_csrf();

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $active = isset($_POST['is_active']) ? 1 : 0;

    if ($name === '') {
        $errors[] = 'Category name is required.';
    }

    if (!$errors) {
        try {
            $update = db()->prepare(
                'UPDATE categories
                 SET name = ?, slug = ?, description = ?, is_active = ?
                 WHERE id = ?'
            );

            $update->execute([
                $name,
                slugify($name),
                $description ?: null,
                $active,
                $id,
            ]);

            flash('success', 'Category updated.');
            redirect('admin/categories/index.php');
        } catch (Throwable $exception) {
            $errors[] = $exception->getMessage();
        }
    }
}

$pageTitle = 'Edit category';
$currentAdminPage = 'categories';

require __DIR__ . '/../../includes/admin_header.php';
?>

<?php if ($errors): ?>
    <div class="alert error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" class="admin-form">
    <?= csrf_field() ?>

    <div class="panel">
        <div class="form-grid">
            <div class="form-field full">
                <label for="name">Category name</label>
                <input id="name" name="name" required value="<?= old('name', $category['name']) ?>">
            </div>

            <div class="form-field full">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="6"><?= old('description', $category['description'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <div class="panel">
        <label class="check-single">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                <?= (int) ($_POST['is_active'] ?? $category['is_active']) === 1 ? 'checked' : '' ?>
            >
            Active category
        </label>
    </div>

    <div class="form-actions">
        <a class="admin-link" href="<?= url('admin/categories/index.php') ?>">Cancel</a>
        <button class="admin-btn" type="submit">Save changes</button>
    </div>
</form>

<?php require __DIR__ . '/../../assets/css/admin.css'; ?>