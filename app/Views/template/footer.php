<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= esc($title ?? 'App') ?></title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="base-url" content="<?= base_url() ?>">

<?php
    // Detect if we have a logged-in user/role
    $role   = session('user_role');          // will be null if not logged in
    $name   = session('user_name');          // adjust to your session key
    $isAuth = ! empty(session('logged_in')) || ! empty($role);
?>

<?php if ($isAuth): ?>
    <!-- ===== Dashboard styles ===== -->
    <style>
        body { background: #20f84eff; font-family: 'Inter', sans-serif; }
        .sidebar {
            height: 100vh; background: #ffffffff; color: #fff; padding: 20px;
            position: fixed; top: 0; left: 0; width: 240px;
        }
        .sidebar h4 { font-weight: 700; margin-bottom: 30px; }
        .sidebar a {
            display: block; color: #adb5bd; padding: 10px;
            border-radius: 8px; text-decoration: none; margin-bottom: 8px;
        }
        .sidebar a:hover, .sidebar a.active { background: #0d6efd; color: #fff; }
        .sidebar .notif-container { position: relative; }
        .sidebar .notif-container .dropdown-menu { top: 0; left: 100%; margin-left: 10px; }
        .content { margin-left: 260px; padding: 40px; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    </style>
<?php else: ?>
    <!-- ===== Auth (login/register) styles ===== -->
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #10f214 0%, #0dfda1 100%);
            background-size: cover;
        }
        .center-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .auth-card {
            background: #fff;
            border-radius: 15px;
            padding: 2rem;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
    </style>
<?php endif; ?>
</head>
<body>

<?php if ($isAuth): ?>
    <div class="sidebar">
        <?php if (in_array(session('user_role'), ['teacher', 'student'])): ?>
        <div class="notif-container dropdown">
            <a href="javascript:void(0)" id="notifButton" class="position-relative">
                🔔 Notifications
                <span id="notifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">0</span>
            </a>
            <div id="notifMenu" class="dropdown-menu p-2" style="min-width: 320px; max-width: 360px;">
                <div id="notifList"></div>
            </div>
        </div>
        <?php endif; ?>

        <a href="<?= site_url('/dashboard') ?>" class="<?= (current_url() == site_url('/dashboard')) ? 'active' : '' ?>">📊 Dashboard</a>
        <?php if (session('user_role') === 'admin'): ?>
        <a href="<?= site_url('/manage-users') ?>" class="<?= (current_url() == site_url('/manage-users')) ? 'active' : '' ?>">👥 Manage Users</a>
        <?php endif; ?>
        <?php if (in_array(session('user_role'), ['teacher', 'student'])): ?>
        <a href="<?= site_url('/change-password') ?>" class="<?= (current_url() == site_url('/change-password')) ? 'active' : '' ?>">🔒 Change Password</a>
        <?php endif; ?>
        <a href="<?= site_url('/courses') ?>" class="<?= (current_url() == site_url('/courses')) ? 'active' : '' ?>">📚 Courses</a>
        <a href="<?= site_url('/materials') ?>" class="<?= (current_url() == site_url('/materials')) ? 'active' : '' ?>">📄 Materials</a>
        <a href="<?= site_url('/auth/logout') ?>">🚪 Logout</a>
    </div>


<?php else: ?>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Inline notification system for reliability
<?php if ($isAuth && in_array(session('user_role'), ['teacher', 'student'])): ?>
(function() {
  var notificationEndpoint = '<?= site_url('notifications') ?>';
  var markReadEndpoint = '<?= site_url('notifications/mark_read') ?>';
  var csrfTokenName = '<?= csrf_token() ?>';
  var csrfTokenValue = '<?= csrf_hash() ?>';
  
  function updateBadge(count) {
    var badge = $('#notifBadge');
    if (!badge.length) return;
    var c = parseInt(count, 10) || 0;
    badge.text(c);
    if (c > 0) {
      badge.removeClass('d-none');
    } else {
      badge.addClass('d-none');
    }
  }
  
  function renderList(items) {
    var list = $('#notifList');
    list.empty();
    if (!items || items.length === 0) {
      list.append('<div class="text-muted px-2 py-1">No notifications</div>');
      return;
    }
    items.forEach(function (n) {
      var item = $(
        '<div class="alert alert-info mb-2" role="alert">' +
          '<div>' + $('<div>').text(n.message || '').html() + '</div>' +
          '<div class="small text-muted">' + $('<div>').text(n.created_at || '').html() + '</div>' +
          '<button type="button" class="btn btn-sm btn-outline-secondary mt-2 mark-read" data-id="' + n.id + '">Mark as Read</button>' +
        '</div>'
      );
      list.append(item);
    });
  }
  
  function fetchNotifications() {
    $.get(notificationEndpoint, function (resp) {
      if (resp && resp.status === 'success') {
        updateBadge(resp.count);
        renderList(resp.items);
        if (resp.csrfHash) {
          csrfTokenValue = resp.csrfHash;
        }
      }
    });
  }
  
  function markAsRead(id) {
    var data = {};
    data[csrfTokenName] = csrfTokenValue;
    $.post(markReadEndpoint + '/' + id, data, function (resp) {
      if (resp && resp.status === 'success') {
        updateBadge(resp.count);
        $('#notifList .mark-read[data-id="' + id + '"]').closest('.alert').remove();
        if ($('#notifList').children().length === 0) {
          $('#notifList').append('<div class="text-muted px-2 py-1">No notifications</div>');
        }
        if (resp.csrfHash) {
          csrfTokenValue = resp.csrfHash;
        }
      }
    });
  }
  
  $(document).ready(function () {
    // Toggle dropdown
    $('#notifButton').on('click', function () {
      $('#notifMenu').toggleClass('show');
    });
    
    // Hide when clicking outside
    $(document).on('click', function (e) {
      if (!$(e.target).closest('#notifButton, #notifMenu').length) {
        $('#notifMenu').removeClass('show');
      }
    });
    
    // Mark as read handler
    $(document).on('click', '.mark-read', function () {
      var id = $(this).data('id');
      if (id) markAsRead(id);
    });
    
    // Initial fetch and interval
    fetchNotifications();
    setInterval(fetchNotifications, 60000);
    
    // Expose refresh function
    window.Notif = window.Notif || {};
    window.Notif.refresh = fetchNotifications;
  });
})();
<?php endif; ?>
</script>
