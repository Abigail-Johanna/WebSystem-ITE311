<?= $this->extend('template') ?>
<?= $this->section('content') ?>

<div class="container-fluid mt-4">
        <div class="row mb-4">
            <div class="col">
                <div class="card text-black shadow-sm p-4 rounded-3" style="background: linear-gradient(135deg, #10f214, #0dfda1);">
                    <h1 class="mb-2">Welcome, <?= esc($name) ?>!</h1>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>