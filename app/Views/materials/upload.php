<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-cloud-upload"></i> Upload Course Material</h4>
            </div>
            <div class="card-body">
                <!-- Display Success Message -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> <strong>Success!</strong> <?= $_SESSION['success'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <!-- Display Error Message -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <strong>Error!</strong> <?= $_SESSION['error'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- File Upload Form -->
                <form action="<?= site_url('/admin/course/' . $course_id . '/upload') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4">
                        <label for="material_file" class="form-label fw-bold">
                            Select File <span class="text-danger">*</span>
                        </label>
                        <input type="file" 
                               class="form-control form-control-lg" 
                               id="material_file" 
                               name="material_file" 
                               accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.zip"
                               required>
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> Allowed file types: PDF, DOC, DOCX, PPT, PPTX, TXT, ZIP. Maximum size: 10MB
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                        <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-upload"></i> Upload Material
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Information Card -->
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-lightbulb"></i> Upload Guidelines</h5>
                <ul class="mb-0">
                    <li>Only upload course-related materials</li>
                    <li>Ensure files are properly named for easy identification</li>
                    <li>Large files may take longer to upload</li>
                    <li>Uploaded materials will be available to enrolled students</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons (optional if not already included) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<?= $this->endSection() ?>
