<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? 'Balmed 18';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Balmed 18 — bottled beauty, made with care in Nairobi.">
    <title><?= e($pageTitle) ?> | Balmed 18</title>
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="<?= url('index.php') ?>">
            <span class="brand-mark">18</span>
            <span>
                <strong>Balmed 18</strong>
                <small>Handcrafted in Kenya</small>
            </span>
        </a>

        <button class="menu-toggle" type="button" aria-label="Toggle menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <nav class="main-nav" aria-label="Main navigation">
            <a href="<?= url('index.php') ?>">Home</a>
            <a href="<?= url('products.php') ?>">Shop</a>
            <a href="<?= url('index.php#story') ?>">Our story</a>
            <a href="<?= url('index.php#why-us') ?>">Why Balmed 18</a>
            <a class="cart-link" href="<?= url('cart.php') ?>">
                Cart <span class="cart-count"><?= cart_count() ?></span>
            </a>
        </nav>
    </div>
</header>

<main>
