<?= $this->include('template/header') ?>
<div class="container center-wrapper">
    <div class="auth-card">
        <h3 class="mb-2 text-center fw-bold">Create Account</h3>
        <p class="text-muted text-center mb-4">LMS Register</p>

        <?php if(session()->has('errors')): ?>
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    <?php foreach(session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if(session()->has('success')): ?>
            <div class="alert alert-success"><?= esc(session('success')) ?></div>
        <?php endif; ?>
        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger"><?= esc(session('error')) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('register') ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input name="name" type="text" class="form-control"
                       value="<?= old('name') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input name="email" type="email" class="form-control"
                       value="<?= old('email') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input name="password" type="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input name="confirm_password" type="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" required>
                    <option value="">-- Select Role --</option>
                    <option value="admin"   <?= old('role')==='admin'   ? 'selected' : '' ?>>Admin</option>
                    <option value="teacher" <?= old('role')==='teacher' ? 'selected' : '' ?>>Teacher</option>
                    <option value="student" <?= old('role')==='student' ? 'selected' : '' ?>>Student</option>
                </select>
            </div>

            <button class="btn btn-primary w-100" type="submit">Register</button>

            <div class="text-center mt-3">
                <a href="<?= site_url('login') ?>">Already have an account? Login</a>
            </div>
        </form>
    </div>
</div>
<?= $this->include('template/footer') ?>
