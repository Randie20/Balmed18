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
                $admin
                && password_verify(
                    $password,
                    $admin['password']
                )
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

require __DIR__ . '/../includes/store_header.php';
?>

<section class="auth-section">
    <div class="container">

        <div class="auth-card">

            <div class="auth-header">
                <span class="eyebrow">Administration</span>

                <h1>
                    Welcome back.
                </h1>

                <p>
                    Sign in to manage your Balmed 18 store.
                </p>
            </div>

            <?php if ($error): ?>

                <div class="alert alert-error">
                    <?= e($error) ?>
                </div>

            <?php endif; ?>

            <form
                method="post"
                action="<?= e(url('admin/login.php')) ?>"
                class="auth-form"
            >

                <div class="form-group">
                    <label for="email">
                        Email address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= old('email') ?>"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Sign in
                </button>

            </form>

            <div class="auth-footer">
                <a href="<?= e(url('index.php')) ?>">
                    ← Back to store
                </a>
            </div>

        </div>

    </div>
</section>

<?php require __DIR__ . '/../includes/store_footer.php'; ?>