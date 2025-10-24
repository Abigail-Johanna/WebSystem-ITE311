<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="bi bi-file-earmark-text"></i> Course Materials</h4>
            </div>
            <div class="card-body">
                <!-- Display Success Message -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Display Error Message -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($materials)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Course</th>
                                    <th width="35%">File Name</th>
                                    <th width="15%">Uploaded Date</th>
                                    <th width="20%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materials as $index => $material): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <?= esc($material['course_name'] ?? 'N/A') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <i class="bi bi-file-earmark-pdf text-danger"></i>
                                            <?= esc($material['file_name']) ?>
                                        </td>
                                        <td>
                                            <?php 
                                            if ($material['created_at']) {
                                                echo date('M d, Y', strtotime($material['created_at']));
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= site_url('/materials/download/' . $material['id']) ?>" 
                                               class="btn btn-sm btn-success" 
                                               title="Download">
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                            
                                            <?php if (session()->get('role') === 'admin' || session()->get('role') === 'instructor'): ?>
                                                <a href="<?= site_url('/materials/delete/' . $material['id']) ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   title="Delete"
                                                   onclick="return confirm('Are you sure you want to delete this material?')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0" role="alert">
                        <i class="bi bi-info-circle"></i> No materials available for your enrolled courses yet.
                    </div>
                <?php endif; ?>

                <div class="mt-3">
                    <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<?= $this->endSection() ?>
