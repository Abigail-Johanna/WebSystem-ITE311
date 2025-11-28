<?= $this->include('template/header') ?>

<!-- Custom styles for search interface -->
<style>
    .loading-spinner {
        display: none;
        text-align: center;
        padding: 40px;
    }
    .fade-in {
        animation: fadeIn 0.3s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .ajax-status {
        display: none;
        margin-top: 10px;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 13px;
    }
    .ajax-status.success {
        background: rgba(40, 167, 69, 0.2);
        color: #155724;
        display: inline-block;
    }
    .ajax-status.error {
        background: rgba(220, 53, 69, 0.2);
        color: #721c24;
        display: inline-block;
    }
    .highlight {
        background-color: #fff3cd;
        padding: 2px 4px;
        border-radius: 3px;
    }
    .search-results-info {
        background: #e8f4fd;
        border-left: 4px solid #0d6efd;
        padding: 12px 20px;
        border-radius: 0 8px 8px 0;
        margin-bottom: 20px;
    }
</style>

<div class="container-fluid">
    <!-- ============================================ -->
    <!-- Search Interface (Dashboard Style Card) -->
    <!-- ============================================ -->
    <div class="card mb-4">
        <div class="card-header fw-bold">
            <i class="bi bi-search"></i> Search Courses
        </div>
        <div class="card-body">
            <!-- Search Form -->
            <form id="searchForm" action="<?= site_url('courses/search') ?>" method="GET">
                <div class="input-group">
                    <input type="text" 
                           class="form-control" 
                           id="searchInput" 
                           name="q" 
                           placeholder="Search by course title or description..." 
                           value="<?= esc($search_term ?? '') ?>"
                           autocomplete="off">
                    <button type="submit" class="btn btn-primary" id="searchBtn">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
            
            <small class="text-muted mt-2 d-block">
                <i class="bi bi-info-circle"></i> 
                Type to filter instantly • Click Search or press Enter for AJAX server search
            </small>
            
            <!-- AJAX Status Indicator -->
            <div class="ajax-status" id="ajaxStatus">
                <i class="bi bi-check-circle"></i> <span id="ajaxMessage"></span>
            </div>
        </div>
    </div>

    <!-- Search Results Info (shown after AJAX search) -->
    <div class="search-results-info fade-in d-none" id="searchResultsInfo">
        <i class="bi bi-search"></i> 
        Showing results for: <strong id="searchTermDisplay"></strong>
        <span class="badge bg-primary ms-2" id="resultCount">0 result(s)</span>
        <button type="button" class="btn btn-sm btn-outline-secondary ms-3" id="clearSearchBtn">
            <i class="bi bi-x-circle"></i> Clear Search
        </button>
    </div>

    <!-- Loading Spinner -->
    <div class="loading-spinner" id="loadingSpinner">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Searching courses...</p>
    </div>

    <!-- Hidden CSRF Token for AJAX -->
    <div id="csrfToken" style="display:none;">
        <?= csrf_field() ?>
    </div>

    <!-- Alert Box for Messages -->
    <div id="alertBox" class="alert d-none mt-3"></div>

    <!-- ============================================ -->
    <!-- Courses List Section (Dashboard Style) -->
    <!-- ============================================ -->
    <div class="card">
        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
            <span><i class="bi bi-book"></i> Available Courses</span>
            <span class="badge bg-primary" id="courseCount"><?= count($courses ?? []) ?> course(s)</span>
        </div>
        <div class="card-body">
            <div id="courseResults">
                <?php if (!empty($courses)): ?>
                    <ul id="courseList" class="list-group">
                        <?php foreach ($courses as $course): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center course-item" 
                                id="course-<?= esc($course['id']) ?>"
                                data-title="<?= esc(strtolower($course['title'])) ?>"
                                data-description="<?= esc(strtolower($course['description'] ?? '')) ?>"
                                data-course-id="<?= esc($course['id']) ?>"
                                data-enrolled="<?= !empty($course['is_enrolled']) ? 'true' : 'false' ?>">
                                <div>
                                    <strong class="course-title"><?= esc($course['title']) ?></strong>
                                    <p class="text-muted mb-0 course-description"><?= esc($course['description'] ?? 'No description available') ?></p>
                                </div>
                                <?php if (!empty($course['is_enrolled'])): ?>
                                    <button type="button" class="btn btn-sm btn-success" disabled>
                                        <i class="bi bi-check-circle"></i> Enrolled
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-primary enroll-btn" data-course-id="<?= esc($course['id']) ?>">
                                        Enroll
                                    </button>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted mb-0" id="noResults">No available courses right now.</p>
                <?php endif; ?>
            </div>

            <!-- No Results Message (shown during filtering) -->
            <div class="text-center py-4 d-none" id="noFilterResults">
                <i class="bi bi-emoji-frown" style="font-size: 48px; color: #dee2e6;"></i>
                <h5 class="mt-3">No Matching Courses</h5>
                <p class="text-muted">No courses match your search. Try a different term.</p>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- ============================================ -->
<!-- jQuery Search & Enrollment Functionality -->
<!-- ============================================ -->
<script>
$(document).ready(function() {
    // =============================================
    // Variables
    // =============================================
    const searchInput = $('#searchInput');
    const searchForm = $('#searchForm');
    const courseList = $('#courseList');
    const courseCount = $('#courseCount');
    const noFilterResults = $('#noFilterResults');
    const loadingSpinner = $('#loadingSpinner');
    const ajaxStatus = $('#ajaxStatus');
    const ajaxMessage = $('#ajaxMessage');
    const searchResultsInfo = $('#searchResultsInfo');
    const searchTermDisplay = $('#searchTermDisplay');
    const resultCount = $('#resultCount');
    
    // Store original courses HTML for reset after AJAX
    const originalCoursesHtml = courseList.html();
    const originalCount = $('.course-item').length;
    let isAjaxSearchActive = false;
    
    // CSRF Token for AJAX requests
    const csrfTokenName = $('meta[name="csrf-token-name"]').attr('content') || '<?= csrf_token() ?>';
    let csrfTokenValue = $('meta[name="csrf-token"]').attr('content') || '<?= csrf_hash() ?>';
    const baseUrl = $('meta[name="base-url"]').attr('content') || '<?= site_url() ?>';

    // =============================================
    // CLIENT-SIDE FILTERING (Instant as you type)
    // =============================================
    searchInput.on('keyup', function(e) {
        if (e.keyCode === 13) {
            return;
        }
        performClientFilter();
    });
    
    function performClientFilter() {
        const searchTerm = searchInput.val().toLowerCase().trim();
        let visibleCount = 0;
        
        ajaxStatus.removeClass('success error').hide();
        
        if (searchTerm === '') {
            if (isAjaxSearchActive) {
                courseList.html(originalCoursesHtml);
                isAjaxSearchActive = false;
                // Rebind enroll buttons after restoring HTML
                bindEnrollButtons();
            }
            
            const courseItems = $('.course-item');
            courseItems.removeClass('d-none').addClass('fade-in');
            visibleCount = courseItems.length;
            noFilterResults.addClass('d-none');
            searchResultsInfo.addClass('d-none');
            
            courseItems.find('.highlight').contents().unwrap();
        } else {
            const courseItems = $('.course-item');
            courseItems.each(function() {
                const title = $(this).data('title') || '';
                const description = $(this).data('description') || '';
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    $(this).removeClass('d-none').addClass('fade-in');
                    visibleCount++;
                    highlightText($(this), searchTerm);
                } else {
                    $(this).addClass('d-none').removeClass('fade-in');
                }
            });
            
            if (visibleCount === 0) {
                noFilterResults.removeClass('d-none');
            } else {
                noFilterResults.addClass('d-none');
            }
        }
        
        courseCount.text(visibleCount + ' course(s)');
    }
    
    function highlightText(element, term) {
        element.find('.highlight').contents().unwrap();
        
        if (term.length > 0) {
            const titleEl = element.find('.course-title');
            const descEl = element.find('.course-description');
            const regex = new RegExp('(' + escapeRegex(term) + ')', 'gi');
            
            titleEl.html(titleEl.text().replace(regex, '<span class="highlight">$1</span>'));
            descEl.html(descEl.text().replace(regex, '<span class="highlight">$1</span>'));
        }
    }
    
    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // =============================================
    // SERVER-SIDE AJAX SEARCH (on form submit)
    // =============================================
    searchForm.on('submit', function(e) {
        e.preventDefault();
        performAjaxSearch();
    });
    
    function performAjaxSearch() {
        const searchTerm = searchInput.val().trim();
        
        loadingSpinner.show();
        courseList.addClass('opacity-50');
        ajaxStatus.removeClass('success error').hide();
        
        console.log('🔍 AJAX Search Started - Searching for:', searchTerm);
        
        $.ajax({
            url: baseUrl + 'courses/search',
            type: 'GET',
            data: { q: searchTerm },
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(response) {
                loadingSpinner.hide();
                courseList.removeClass('opacity-50');
                
                console.log('✅ AJAX SUCCESS!', response);
                
                ajaxStatus.removeClass('error').addClass('success').show();
                ajaxMessage.html('AJAX Success! Found ' + response.count + ' course(s)');
                
                if (response.status === 'success') {
                    isAjaxSearchActive = true;
                    renderCourseResults(response.results, response.search_term);
                    courseCount.text(response.count + ' course(s)');
                    
                    if (searchTerm !== '') {
                        searchResultsInfo.removeClass('d-none');
                        searchTermDisplay.text('"' + response.search_term + '"');
                        resultCount.text(response.count + ' result(s)');
                    } else {
                        searchResultsInfo.addClass('d-none');
                    }
                    
                    if (response.csrfHash) {
                        csrfTokenValue = response.csrfHash;
                    }
                } else {
                    showAlert('error', response.message || 'Search failed');
                }
                
                setTimeout(function() {
                    ajaxStatus.fadeOut();
                }, 5000);
            },
            error: function(xhr, status, error) {
                loadingSpinner.hide();
                courseList.removeClass('opacity-50');
                
                console.log('❌ AJAX ERROR!', error);
                
                ajaxStatus.removeClass('success').addClass('error').show();
                ajaxMessage.html('AJAX Error: ' + error);
                
                showAlert('error', 'An error occurred while searching. Please try again.');
            }
        });
    }
    
    // Render course results from server response (Dashboard Style)
    function renderCourseResults(courses, searchTerm) {
        courseList.empty();
        noFilterResults.addClass('d-none');
        
        if (!courses || courses.length === 0) {
            noFilterResults.removeClass('d-none');
            courseList.html(`
                <li class="list-group-item text-center text-muted py-4">
                    <i class="bi bi-search" style="font-size: 32px;"></i>
                    <p class="mb-0 mt-2">No courses match "${escapeHtml(searchTerm || '')}"</p>
                </li>
            `);
            return;
        }
        
        courses.forEach(function(course) {
            const isEnrolled = course.is_enrolled === true || course.is_enrolled === 1 || course.is_enrolled === '1';
            const buttonHtml = isEnrolled
                ? `<button type="button" class="btn btn-sm btn-success" disabled>
                       <i class="bi bi-check-circle"></i> Enrolled
                   </button>`
                : `<button type="button" class="btn btn-sm btn-primary enroll-btn" data-course-id="${course.id}">
                       Enroll
                   </button>`;
            
            const courseHtml = `
                <li class="list-group-item d-flex justify-content-between align-items-center course-item fade-in" 
                    id="course-${course.id}"
                    data-title="${escapeHtml((course.title || '').toLowerCase())}"
                    data-description="${escapeHtml((course.description || '').toLowerCase())}"
                    data-course-id="${course.id}"
                    data-enrolled="${isEnrolled ? 'true' : 'false'}">
                    <div>
                        <strong class="course-title">${escapeHtml(course.title)}</strong>
                        <p class="text-muted mb-0 course-description">${escapeHtml(course.description || 'No description available')}</p>
                    </div>
                    ${buttonHtml}
                </li>
            `;
            courseList.append(courseHtml);
        });
        
        // Rebind enroll buttons for new elements
        bindEnrollButtons();
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        $('#alertBox')
            .removeClass('d-none alert-success alert-danger')
            .addClass('alert ' + alertClass)
            .html('<i class="bi bi-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + message);
        
        setTimeout(function() {
            $('#alertBox').addClass('d-none');
        }, 5000);
    }

    // =============================================
    // CLEAR SEARCH BUTTON
    // =============================================
    $('#clearSearchBtn').on('click', function() {
        window.location.href = '<?= site_url('courses') ?>';
    });

    // =============================================
    // ENROLLMENT FUNCTIONALITY
    // =============================================
    function bindEnrollButtons() {
        $('.enroll-btn').off('click').on('click', function() {
            const button = $(this);
            const courseId = button.data('course-id');
            const courseTitle = button.closest('li').find('.course-title').text().trim();
            
            if (!courseId) {
                showAlert('error', 'Invalid course selected');
                return;
            }
            
            button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
            
            const postData = { course_id: courseId };
            postData[csrfTokenName] = csrfTokenValue;
            
            console.log('📝 Enrolling in course:', courseId);
            
            $.ajax({
                url: baseUrl + 'course/enroll',
                type: 'POST',
                data: postData,
                dataType: 'json',
                success: function(response) {
                    console.log('✅ Enrollment Response:', response);
                    
                    if (response.status === 'success') {
                        button.removeClass('btn-primary').addClass('btn-success')
                              .html('<i class="bi bi-check-circle"></i> Enrolled');
                        showAlert('success', response.message);
                        
                        // Update course count
                        const currentCount = parseInt($('.course-item:visible').length);
                        
                        if (typeof window.Notif !== 'undefined' && window.Notif.refresh) {
                            window.Notif.refresh();
                        }
                    } else {
                        button.prop('disabled', false).html('Enroll');
                        showAlert('error', response.message);
                    }
                    
                    if (response.csrfHash) {
                        csrfTokenValue = response.csrfHash;
                    }
                },
                error: function(xhr, status, error) {
                    console.log('❌ Enrollment Error:', error);
                    button.prop('disabled', false).html('Enroll');
                    showAlert('error', 'An error occurred. Please try again.');
                }
            });
        });
    }
    
    // Initial binding
    bindEnrollButtons();

    // =============================================
    // Initialize
    // =============================================
    searchInput.focus();
    console.log('🚀 Course Search initialized. Type to filter, press Enter/Search for AJAX.');
});
</script>

<?= $this->include('template/footer') ?>
