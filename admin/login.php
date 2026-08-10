<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

if (admin_logged_in()) {
    redirect('admin/dashboard.php');
}

$error = null;

if (is_post()) {
    verify_csrf();

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT * FROM admins WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        session_regenerate_id(true);

        $_SESSION['admin_id'] = (int) $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_email'] = $admin['email'];

        redirect('admin/dashboard.php');
    }

    $error = 'Invalid email or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Balmed 18</title>
    <link rel="stylesheet" href="<?= url('assets/css/admin.css') ?>">
</head>
<body class="login-body">
    <div class="login-card">
        <a class="admin-logo login-logo" href="<?= url('index.php') ?>">
            <span>18</span>
            <strong>Balmed 18</strong>
            <small>Admin</small>
        </a>

        <div>
            <span class="eyebrow">Secure access</span>
            <h1>Welcome back.</h1>
            <p>Manage products, categories and customer orders.</p>
        </div>

        <?php if ($error): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" class="admin-form">
            <?= csrf_field() ?>

            <div class="form-field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" required autocomplete="email" value="<?= old('email') ?>">
            </div>

            <div class="form-field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
            </div>

            <button class="admin-btn" type="submit">Sign in</button>
        </form>

        <a class="back-store" href="<?= url('index.php') ?>">← Back to storefront</a>
    </div>
</body>
</html>
