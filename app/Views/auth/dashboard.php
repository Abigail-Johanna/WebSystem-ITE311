<?= $this->extend('template') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <div class="card bg-primary text-white shadow-sm p-4 rounded-3">
                <h2 class="mb-0">Welcome back, <?= esc($name) ?> 👋</h2>
                <p class="mb-0">You are now logged in to the LMS dashboard.</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <h5 class="card-title">📚 Books</h5>
                    <p class="display-6 fw-bold text-primary">120</p>
                    <p class="text-muted">Total Available</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <h5 class="card-title">👥 Users</h5>
                    <p class="display-6 fw-bold text-success">45</p>
                    <p class="text-muted">Active Members</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <h5 class="card-title">💬 Messages</h5>
                    <p class="display-6 fw-bold text-warning">8</p>
                    <p class="text-muted">Unread</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-light fw-bold">
                    Recent Activity
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">📖 John borrowed <strong>"CI4 Systems"</strong></li>
                        <li class="list-group-item">👤 New user <strong>Abeguil Ruales</strong> registered</li>
                        <li class="list-group-item">🔄 Abigail returned <strong>"All Programming"</strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
