dashboard

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
    </div>


<?php elseif ($user_role === 'teacher'): ?>

    <!-- removed the first My Courses summary card -->

    <div class="card mt-4">
        <div class="card-header fw-bold">My Courses</div>
        <div class="card-body">
            <?php if (!empty($courses)): ?>
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Course Name</th>
                            <th>Students Enrolled</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $index => $c): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($c['name'] ?? $c['course_name']) ?></td>
                                <td><?= esc($c['enrolled_students'] ?? 0) ?></td>
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


<?php elseif ($user_role === 'student'): ?>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-center p-3">
                <h5>My Enrolled Courses</h5>
                <h3><?= esc($stats['my_courses'] ?? 0) ?></h3>
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

    <div id="alert"></div>

    <div class="row">
        <!-- Enrolled Courses -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header fw-bold">My Enrolled Courses</div>
                <div class="card-body">
                    <ul id="enrolledList" class="list-group mb-3">
                        <?php if (!empty($enrolledCourses)): ?>
                            <?php foreach ($enrolledCourses as $course): ?>
                                <li class="list-group-item">
                                    <strong><?= esc($course['title']) ?></strong>
                                    <p class="text-muted mb-0"><?= esc($course['description']) ?></p>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-muted">You are not enrolled in any courses yet.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Available Courses -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header fw-bold">Available Courses</div>
                <div class="card-body">
                    <ul id="availableList" class="list-group">
                        <?php if (!empty($availableCourses)): ?>
                            <?php foreach ($availableCourses as $course): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= esc($course['title']) ?></strong>
                                        <p class="text-muted mb-0"><?= esc($course['description']) ?></p>
                                    </div>
                                    <button class="btn btn-sm btn-primary enroll-btn" data-course-id="<?= $course['id'] ?>">Enroll</button>
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

    <!-- AJAX Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(function() {
        $('.enroll-btn').on('click', function() {
            var btn = $(this);
            var id = btn.data('course-id');

            $.post('<?= site_url("course/enroll") ?>', {course_id: id}, function(res) {
                if (res.success) {
                    $('#alert').html('<div class="alert alert-success">' + res.message + '</div>');
                    btn.prop('disabled', true).text('Enrolled');
                    $('#enrolledList').append('<li class="list-group-item"><strong>' + res.course.title + '</strong></li>');
                } else {
                    $('#alert').html('<div class="alert alert-danger">' + res.message + '</div>');
                }
            }, 'json');
        });
    });
    </script>

<?php endif; ?>


<?= $this->include('template/footer') ?>
