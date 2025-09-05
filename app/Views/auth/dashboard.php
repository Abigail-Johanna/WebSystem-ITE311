<?= $this->extend('template') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <div class="card text-black shadow-sm p-4 rounded-3" style="background-color: #10f214; background-image: linear-gradient(to right, #10f214, #0dfda1);">
                <h2 class="mb-0">Welcome back, <?= esc($name) ?>! </h2>
                <p class="mb-0">You are now logged in to the LMS dashboard.</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>