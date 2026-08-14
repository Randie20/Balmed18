<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

/*
|--------------------------------------------------------------------------
| Application URL
|--------------------------------------------------------------------------
|
| Local XAMPP:
|   http://localhost/balmed18
|
| Render:
|   https://balmed18.onrender.com
|
| BASE_URL can be supplied through the environment.
| If it is not supplied, we automatically determine
| the appropriate base path from the current request.
|
*/

if (!defined('BASE_URL')) {
    $configuredBaseUrl = getenv('BASE_URL');

    if ($configuredBaseUrl !== false && $configuredBaseUrl !== '') {
        define('BASE_URL', rtrim($configuredBaseUrl, '/'));
    } else {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';

        if (str_contains($scriptName, '/balmed18/')) {
            define('BASE_URL', '/balmed18');
        } else {
            define('BASE_URL', '');
        }
    }
}

function e(?string $value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

function url(string $path = ''): string
{
    $baseUrl = rtrim(BASE_URL, '/');
    $path = ltrim($path, '/');

    if ($path === '') {
        return $baseUrl !== '' ? $baseUrl . '/' : '/';
    }

    return ($baseUrl !== '' ? $baseUrl : '') . '/' . $path;
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function old(string $key, string $default = ''): string
{
    return e($_POST[$key] ?? $default);
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][$type] = $message;
}

function get_flash(string $type): ?string
{
    $message = $_SESSION['flash'][$type] ?? null;

    unset($_SESSION['flash'][$type]);

    return $message;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="'
        . e(csrf_token())
        . '">';
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';

    if (
        !$token
        || !hash_equals(
            $_SESSION['csrf_token'] ?? '',
            $token
        )
    ) {
        http_response_code(419);
        exit('Invalid CSRF token.');
    }
}

function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function require_post(): void
{
    if (!is_post()) {
        http_response_code(405);
        exit('Method Not Allowed');
    }
}

function money(float|int $amount): string
{
    return CURRENCY . ' '
        . number_format((float) $amount, 2);
}

function product_price(array $product): float
{
    return $product['sale_price'] !== null
        ? (float) $product['sale_price']
        : (float) $product['price'];
}

function product_image(?string $image): string
{
    if ($image) {
        return url(
            'uploads/products/'
            . rawurlencode(basename($image))
        );
    }

    return url('assets/images/product-placeholder.svg');
}

function slugify(string $value): string
{
    $value = trim($value);

    $value = preg_replace(
        '/[^\pL\pN]+/u',
        '-',
        $value
    ) ?? '';

    $value = iconv(
        'UTF-8',
        'ASCII//TRANSLIT//IGNORE',
        $value
    ) ?: $value;

    $value = preg_replace(
        '/[^a-zA-Z0-9-]/',
        '',
        $value
    ) ?? '';

    return strtolower(trim($value, '-'))
        ?: 'item-' . time();
}

function unique_product_slug(
    string $name,
    ?int $excludeId = null
): string {
    $base = slugify($name);
    $slug = $base;
    $counter = 1;

    while (true) {
        $sql = 'SELECT id
                FROM products
                WHERE slug = ?';

        $params = [$slug];

        if ($excludeId !== null) {
            $sql .= ' AND id != ?';
            $params[] = $excludeId;
        }

        $stmt = db()->prepare($sql);
        $stmt->execute($params);

        if (!$stmt->fetch()) {
            return $slug;
        }

        $slug = $base . '-' . $counter++;
    }
}

function get_product(
    int $id,
    bool $activeOnly = true
): ?array {
    $sql = 'SELECT
                p.*,
                c.name AS category_name
            FROM products p
            LEFT JOIN categories c
                ON c.id = p.category_id
            WHERE p.id = ?';

    if ($activeOnly) {
        $sql .= ' AND p.is_active = 1';
    }

    $stmt = db()->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch() ?: null;
}

function get_categories(
    bool $activeOnly = true
): array {
    $sql = 'SELECT * FROM categories';

    if ($activeOnly) {
        $sql .= ' WHERE is_active = 1';
    }

    $sql .= ' ORDER BY name ASC';

    return db()->query($sql)->fetchAll();
}

function cart(): array
{
    return $_SESSION['cart'] ?? [];
}

function cart_count(): int
{
    return array_sum(
        array_map('intval', cart())
    );
}

function add_to_cart(
    int $productId,
    int $quantity
): void {
    $product = get_product($productId);

    if (
        !$product
        || (int) $product['stock_quantity'] < 1
    ) {
        throw new RuntimeException(
            'This product is currently unavailable.'
        );
    }

    $quantity = max(1, $quantity);

    $current = (int) (
        $_SESSION['cart'][$productId] ?? 0
    );

    $newQuantity = min(
        $current + $quantity,
        (int) $product['stock_quantity']
    );

    $_SESSION['cart'][$productId] = $newQuantity;
}

function update_cart_item(
    int $productId,
    int $quantity
): void {
    $product = get_product($productId);

    if (!$product) {
        unset($_SESSION['cart'][$productId]);
        return;
    }

    $quantity = min(
        max(0, $quantity),
        (int) $product['stock_quantity']
    );

    if ($quantity === 0) {
        unset($_SESSION['cart'][$productId]);
        return;
    }

    $_SESSION['cart'][$productId] = $quantity;
}

function remove_from_cart(int $productId): void
{
    unset($_SESSION['cart'][$productId]);
}

function clear_cart(): void
{
    $_SESSION['cart'] = [];
}

function get_cart_items(): array
{
    $items = [];

    foreach (cart() as $productId => $quantity) {
        $product = get_product(
            (int) $productId,
            false
        );

        if (
            !$product
            || !(int) $product['is_active']
        ) {
            unset($_SESSION['cart'][$productId]);
            continue;
        }

        $quantity = min(
            (int) $quantity,
            (int) $product['stock_quantity']
        );

        if ($quantity < 1) {
            unset($_SESSION['cart'][$productId]);
            continue;
        }

        $product['cart_quantity'] = $quantity;

        $product['line_total'] =
            product_price($product) * $quantity;

        $items[] = $product;
    }

    return $items;
}

function cart_subtotal(): float
{
    return array_reduce(
        get_cart_items(),
        fn (
            float $total,
            array $item
        ): float => $total + (float) $item['line_total'],
        0.0
    );
}

function order_statuses(): array
{
    return [
        'Pending',
        'Confirmed',
        'Processing',
        'Out for Delivery',
        'Completed',
        'Cancelled',
    ];
}

function admin_logged_in(): bool
{
    return !empty($_SESSION['admin_id']);
}

function require_admin(): void
{
    if (!admin_logged_in()) {
        redirect('admin/login.php');
    }
}

function upload_product_image(
    array $file,
    ?string $oldImage = null
): ?string {
    if (
        ($file['error'] ?? UPLOAD_ERR_NO_FILE)
        === UPLOAD_ERR_NO_FILE
    ) {
        return $oldImage;
    }

    if (
        ($file['error'] ?? UPLOAD_ERR_OK)
        !== UPLOAD_ERR_OK
    ) {
        throw new RuntimeException(
            'Image upload failed.'
        );
    }

    if (
        ($file['size'] ?? 0)
        > MAX_UPLOAD_SIZE
    ) {
        throw new RuntimeException(
            'Image must be 5MB or smaller.'
        );
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    $mime = (new finfo(FILEINFO_MIME_TYPE))
        ->file($file['tmp_name']);

    if (!isset($allowed[$mime])) {
        throw new RuntimeException(
            'Only JPG, PNG and WebP images are allowed.'
        );
    }

    if (!is_dir(PRODUCT_UPLOAD_DIR)) {
        mkdir(
            PRODUCT_UPLOAD_DIR,
            0755,
            true
        );
    }

    $filename = bin2hex(
        random_bytes(16)
    ) . '.' . $allowed[$mime];

    if (
        !move_uploaded_file(
            $file['tmp_name'],
            PRODUCT_UPLOAD_DIR . $filename
        )
    ) {
        throw new RuntimeException(
            'Could not save the uploaded image.'
        );
    }

    if ($oldImage) {
        delete_product_image($oldImage);
    }

    return $filename;
}

function delete_product_image(
    ?string $image
): void {
    if (!$image) {
        return;
    }

    $path = PRODUCT_UPLOAD_DIR
        . basename($image);

    if (is_file($path)) {
        @unlink($path);
    }
}