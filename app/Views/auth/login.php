<?= $this->include('template/header') ?>
<div class="container center-wrapper">
    <div class="auth-card">
        <h3 class="text-center fw-bold">LMS Login</h3>
        <p class="text-muted text-center mb-4">Welcome Back!</p>

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

        <form method="post" action="<?= site_url('login') ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input name="email" type="email" class="form-control"
                       value="<?= old('email') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input name="password" type="password" class="form-control" required>
            </div>

            <button class="btn btn-success w-100" type="submit">Login</button>
        </form>
    </div>
</div>
<?= $this->include('template/footer') ?>
