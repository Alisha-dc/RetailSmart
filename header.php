<?php

require_once __DIR__ . '/../../config/helpers.php';
$user = current_user();
$currentPage = basename($_SERVER['PHP_SELF'] ?? 'index.php');
$isAdminSection = str_contains($_SERVER['PHP_SELF'] ?? '', '/admin/');
$basePath = $isAdminSection ? '../' : '';
$rootPath = $isAdminSection ? '../' : '';
?>


<!DOCTYPE html>
<html lang ="eng">
    <head>
        <meta charset ="UTF-8">
        <meta name ="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            <?= e($pageTitle ?? APP_NAME) ?>
</title>
<link rel="stylesheet" href="<?= $basePath ?>assets/css/style.css?v=<?= time() ?>">
</head>
<body>
    <header class="site-header">
        <div class="container nav-container">
            <a href="<?= $rootPath ?>index.php" class="logo">
            RetailSmart
</a>



        <nav aria-label="Main navigation">

            <a href="<?= $rootPath ?>index.php" class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>" <?= $currentPage === 'index.php' ? 'aria-current="page"' : '' ?>>
                Products
            </a>


            <?php if ($user): ?>

                <a href="<?= $rootPath ?>cart.php" class="nav-link <?= $currentPage === 'cart.php' ? 'active' : '' ?>" <?= $currentPage === 'cart.php' ? 'aria-current="page"' : '' ?>>
                    Cart
                </a>

                <a href="<?= $rootPath ?>orders.php" class="nav-link <?= $currentPage === 'orders.php' ? 'active' : '' ?>" <?= $currentPage === 'orders.php' ? 'aria-current="page"' : '' ?>>
                    My Orders
                </a>


                <?php if ($user['role'] === 'admin'): ?>

                    <a href="<?= $isAdminSection ? 'dashboard.php' : 'admin/dashboard.php' ?>" class="nav-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>" <?= $currentPage === 'dashboard.php' ? 'aria-current="page"' : '' ?>>
                        Admin
                    </a>

                <?php endif; ?>


                <span class="welcome">
                    Hi, <?= e($user['name']) ?>
                </span>

                <a href="<?= $rootPath ?>action.php?action=logout" class="nav-link">
                    Logout
                </a>

            <?php else: ?>

                <a href="<?= $rootPath ?>login.php" class="nav-link <?= $currentPage === 'login.php' ? 'active' : '' ?>" <?= $currentPage === 'login.php' ? 'aria-current="page"' : '' ?>>
                    Login
                </a>

                <a href="<?= $rootPath ?>register.php" class="nav-link <?= $currentPage === 'register.php' ? 'active' : '' ?>" <?= $currentPage === 'register.php' ? 'aria-current="page"' : '' ?>>
                    Register
                </a>

            <?php endif; ?>

        </nav>

    </div>

</header>


<main class="container">