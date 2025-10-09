<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= isset($title) ? esc($title) : 'LMS' ?> | ITE311</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { padding-top: 56px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= site_url('/') ?>">LMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= (current_url() == site_url('/')) ? 'active' : '' ?>" href="<?= site_url('/') ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (current_url() == site_url('about')) ? 'active' : '' ?>" href="<?= site_url('about') ?>">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (current_url() == site_url('contact')) ? 'active' : '' ?>" href="<?= site_url('contact') ?>">Contact</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <?php if (session()->get('isLoggedIn')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (current_url() == site_url('dashboard')) ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('logout') ?>">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (current_url() == site_url('login')) ? 'active' : '' ?>" href="<?= site_url('login') ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (current_url() == site_url('register')) ? 'active' : '' ?>" href="<?= site_url('register') ?>">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <?= $this->renderSection('content') ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>