<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'Admin Login';
$error = null;

if (is_post()) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter your email and password.';
    } else {
        try {
            $stmt = db()->prepare(
                'SELECT id, name, email, password
                 FROM admins
                 WHERE email = ?
                 LIMIT 1'
            );

            $stmt->execute([$email]);

            $admin = $stmt->fetch();

            if (
                $admin &&
                password_verify($password, $admin['password'])
            ) {
                session_regenerate_id(true);

                $_SESSION['admin_id'] = (int) $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];
                $_SESSION['admin_email'] = $admin['email'];

                redirect('admin/dashboard.php');
            }

            $error = 'Invalid email or password.';
        } catch (Throwable $e) {
            $error = 'Unable to process your login right now.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="Balmed 18 administration login"
    >

    <title><?= e($pageTitle) ?> | Balmed 18</title>

    <link
        rel="stylesheet"
        href="<?= e(url('assets/css/admin.css')) ?>"
    >
</head>

<body class="admin-auth-page">

<div class="admin-auth-shell">

    <div class="admin-auth-brand">
        <a
            href="<?= e(url('index.php')) ?>"
            class="admin-brand"
        >
            <span class="admin-brand-mark">18</span>

            <span class="admin-brand-text">
                <strong>Balmed 18</strong>
                <small>Administration</small>
            </span>
        </a>
    </div>

    <main class="admin-auth-main">

        <section class="admin-login-card">

            <div class="admin-login-header">

                <span class="admin-eyebrow">
                    Administration
                </span>

                <h1>
                    Welcome back.
                </h1>

                <p>
                    Sign in to manage your Balmed 18 store.
                </p>

            </div>

            <?php if ($error !== null): ?>

                <div
                    class="admin-alert admin-alert-error"
                    role="alert"
                >
                    <?= e($error) ?>
                </div>

            <?php endif; ?>

            <form
                method="post"
                action="<?= e(url('admin/login.php')) ?>"
                class="admin-login-form"
            >

                <div class="admin-form-group">

                    <label for="email">
                        Email address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= old('email') ?>"
                        autocomplete="email"
                        placeholder="admin@balmed18.com"
                        required
                    >

                </div>

                <div class="admin-form-group">

                    <div class="admin-label-row">

                        <label for="password">
                            Password
                        </label>

                    </div>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        placeholder="Enter your password"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="admin-login-button"
                >
                    Sign in
                </button>

            </form>

            <div class="admin-login-footer">

                <a href="<?= e(url('index.php')) ?>">
                    <span aria-hidden="true">←</span>
                    Back to store
                </a>

            </div>

        </section>

    </main>

    <footer class="admin-auth-footer">
        <span>
            © <?= date('Y') ?> Balmed 18
        </span>

        <span>
            Administration Portal
        </span>
    </footer>

</div>

</body>
</html>