<?= $this->include('template/header') ?>

<?php if ($user_role === 'admin'): ?>

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

<?php elseif ($user_role === 'teacher'): ?>

    <div class="card mt-4">
        <div class="card-header fw-bold">My Courses</div>
        <div class="card-body">
            <?php if (!empty($courses)): ?>
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Course Title</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $index => $c): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($c['title']) ?></td>
                                <td><?= esc($c['description'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted mb-0">No courses found.</p>
            <?php endif; ?>
        </div>
    </div>

<?php elseif ($user_role === 'student'): ?>

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

    <!-- ✅ CSRF Token -->
    <div id="csrfToken" style="display:none;">
        <?= csrf_field() ?>
    </div>

    <!-- ✅ Alert Box -->
    <div id="alertBox" class="alert d-none mt-3"></div>

    <div class="row">
        <!-- ✅ Enrolled Courses -->
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

        <!-- ✅ Available Courses -->
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

    <!-- ✅ jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- ✅ AJAX Enroll -->
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
