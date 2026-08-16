<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = get_product($id, false);

if (!$product) {
    http_response_code(404);
    exit('Product not found.');
}

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
            $image = upload_product_image($_FILES['image'] ?? [], $product['image']);
            $slug = unique_product_slug($name, $id);

            $stmt = db()->prepare(
                'UPDATE products
                 SET name = ?, slug = ?, description = ?, short_description = ?,
                     price = ?, sale_price = ?, image = ?, stock_quantity = ?,
                     category_id = ?, is_featured = ?, is_active = ?
                 WHERE id = ?'
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
                $id,
            ]);

            flash('success', 'Product updated successfully.');
            redirect('admin/products/index.php');
        } catch (Throwable $exception) {
            $errors[] = $exception->getMessage();
        }
    }
}

$pageTitle = 'Edit product';
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
                <h2><?= e($product['name']) ?></h2>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-field full">
                <label for="name">Product name</label>
                <input id="name" name="name" required value="<?= old('name', $product['name']) ?>">
            </div>

            <div class="form-field">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id">
                    <option value="0">Uncategorised</option>

                    <?php foreach ($categories as $category): ?>
                        <option
                            value="<?= (int) $category['id'] ?>"
                            <?= (int) ($_POST['category_id'] ?? $product['category_id'] ?? 0) === (int) $category['id'] ? 'selected' : '' ?>
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
                    value="<?= old('short_description', $product['short_description'] ?? '') ?>"
                >
            </div>

            <div class="form-field full">
                <label for="description">Full description</label>
                <textarea id="description" name="description" rows="8" required><?= old('description', $product['description']) ?></textarea>
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
                <input
                    id="price"
                    type="number"
                    name="price"
                    min="0"
                    step="0.01"
                    required
                    value="<?= old('price', (string) $product['price']) ?>"
                >
            </div>

            <div class="form-field">
                <label for="sale_price">Sale price (optional)</label>
                <input
                    id="sale_price"
                    type="number"
                    name="sale_price"
                    min="0"
                    step="0.01"
                    value="<?= old('sale_price', $product['sale_price'] !== null ? (string) $product['sale_price'] : '') ?>"
                >
            </div>

            <div class="form-field">
                <label for="stock_quantity">Stock quantity</label>
                <input
                    id="stock_quantity"
                    type="number"
                    name="stock_quantity"
                    min="0"
                    required
                    value="<?= old('stock_quantity', (string) $product['stock_quantity']) ?>"
                >
            </div>
        </div>
    </div>

    <div class="panel image-edit-panel">
        <div class="current-product-image">
            <img src="<?= e(product_image($product['image'])) ?>" alt="">
        </div>

        <div class="form-field">
            <label for="image">Replace product image</label>
            <input id="image" type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
            <small>Leave blank to keep the current image.</small>
        </div>
    </div>

    <div class="panel">
        <div class="checkbox-row">
            <label>
                <input
                    type="checkbox"
                    name="is_featured"
                    value="1"
                    <?= (int) ($_POST['is_featured'] ?? $product['is_featured']) === 1 ? 'checked' : '' ?>
                >
                Featured product
            </label>

            <label>
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    <?= (int) ($_POST['is_active'] ?? $product['is_active']) === 1 ? 'checked' : '' ?>
                >
                Active on storefront
            </label>
        </div>
    </div>

    <div class="form-actions">
        <a class="admin-link" href="<?= url('admin/products/index.php') ?>">Cancel</a>
        <button class="admin-btn" type="submit">Save changes</button>
    </div>
</form>

<link rel="stylesheet" href="<?= e(url('assets/css/admin.css')) ?>">

<?php require __DIR__ . '/../../includes/admin_footer.php'; ?>
