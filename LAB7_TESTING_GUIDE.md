# Laboratory 7 - Testing Guide

## Quick Start Testing Instructions

### Prerequisites
1. **XAMPP Running**
   - Apache: Running
   - MySQL: Running

2. **Database Setup**
   - Database created
   - All migrations run successfully (including materials table)

### Access the Application
- **URL:** http://localhost:8080 (CodeIgniter server)
- **Or:** http://localhost/ITE311-RUALES/public (XAMPP)

---

## Test Scenario 1: Admin/Instructor Upload Materials

### Step-by-Step:
1. **Login as Admin/Instructor**
   - Go to: http://localhost:8080/login
   - Use admin/instructor credentials

2. **Navigate to Dashboard**
   - You should see the dashboard with courses
   - For teachers: You'll see "Upload Material" button next to each course

3. **Upload a Material**
   - Click "Upload Material" button for any course
   - Select a PDF, DOC, or PPT file (max 10MB)
   - Click "Upload Material"
   - ✅ **Expected:** Success message appears

4. **Verify Upload**
   - Check dashboard - material should appear in "Recent Course Materials" table
   - Check file system: `writable/uploads/materials/` should contain the file
   - Check database: `materials` table should have a new record

5. **Test Download**
   - Click the download button
   - ✅ **Expected:** File downloads with original filename

6. **Test Delete**
   - Click the delete button
   - Confirm deletion
   - ✅ **Expected:** Material removed from database and file system

---

## Test Scenario 2: Student View and Download Materials

### Step-by-Step:
1. **Login as Student**
   - Go to: http://localhost:8080/login
   - Use student credentials

2. **Enroll in a Course** (if not already enrolled)
   - From dashboard, click "Enroll" on an available course
   - ✅ **Expected:** Success message, course moves to enrolled list

3. **View Materials**
   - Dashboard should show "My Course Materials" section
   - Materials from enrolled courses should be listed
   - Click "View All" to see full materials list

4. **Download Material**
   - Click download button on any material
   - ✅ **Expected:** File downloads successfully

5. **Test Access Control**
   - Try accessing: http://localhost:8080/materials/download/1
   - If not enrolled in that course's material
   - ✅ **Expected:** Error message or redirect

---

## Test Scenario 3: Security Testing

### 1. Unauthorized Access
- **Test:** Try accessing upload page as student
- **URL:** http://localhost:8080/admin/course/1/upload
- ✅ **Expected:** Redirect to dashboard with error message

### 2. File Validation
- **Test:** Try uploading invalid file type (.exe, .sh)
- ✅ **Expected:** Validation error

### 3. File Size Limit
- **Test:** Try uploading file > 10MB
- ✅ **Expected:** Validation error

### 4. Direct File Access
- **Test:** Try accessing file directly through browser
- **URL:** http://localhost:8080/writable/uploads/materials/filename.pdf
- ✅ **Expected:** 403 Forbidden or file not accessible

### 5. Enrollment Verification
- **Test:** Student tries to download material from non-enrolled course
- ✅ **Expected:** Error message or redirect

---

## Database Verification

### Check Materials Table:
```sql
SELECT * FROM materials;
```
**Expected Columns:**
- id
- course_id
- file_name
- file_path
- created_at

### Check Foreign Key:
```sql
SHOW CREATE TABLE materials;
```
**Expected:** Foreign key constraint on course_id

---

## File System Verification

### Check Upload Directory:
```
writable/
└── uploads/
    └── materials/
        ├── index.html (present)
        ├── .htaccess (present)
        └── [random_name].pdf (uploaded files)
```

---

## Common Issues and Solutions

### Issue 1: "File upload failed"
**Solution:** 
- Check directory permissions: `writable/uploads/materials/` should be writable
- Windows: Right-click folder → Properties → Security → Edit → Add write permission

### Issue 2: "Table 'materials' doesn't exist"
**Solution:**
```bash
php spark migrate
```

### Issue 3: "Call to undefined method"
**Solution:** Clear cache
```bash
php spark cache:clear
```

### Issue 4: 404 on routes
**Solution:** Check `app/Config/Routes.php` has materials routes

### Issue 5: Download returns empty file
**Solution:** Check file_path in database matches actual file location

---

## Expected Outcomes for Lab Submission

### ✅ Screenshots Needed:

1. **Database Schema**
   - phpMyAdmin → materials table → Structure tab
   - Should show all columns and foreign key

2. **Upload Form (Admin/Instructor)**
   - Dashboard with "Upload Material" button visible
   - Upload form with file input

3. **Materials List (Student)**
   - Dashboard showing "My Course Materials"
   - Table with downloadable materials

4. **File System**
   - Windows Explorer showing `writable\uploads\materials\` with uploaded files
   - At least one random-named file present

5. **GitHub Repository**
   - Repository overview with latest commit
   - Commit message: "Lab 7: File Upload and Management System"

---

## Routes to Test

| Route | Method | Access | Purpose |
|-------|--------|--------|---------|
| `/admin/course/{id}/upload` | GET/POST | Admin/Instructor | Upload form and handler |
| `/materials` | GET | All authenticated | List materials |
| `/materials/download/{id}` | GET | Enrolled students | Download file |
| `/materials/delete/{id}` | GET | Admin/Instructor | Delete material |
| `/dashboard` | GET | All authenticated | View dashboard |

---

## Test Data Suggestions

### Sample Files to Upload:
- ✅ syllabus.pdf (< 5MB)
- ✅ lecture_notes.docx (< 5MB)
- ✅ presentation.pptx (< 5MB)
- ❌ large_video.mp4 (> 10MB) - should fail
- ❌ malicious.exe - should fail

### Test Accounts Needed:
1. **Admin** - Full access
2. **Instructor** - Upload and manage materials
3. **Student A** - Enrolled in Course 1
4. **Student B** - Not enrolled in any course

---

## Success Criteria

All tests pass if:
- ✅ Materials table created with proper schema
- ✅ Files upload successfully for admin/instructor
- ✅ Files stored in secure directory with random names
- ✅ Students can view materials from enrolled courses only
- ✅ Download functionality works correctly
- ✅ Delete functionality works (admin/instructor only)
- ✅ Access control prevents unauthorized actions
- ✅ File validation rejects invalid files
- ✅ Dashboard displays materials for all user roles
- ✅ All routes work as expected

---

## Ready to Submit When:

1. ✅ All tests pass
2. ✅ All 5 screenshots captured
3. ✅ Code committed to GitHub
4. ✅ LAB7_DOCUMENTATION.md reviewed
5. ✅ No errors in browser console or server logs
