<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';
require_admin();

$pageTitle = $pageTitle ?? 'Admin';
$currentAdminPage = $currentAdminPage ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | Balmed 18 Admin</title>
    <link rel="stylesheet" href="<?= url('assets/css/admin.css') ?>">
</head>
<body class="admin-body">
<aside class="admin-sidebar">
    <a class="admin-logo" href="<?= url('admin/dashboard.php') ?>">
        <span>18</span>
        <strong>Balmed 18</strong>
        <small>Admin</small>
    </a>

    <nav class="admin-nav">
        <a class="<?= $currentAdminPage === 'dashboard' ? 'active' : '' ?>" href="<?= url('admin/dashboard.php') ?>">Dashboard</a>
        <a class="<?= $currentAdminPage === 'products' ? 'active' : '' ?>" href="<?= url('admin/products/index.php') ?>">Products</a>
        <a class="<?= $currentAdminPage === 'categories' ? 'active' : '' ?>" href="<?= url('admin/categories/index.php') ?>">Categories</a>
        <a class="<?= $currentAdminPage === 'orders' ? 'active' : '' ?>" href="<?= url('admin/orders/index.php') ?>">Orders</a>
        <a href="<?= url('index.php') ?>">View storefront</a>
        <a href="<?= url('admin/logout.php') ?>">Logout</a>
    </nav>
</aside>

<div class="admin-main">
    <header class="admin-topbar">
        <div>
            <span class="eyebrow">Balmed 18</span>
            <h1><?= e($pageTitle) ?></h1>
        </div>
        <div class="admin-user">
            <?= e($_SESSION['admin_name'] ?? 'Administrator') ?>
        </div>
    </header>

    <section class="admin-content">
        <?php if ($message = get_flash('success')): ?>
            <div class="alert success"><?= e($message) ?></div>
        <?php endif; ?>

        <?php if ($message = get_flash('error')): ?>
            <div class="alert error"><?= e($message) ?></div>
        <?php endif; ?>
