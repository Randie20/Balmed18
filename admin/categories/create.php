<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

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
            $stmt = db()->prepare(
                'INSERT INTO categories (name, slug, description, is_active)
                 VALUES (?, ?, ?, ?)'
            );

            $stmt->execute([
                $name,
                slugify($name),
                $description ?: null,
                $active,
            ]);

            flash('success', 'Category created.');
            redirect('admin/categories/index.php');
        } catch (Throwable $exception) {
            $errors[] = $exception->getMessage();
        }
    }
}

$pageTitle = 'Add category';
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
                <input id="name" name="name" required value="<?= old('name') ?>">
            </div>

            <div class="form-field full">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="6"><?= old('description') ?></textarea>
            </div>
        </div>
    </div>

    <div class="panel">
        <label class="check-single">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                <?= !is_post() || isset($_POST['is_active']) ? 'checked' : '' ?>
            >
            Active category
        </label>
    </div>

    <div class="form-actions">
        <a class="admin-link" href="<?= url('admin/categories/index.php') ?>">Cancel</a>
        <button class="admin-btn" type="submit">Create category</button>
    </div>
</form>

<link rel="stylesheet" href="<?= e(url('assets/css/admin.css')) ?>">

<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
