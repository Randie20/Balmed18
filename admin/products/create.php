<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

$categories = get_categories(false);
$errors = [];

if (is_post()) {
    verify_csrf();

    $name = trim($_POST['name'] ?? '');
    $shortDescription = trim($_POST['short_description'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $salePriceRaw = trim($_POST['sale_price'] ?? '');
    $salePrice = $salePriceRaw === '' ? null : (float) $salePriceRaw;
    $stock = max(0, (int) ($_POST['stock_quantity'] ?? 0));
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $featured = isset($_POST['is_featured']) ? 1 : 0;
    $active = isset($_POST['is_active']) ? 1 : 0;

    if ($name === '') {
        $errors[] = 'Product name is required.';
    }

    if ($description === '') {
        $errors[] = 'Full description is required.';
    }

    if ($price < 0) {
        $errors[] = 'Price cannot be negative.';
    }

    if ($salePrice !== null && ($salePrice < 0 || $salePrice > $price)) {
        $errors[] = 'Sale price must be between zero and the regular price.';
    }

    if (!$errors) {
        try {
            $image = upload_product_image($_FILES['image'] ?? []);
            $slug = unique_product_slug($name);

            $stmt = db()->prepare(
                'INSERT INTO products
                (name, slug, description, short_description, price, sale_price,
                 image, stock_quantity, category_id, is_featured, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );

            $stmt->execute([
                $name,
                $slug,
                $description,
                $shortDescription ?: null,
                $price,
                $salePrice,
                $image,
                $stock,
                $categoryId > 0 ? $categoryId : null,
                $featured,
                $active,
            ]);

            flash('success', 'Product created successfully.');
            redirect('admin/products/index.php');
        } catch (Throwable $exception) {
            $errors[] = $exception->getMessage();
        }
    }
}

$pageTitle = 'Add product';
$currentAdminPage = 'products';

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


<form class="admin-form product-form" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="panel">
        <div class="panel-heading">
            <div>
                <span class="eyebrow">Product information</span>
                <h2>Tell customers about it.</h2>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-field full">
                <label for="name">Product name</label>
                <input id="name" name="name" required value="<?= old('name') ?>">
            </div>

            <div class="form-field">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id">
                    <option value="0">Uncategorised</option>

                    <?php foreach ($categories as $category): ?>
                        <option
                            value="<?= (int) $category['id'] ?>"
                            <?= (int) ($_POST['category_id'] ?? 0) === (int) $category['id'] ? 'selected' : '' ?>
                        >
                            <?= e($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-field">
                <label for="short_description">Short description</label>
                <input
                    id="short_description"
                    name="short_description"
                    maxlength="255"
                    value="<?= old('short_description') ?>"
                >
            </div>

            <div class="form-field full">
                <label for="description">Full description</label>
                <textarea id="description" name="description" rows="8" required><?= old('description') ?></textarea>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-heading">
            <div>
                <span class="eyebrow">Pricing & stock</span>
                <h2>Commercial details.</h2>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-field">
                <label for="price">Price (KSh)</label>
                <input id="price" type="number" name="price" min="0" step="0.01" required value="<?= old('price') ?>">
            </div>

            <div class="form-field">
                <label for="sale_price">Sale price (optional)</label>
                <input id="sale_price" type="number" name="sale_price" min="0" step="0.01" value="<?= old('sale_price') ?>">
            </div>

            <div class="form-field">
                <label for="stock_quantity">Stock quantity</label>
                <input id="stock_quantity" type="number" name="stock_quantity" min="0" required value="<?= old('stock_quantity', '0') ?>">
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-heading">
            <div>
                <span class="eyebrow">Image</span>
                <h2>Product photography.</h2>
            </div>
        </div>

        <div class="form-field">
            <label for="image">Product image</label>
            <input id="image" type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
            <small>JPG, PNG or WebP. Maximum 5MB.</small>
        </div>
    </div>

    <div class="panel">
        <div class="checkbox-row">
            <label>
                <input type="checkbox" name="is_featured" value="1" <?= isset($_POST['is_featured']) ? 'checked' : '' ?>>
                Featured product
            </label>

            <label>
                <input type="checkbox" name="is_active" value="1" <?= !is_post() || isset($_POST['is_active']) ? 'checked' : '' ?>>
                Active on storefront
            </label>
        </div>
    </div>

    <div class="form-actions">
        <a class="admin-link" href="<?= url('admin/products/index.php') ?>">Cancel</a>
        <button class="admin-btn" type="submit">Create product</button>
    </div>
</form>

<link rel="stylesheet" href="<?= e(url('assets/css/admin.css')) ?>">

<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
