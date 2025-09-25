<<<<<<< HEAD
<?= $this->extend('template') ?>
<?= $this->section('content') ?>

<style>
/* Full-screen flex container beneath the fixed navbar */
.fullscreen-auth {
    position: fixed;
    top: 56px; /* height of navbar */
    left: 0;
    width: 100%;
    height: calc(100% - 56px);
    background: linear-gradient(135deg, #10f214ff, #0dfda1ff); 
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1rem;
    z-index: 0;
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
    color: #000;
}
</style>

<div class="fullscreen-auth">
    <div class="auth-card">
        <h2 class="auth-title">Register</h2>

        <!-- Flash Messages -->
        <?php if(session()->has('success')): ?>
            <div class="alert alert-success"><?= esc(session('success')) ?></div>
        <?php endif; ?>
        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger"><?= esc(session('error')) ?></div>
        <?php endif; ?>
        <?php if(session()->has('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach(session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('register') ?>" method="post" novalidate>
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="name" class="form-label text-secondary">Full Name</label>
                <input type="text" id="name" name="name"
                       value="<?= old('name') ?>"
                       class="form-control border-secondary"
                       placeholder="Enter your full name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label text-secondary">Email Address</label>
                <input type="email" id="email" name="email"
                       value="<?= old('email') ?>"
                       class="form-control border-secondary"
                       placeholder="Enter a valid email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label text-secondary">Password</label>
                <input type="password" id="password" name="password"
                       class="form-control border-secondary"
                       placeholder="Enter password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label text-secondary">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password"
                       class="form-control border-secondary"
                       placeholder="Re-enter password" required>
            </div>
            <div class="mb-4">
                <label for="role" class="form-label text-secondary">Role</label>
                <select id="role" name="role" class="form-select border-secondary" required>
                    <option value="">-- Select Role --</option>
                    <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="user"  <?= old('role') === 'user'  ? 'selected' : '' ?>>User</option>
                </select>
            </div>
            <button type="submit" class="btn w-100 text-white" style="background-color:#d4a373;">Register</button>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">
                Already have an account?
                <a href="<?= site_url('login') ?>" style="color:#d4a373;">Log in here</a>
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
    <title><?= esc($title ?? 'Register') ?> | LMS</title>
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
            color: #000000ff;
        }
    </style>
</head>
<body>

<div class="auth-card">
    <h2 class="auth-title">LMS Registration</h2>

    <?php if(isset($validation)): ?>
        <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
    <?php endif; ?>

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
        
         <div class="mb-3">
            <label for="role" class="form-label">User Role</label>
            <select name="role" id="role" class="form-select" required>
                <option value="">-- Select Role --</option>
                <option value="student" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="student" <?= old('role') === 'user' ? 'selected' : '' ?>>User</option>
            </select>
        </div>
       
        <button type="submit" class="btn btn-success w-100">Register</button>
    </form>

    <p class="text-center mt-3">
        Already have an account? <a href="<?= site_url('login') ?>">Login here</a>
    </p>
</div>

</body>
</html>
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
