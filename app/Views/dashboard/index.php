<?= $this->extend('template') ?>
<?= $this->section('content') ?>

<style>
.welcome-wrap {
    padding: 24px 0;
}

.welcome-banner {
    background: linear-gradient(135deg, #10f214 0%, #0dfda1 100%);
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(12, 120, 40, 0.12);
    padding: 20px 28px;
    max-width: 1200px;
    width: 100%;
    margin: 0 auto;
    color: rgba(0,0,0,0.9);
    display: block;
}

.welcome-banner h1 {
    margin: 0;
    font-size: clamp(20px, 3.5vw, 32px);
    font-weight: 800;
    line-height: 1.1;
    text-align: left;
    font-family: Arial, sans-serif;
}

.welcome-sub {
    margin-top: 6px;
    color: rgba(0,0,0,0.65);
    font-size: 14px;
}

.dashboard-grid {
    margin-top: 28px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 18px;
}

.info-card {
    background: #fff;
    border-radius: 10px;
    padding: 18px;
    border: 1px solid rgba(0,0,0,0.06);
    box-shadow: 0 4px 10px rgba(0,0,0,0.04);
}
.info-card h5 { margin: 0 0 8px 0; font-weight:700; }
.info-card p  { margin: 0; color: #555; font-size: 0.95rem; }
</style>

<div class="welcome-wrap">
    <div class="container">
        <div class="welcome-banner" role="region" aria-label="Welcome banner">
            <h1>Welcome, <?= esc($user_name) ?>!</h1>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
