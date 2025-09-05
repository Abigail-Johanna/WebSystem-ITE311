<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Register') ?> | LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #6610f2, #0d6efd);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .auth-card {
            width: 100%;
            max-width: 500px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            padding: 2rem;
        }
        .auth-title {
            font-weight: bold;
            text-align: center;
            margin-bottom: 1.5rem;
            color: #6610f2;
        }
    </style>
</head>
<body>

<div class="auth-card">
    <h2 class="auth-title">LMS Registration</h2>

    <!-- Validation Errors -->
    <?php if(isset($validation)): ?>
        <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
    <?php endif; ?>

    <!-- Flash Messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

<form action="<?= site_url('register/submit') ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" id="name" value="<?= old('name') ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" id="email" value="<?= old('email') ?>" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password (min 8 chars)</label>
            <input type="password" name="password" class="form-control" id="password" required>
        </div>

        <div class="mb-3">
            <label for="password_confirm" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirm" class="form-control" id="password_confirm" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Register</button>
    </form>

    <p class="text-center mt-3">
        Already have an account? <a href="<?= site_url('login') ?>">Login here</a>
    </p>
</div>

</body>
</html>
