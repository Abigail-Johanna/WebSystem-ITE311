<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= esc($title ?? 'App') ?></title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="base-url" content="<?= base_url() ?>">

<?php
    $role   = session('user_role');          
    $name   = session('user_name');          
    $isAuth = ! empty(session('logged_in')) || ! empty($role);
?>

<?php if ($isAuth): ?>
    <style>
        body { background: #f8f9fa; font-family: 'Inter', sans-serif; }
        .sidebar {
            height: 100vh; background: #fefefeff; color: #fff; padding: 20px;
            position: fixed; top: 0; left: 0; width: 240px;
        }
        .sidebar h4 { font-weight: 700; margin-bottom: 30px; }
        .sidebar a {
            display: block; color: #adb5bd; padding: 10px;
            border-radius: 8px; text-decoration: none; margin-bottom: 8px;
        }
        .sidebar a:hover, .sidebar a.active { background: #0d6efd; color: #fff; }
        .content { margin-left: 260px; padding: 40px; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    </style>
<?php else: ?>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #10f214 0%, #0dfda1 100%);
            background-size: cover;
        }
        .center-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .auth-card {
            background: #fff;
            border-radius: 15px;
            padding: 2rem;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
    </style>
<?php endif; ?>
</head>
<body>

<?php if ($isAuth): ?>

    <div class="content">
        <h2 class="fw-bold">Welcome, <?= esc($name ?? 'User') ?></h2>

        <?php if(session()->has('success')): ?>
            <div class="alert alert-success"><?= esc(session('success')) ?></div>
        <?php endif; ?>
        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger"><?= esc(session('error')) ?></div>
        <?php endif; ?>

<?php else: ?>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('js/notifications.js') ?>"></script>

