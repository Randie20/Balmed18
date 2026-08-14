<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/database.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please enter your email and password.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $pdo->prepare(
            'SELECT id, name, email, password
             FROM admins
             WHERE email = :email
             LIMIT 1'
        );

        $stmt->execute([
            'email' => $email,
        ]);

        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);

            $_SESSION['admin_id'] = (int) $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_email'] = $admin['email'];

            header('Location: dashboard.php');
            exit;
        }

        $error = 'Invalid email or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login | Balmed 18</title>

    <style>
        :root {
            --primary: #210b2c;
            --primary-dark: #16071e;
            --accent: #d4a574;
            --background: #f6f3f0;
            --surface: #ffffff;
            --text: #211c20;
            --muted: #766d72;
            --border: #e5dfe2;
            --danger: #b42318;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background:
                radial-gradient(
                    circle at top left,
                    rgba(33, 11, 44, 0.08),
                    transparent 35%
                ),
                var(--background);
            color: var(--text);
            font-family:
                Inter,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
        }

        .brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-mark {
            width: 58px;
            height: 58px;
            margin: 0 auto 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            background: var(--primary);
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            box-shadow: 0 12px 30px rgba(33, 11, 44, 0.18);
        }

        .brand h1 {
            color: var(--primary);
            font-size: 27px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .brand p {
            margin-top: 7px;
            color: var(--muted);
            font-size: 14px;
        }

        .login-card {
            padding: 34px;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: var(--surface);
            box-shadow: 0 20px 60px rgba(33, 11, 44, 0.08);
        }

        .login-card h2 {
            margin-bottom: 7px;
            color: var(--text);
            font-size: 22px;
        }

        .login-card .subtitle {
            margin-bottom: 26px;
            color: var(--muted);
            font-size: 14px;
        }

        .alert {
            margin-bottom: 20px;
            padding: 12px 14px;
            border: 1px solid #f3c5c1;
            border-radius: 10px;
            background: #fff5f4;
            color: var(--danger);
            font-size: 14px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 19px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text);
            font-size: 14px;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            height: 48px;
            padding: 0 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            outline: none;
            background: #ffffff;
            color: var(--text);
            font: inherit;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .form-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(33, 11, 44, 0.08);
        }

        .login-button {
            width: 100%;
            height: 50px;
            margin-top: 6px;
            border: 0;
            border-radius: 10px;
            background: var(--primary);
            color: #ffffff;
            cursor: pointer;
            font: inherit;
            font-weight: 600;
            transition:
                background 0.2s ease,
                transform 0.2s ease;
        }

        .login-button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .footer-note {
            margin-top: 22px;
            text-align: center;
            color: var(--muted);
            font-size: 12px;
        }

        @media (max-width: 480px) {
            body {
                padding: 16px;
            }

            .login-card {
                padding: 24px 20px;
                border-radius: 16px;
            }

            .brand h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <div class="brand">
        <div class="brand-mark">B18</div>

        <h1>Balmed 18</h1>

        <p>Administration Portal</p>
    </div>

    <div class="login-card">

        <h2>Welcome back</h2>

        <p class="subtitle">
            Sign in to manage your store.
        </p>

        <?php if ($error !== ''): ?>
            <div class="alert">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">

            <div class="form-group">
                <label for="email">Email address</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="admin@balmed18.com"
                    value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    autocomplete="username"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="login-button">
                Sign In
            </button>

        </form>

    </div>

    <p class="footer-note">
        Balmed 18 &copy; <?= date('Y') ?>
    </p>

</div>

</body>
</html>