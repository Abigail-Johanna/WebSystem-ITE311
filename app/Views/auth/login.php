<<<<<<< HEAD
<?= $this->extend('template') ?>
<?= $this->section('content') ?>

<style>
/* cover everything below the fixed navbar */
.fullscreen-auth {
    position: fixed;
    top: 56px;         /* same height as navbar */
    left: 0;
    width: 100%;
    height: calc(100% - 56px);
    background: linear-gradient(135deg,#10f214,#0dfda1);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    z-index: 0;        /* behind navbar */
}
.auth-card {
    background: #fff;
    border-radius: 12px;
    padding: 2rem;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}
</style>

<div class="fullscreen-auth">
    <div class="auth-card">
        <h2 class="text-center fw-bold mb-4">Login</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form action="<?= site_url('login') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100" style="background:#d4a373;border:none;">Sign In</button>
        </form>

        <div class="text-center mt-3">
            <small>Don’t have an account?
                <a href="<?= site_url('register') ?>" style="color:#a06b48;">Create one</a>
            </small>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
=======
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Login') ?> | LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #10f214ff, #0dfda1ff);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            padding: 2rem;
        }
        .auth-title {
            font-weight: bold;
            text-align: center;
            margin-bottom: 1.5rem;
            color: #000000ff;
        }
    </style>
</head>
<body>

<div class="auth-card">
    <h2 class="auth-title">LMS Login</h2>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

<form action="<?= site_url('login/submit') ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" id="email" value="<?= old('email') ?>" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="text-center mt-3">
        Don’t have an account? <a href="<?= site_url('register') ?>">Register here</a>
    </p>
</div>

</body>
</html>
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
