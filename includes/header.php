<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? APP_NAME;
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
        content="Balmed 18 — Lip care is also skin care."
    >

    <title>
        <?= e($pageTitle) ?> | <?= e(APP_NAME) ?>
    </title>

    <!-- Google Fonts -->
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- Balmed 18 Stylesheet -->
    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>/assets/css/style.css"
    >

</head>

<body>

<header class="site-header">

    <div class="container header-inner">

        <a
            href="<?= BASE_URL ?>/index.php"
            class="brand"
            aria-label="Balmed 18 home"
        >

            <span class="brand-mark">
                18
            </span>

            <span class="brand-name">
                Balmed 18
            </span>

        </a>

        <nav class="desktop-nav">

            <a href="<?= BASE_URL ?>/pages/shop.php">
                Shop
            </a>

            <a href="<?= BASE_URL ?>/pages/shop.php">
                Our Collection
            </a>

            <a href="<?= BASE_URL ?>/pages/shop.php">
                Bundles
            </a>

            <a href="<?= BASE_URL ?>/pages/shop.php">
                About
            </a>

        </nav>

        <div class="header-actions">

            <a
                href="<?= BASE_URL ?>/pages/cart.php"
                class="cart-link"
                aria-label="Shopping cart"
            >

                <span class="cart-icon">
                    ♡
                </span>

                <?php if (getCartItemCount() > 0): ?>

                    <span class="cart-count">
                        <?= getCartItemCount() ?>
                    </span>

                <?php endif; ?>

            </a>

            <button
                type="button"
                class="menu-toggle"
                aria-label="Open menu"
                aria-expanded="false"
            >

                <span></span>
                <span></span>

            </button>

        </div>

    </div>

</header>

<main></main>