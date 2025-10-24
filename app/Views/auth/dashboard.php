<?= $this->include('template/header') ?>

<?php if ($user_role === 'admin'): ?>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> <strong>Success!</strong> <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <strong>Error!</strong> <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Total Users</h6>
                <h3><?= esc($stats['total_users'] ?? 0) ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Total Courses</h6>
                <h3><?= esc($stats['total_courses'] ?? 0) ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Active Students</h6>
                <h3><?= esc($stats['active_students'] ?? 0) ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h6>Active Teachers</h6>
                <h3><?= esc($stats['active_teachers'] ?? 0) ?></h3>
            </div>
        </div>
    </div>

    <!-- Courses Management Section for Admin -->
    <div class="card mt-4">
        <div class="card-header fw-bold"><i class="bi bi-book"></i> Courses Management</div>
        <div class="card-body">
            <?php if (!empty($courses)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Course Title</th>
                                <th width="40%">Description</th>
                                <th width="15%">Created</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $index => $course): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><strong><?= esc($course['title']) ?></strong></td>
                                    <td><?= esc(substr($course['description'] ?? 'No description', 0, 60)) ?><?= strlen($course['description'] ?? '') > 60 ? '...' : '' ?></td>
                                    <td><?= isset($course['created_at']) ? date('M d, Y', strtotime($course['created_at'])) : 'N/A' ?></td>
                                    <td>
                                        <a href="<?= site_url('/admin/course/' . $course['id'] . '/upload') ?>" 
                                           class="btn btn-sm btn-primary"
                                           title="Upload material for this course">
                                            <i class="bi bi-upload"></i> Upload
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted mb-0">No courses found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Course Materials Section for Admin -->
    <div class="card mt-4">
        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
            <span><i class="bi bi-file-earmark-text"></i> Recent Course Materials</span>
            <?php if (!empty($materials)): ?>
                <span class="badge bg-success"><?= count($materials) ?> materials</span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if (!empty($materials)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Course</th>
                                <th>File Name</th>
                                <th>Uploaded</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($materials as $material): ?>
                                <tr>
                                    <td><span class="badge bg-primary"><?= esc($material['course_name'] ?? 'N/A') ?></span></td>
                                    <td><i class="bi bi-file-earmark-pdf text-danger"></i> <?= esc($material['file_name']) ?></td>
                                    <td><?= date('M d, Y', strtotime($material['created_at'] ?? 'now')) ?></td>
                                    <td>
                                        <a href="<?= site_url('/materials/download/' . $material['id']) ?>" class="btn btn-sm btn-success" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <a href="<?= site_url('/materials/delete/' . $material['id']) ?>" 
                                           class="btn btn-sm btn-danger" 
                                           title="Delete"
                                           onclick="return confirm('Delete this material?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i> No materials uploaded yet. Click the "Upload" button on any course above to add materials.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<?php elseif ($user_role === 'teacher'): ?>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> <strong>Success!</strong> <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <strong>Error!</strong> <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card mt-4">
        <div class="card-header fw-bold"><i class="bi bi-book"></i> My Courses</div>
        <div class="card-body">
            <?php if (!empty($courses)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Course Title</th>
                                <th width="40%">Description</th>
                                <th width="15%">Created</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $index => $c): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><strong><?= esc($c['title']) ?></strong></td>
                                    <td><?= esc(substr($c['description'] ?? 'No description', 0, 60)) ?><?= strlen($c['description'] ?? '') > 60 ? '...' : '' ?></td>
                                    <td><?= isset($c['created_at']) ? date('M d, Y', strtotime($c['created_at'])) : 'N/A' ?></td>
                                    <td>
                                        <a href="<?= site_url('/admin/course/' . $c['id'] . '/upload') ?>" 
                                           class="btn btn-sm btn-primary"
                                           title="Upload material for this course">
                                            <i class="bi bi-upload"></i> Upload
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted mb-0">No courses assigned to you yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Course Materials Section -->
    <div class="card mt-4">
        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
            <span><i class="bi bi-file-earmark-text"></i> Recent Course Materials</span>
            <?php if (!empty($materials)): ?>
                <span class="badge bg-success"><?= count($materials) ?> materials</span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if (!empty($materials)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Course</th>
                                <th>File Name</th>
                                <th>Uploaded</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($materials as $material): ?>
                                <tr>
                                    <td><span class="badge bg-primary"><?= esc($material['course_name'] ?? 'N/A') ?></span></td>
                                    <td><i class="bi bi-file-earmark-pdf text-danger"></i> <?= esc($material['file_name']) ?></td>
                                    <td><?= date('M d, Y', strtotime($material['created_at'] ?? 'now')) ?></td>
                                    <td>
                                        <a href="<?= site_url('/materials/download/' . $material['id']) ?>" class="btn btn-sm btn-success">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <a href="<?= site_url('/materials/delete/' . $material['id']) ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Delete this material?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i> No materials uploaded yet. Click the "Upload" button on any course above to add materials for your students.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<?php elseif ($user_role === 'student'): ?>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> <strong>Success!</strong> <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <strong>Error!</strong> <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-center p-3">
                <h5>My Enrolled Courses</h5>
                <h3><?= esc(count($enrolledCourses ?? [])) ?></h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center p-3">
                <h5>Upcoming Deadlines</h5>
                <h3><?= esc(isset($deadlines) ? count($deadlines) : 0) ?></h3>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        Welcome to your student dashboard! You can view and enroll in available courses below.
    </div>

    <div id="csrfToken" style="display:none;">
        <?= csrf_field() ?>
    </div>

    <div id="alertBox" class="alert d-none mt-3"></div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header fw-bold">My Enrolled Courses</div>
                <div class="card-body">
                    <ul id="enrolledCourses" class="list-group">
                        <?php if (!empty($enrolledCourses)): ?>
                            <?php foreach ($enrolledCourses as $course): ?>
                                <li class="list-group-item">
                                    <strong><?= esc($course['title']) ?></strong>
                                    <p class="text-muted mb-0"><?= esc($course['description'] ?? '') ?></p>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-muted no-enrollment-msg">You are not enrolled in any courses yet.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header fw-bold">Available Courses</div>
                <div class="card-body">
                    <ul id="availableCourses" class="list-group">
                        <?php if (!empty($courses)): ?>
                            <?php foreach ($courses as $course): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center" id="course-<?= $course['id'] ?>">
                                    <div>
                                        <strong class="course-title"><?= esc($course['title']) ?></strong>
                                        <p class="text-muted mb-0"><?= esc($course['description'] ?? '') ?></p>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary enroll-btn" data-course-id="<?= $course['id'] ?>">Enroll</button>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-muted">No available courses right now.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Materials Section -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-file-earmark-text"></i> My Course Materials</span>
                    <a href="<?= site_url('materials') ?>" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($materials)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Course</th>
                                        <th>File Name</th>
                                        <th>Uploaded</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($materials, 0, 5) as $material): ?>
                                        <tr>
                                            <td><span class="badge bg-primary"><?= esc($material['course_name'] ?? 'N/A') ?></span></td>
                                            <td><i class="bi bi-file-earmark-pdf text-danger"></i> <?= esc($material['file_name']) ?></td>
                                            <td><?= date('M d, Y', strtotime($material['created_at'] ?? 'now')) ?></td>
                                            <td>
                                                <a href="<?= site_url('/materials/download/' . $material['id']) ?>" class="btn btn-sm btn-success">
                                                    <i class="bi bi-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No materials available yet. Enroll in courses to access materials.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- AJAX Enroll -->
    <script>
    $(document).ready(function() {
        $('.enroll-btn').click(function() {
            const button = $(this);
            const courseId = button.data('course-id');
            const courseTitle = button.closest('li').find('.course-title').text().trim();

            $.ajax({
                url: "<?= base_url('course/enroll') ?>",
                type: "POST",
                data: {
                    course_id: courseId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        $('#alertBox')
                            .removeClass('d-none alert-danger')
                            .addClass('alert alert-success')
                            .text(response.message);

                        // Disable button and move to enrolled list
                        button.prop('disabled', true).text('Enrolled');
                        button.closest('li').fadeOut(300, function() {
                            $(this).remove();
                            $('#enrolledCourses').append(
                                `<li class="list-group-item"><strong>${courseTitle}</strong></li>`
                            );
                        });

                        $('.no-enrollment-msg').remove();
                    } else {
                        $('#alertBox')
                            .removeClass('d-none alert-success')
                            .addClass('alert alert-danger')
                            .text(response.message);
                    }
                },
                error: function() {
                    $('#alertBox')
                        .removeClass('d-none alert-success')
                        .addClass('alert alert-danger')
                        .text('An error occurred. Please try again.');
                }
            });
        });
    });
    </script>

<?php endif; ?>

<?= $this->include('template/footer') ?>
