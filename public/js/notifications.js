'use strict';

(function () {
  var BASE = (function () {
    var m = document.querySelector('meta[name="base-url"]');
    var b = m ? m.getAttribute('content') : window.location.origin + '/';
    if (b && b.slice(-1) !== '/') b += '/';
    return b;
  })();
  function getTokenName() {
    return document.querySelector('meta[name="csrf-token-name"]').getAttribute('content');
  }
  function getToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  }
  function setToken(name, value) {
    if (name) {
      var nameMeta = document.querySelector('meta[name="csrf-token-name"]');
      if (nameMeta) nameMeta.setAttribute('content', name);
    }
    if (value) {
      var valueMeta = document.querySelector('meta[name="csrf-token"]');
      if (valueMeta) valueMeta.setAttribute('content', value);
    }
  }

  function isVisible(el) {
    if (!el) return false;
    var rect = el.getBoundingClientRect();
    var style = window.getComputedStyle(el);
    return (
      rect.width > 0 &&
      rect.height > 0 &&
      style.visibility !== 'hidden' &&
      style.display !== 'none'
    );
  }

  function updateCsrfFromData(data) {
    if (data && data.csrfToken && data.csrfHash) {
      setToken(data.csrfToken, data.csrfHash);
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
      var id = n.id;
      var msg = n.message || '';
      var created = n.created_at || '';
      var item = $(
        '<div class="alert alert-info mb-2" role="alert">' +
          '<div>' + $('<div>').text(msg).html() + '</div>' +
          '<div class="small text-muted">' + $('<div>').text(created).html() + '</div>' +
          '<button type="button" class="btn btn-sm btn-outline-secondary mt-2 mark-read" data-id="' + id + '">Mark as Read</button>' +
        '</div>'
      );
      list.append(item);
    });
  }

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

  function fetchNotifications() {
    $.get(BASE + 'notifications', function (resp) {
      if (resp && resp.status === 'success') {
        updateBadge(resp.count);
        renderList(resp.items);
      }
      updateCsrfFromData(resp);
    });
  }

  function markAsRead(id) {
    var data = {};
    data[getTokenName()] = getToken();
    $.post(BASE + 'notifications/mark_read/' + id, data, function (resp) {
      if (resp && resp.status === 'success') {
        updateBadge(resp.count);
        // remove the item from list
        $('#notifList .mark-read[data-id="' + id + '"]').closest('.alert').remove();
        // If list becomes empty
        if ($('#notifList').children().length === 0) {
          $('#notifList').append('<div class="text-muted px-2 py-1">No notifications</div>');
        }
      }
      updateCsrfFromData(resp);
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

    // Delegated handler for mark as read
    $(document).on('click', '.mark-read', function () {
      var id = $(this).data('id');
      if (id) markAsRead(id);
    });

    // Initial fetch and optional interval
    fetchNotifications();
    setInterval(fetchNotifications, 60000);
  });

  // Expose a small global API so other scripts can trigger refreshes
  window.Notif = window.Notif || {};
  window.Notif.refresh = fetchNotifications;
  window.Notif.markAsRead = markAsRead;
})();
