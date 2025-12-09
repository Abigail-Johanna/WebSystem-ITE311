<?= $this->include('template/header') ?>

<div class="content">
    <h2 class="mb-4">Manage Users</h2>

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

    <!-- Create New User Section -->
    <div class="card mb-4">
        <div class="card-header fw-bold">
            <i class="bi bi-person-plus"></i> Create New User
        </div>
        <div class="card-body">
            <form method="POST" action="<?= site_url('manage-users/create') ?>" id="createUserForm">
                <?= csrf_field() ?>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
                        </select>
                        <small class="text-muted">New users can be Admin, Teacher, or Student</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Auto-Generated Password</label>
                        <div class="input-group">
                            <input type="text" id="generatedPassword" class="form-control" readonly>
                            <button type="button" class="btn btn-outline-secondary" onclick="copyPassword()">Copy</button>
                        </div>
                        <small class="text-muted">Password is automatically generated. Users should change it after first login.</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="text" id="confirmPassword" class="form-control" readonly>
                        <small class="text-muted">Auto-filled for confirmation</small>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create User</button>
            </form>
        </div>
    </div>

    <!-- All Users Section -->
    <div class="card">
        <div class="card-header fw-bold">
            <i class="bi bi-people"></i> All Users
        </div>
        <div class="card-body">
            <?php if (!empty($users)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Name</th>
                                <th width="20%">Email</th>
                                <th width="15%">Current Role</th>
                                <th width="10%">Status</th>
                                <th width="15%">Change Role</th>
                                <th width="20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $currentUserId = session()->get('user_id');
                            foreach ($users as $index => $user): 
                                $isFirstAdmin = ($user['id'] == 1 && $user['role'] === 'admin');
                                $isAdmin = ($user['role'] === 'admin');
                                $isCurrentUser = ($user['id'] == $currentUserId);
                                $status = $user['status'] ?? 'active';
                            ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><strong><?= esc($user['name']) ?></strong></td>
                                    <td><?= esc($user['email']) ?></td>
                                    <td>
                                        <?php 
                                        $roleDisplay = ucfirst($user['role']);
                                        echo $roleDisplay;
                                        if ($isAdmin): 
                                        ?>
                                            <span class="badge bg-danger ms-1">Protected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($status === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php elseif ($status === 'inactive'): ?>
                                            <span class="badge bg-warning text-dark">Inactive</span>
                                        <?php elseif ($status === 'deleted'): ?>
                                            <span class="badge bg-danger">Deleted</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= ucfirst($status) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($isFirstAdmin): ?>
                                            <span class="badge bg-danger">Admin (Protected)</span>
                                        <?php elseif ($isAdmin): ?>
                                            <span class="badge bg-danger">Admin (Protected)</span>
                                        <?php else: ?>
                                            <form method="POST" action="<?= site_url('manage-users/change-role') ?>" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                <select name="role" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto; display: inline-block;">
                                                    <option value="teacher" <?= $user['role'] === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                                                    <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                                                </select>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($isFirstAdmin): ?>
                                            <span class="text-muted">-</span>
                                        <?php else: ?>
                                            <?php if ($status === 'deleted'): ?>
                                                <a href="<?= site_url('manage-users/restore/' . $user['id']) ?>" 
                                                   class="btn btn-sm btn-success" 
                                                   onclick="return confirm('Are you sure you want to restore this user? The user will be set to active status.')"
                                                   title="Restore">
                                                    Restore
                                                </a>
                                            <?php elseif ($status === 'active'): ?>
                                                <a href="<?= site_url('manage-users/deactivate/' . $user['id']) ?>" 
                                                   class="btn btn-sm btn-warning" 
                                                   onclick="return confirm('Are you sure you want to deactivate this user?')"
                                                   title="Deactivate">
                                                    Deactivate
                                                </a>
                                                <a href="<?= site_url('manage-users/delete/' . $user['id']) ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this user? The user will be removed from admin view but data will be preserved in the database.')"
                                                   title="Delete">
                                                    Delete
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= site_url('manage-users/activate/' . $user['id']) ?>" 
                                                   class="btn btn-sm btn-success" 
                                                   title="Activate">
                                                    Activate
                                                </a>
                                                <a href="<?= site_url('manage-users/delete/' . $user['id']) ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this user? The user will be removed from admin view but data will be preserved in the database.')"
                                                   title="Delete">
                                                    Delete
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted mb-0">No users found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script>
// Generate role-based password
function generatePassword(role) {
    switch(role) {
        case 'admin':
            return 'admin123';
        case 'teacher':
            return 'teacher123';
        case 'student':
            return 'student123';
        default:
            return '';
    }
}

// Update password when role changes
function updatePassword() {
    const roleSelect = document.querySelector('select[name="role"]');
    const passwordField = document.getElementById('generatedPassword');
    const confirmPasswordField = document.getElementById('confirmPassword');
    
    if (roleSelect && passwordField) {
        const selectedRole = roleSelect.value;
        const password = generatePassword(selectedRole);
        passwordField.value = password;
        confirmPasswordField.value = password;
    }
}

// Set password on page load
document.addEventListener('DOMContentLoaded', function() {
    updatePassword();
    
    // Update password when role dropdown changes
    const roleSelect = document.querySelector('select[name="role"]');
    if (roleSelect) {
        roleSelect.addEventListener('change', updatePassword);
    }
});

// Copy password to clipboard
function copyPassword() {
    const passwordField = document.getElementById('generatedPassword');
    passwordField.select();
    document.execCommand('copy');
    alert('Password copied to clipboard!');
}
</script>

<?= $this->include('template/footer') ?>
