# Laboratory Exercise 7: File Upload and Management System

## Overview
This laboratory exercise implements a complete file upload and management system for the Learning Management System (LMS). It allows instructors to upload course materials and students to download them based on their course enrollment.

## Completed Steps

### Step 1: Database Migration for Materials Table ✅
**File:** `app/Database/Migrations/20251023162233_CreateMaterialsTable.php`

Created a migration with the following schema:
- `id` - Primary Key (Auto-increment)
- `course_id` - Foreign Key referencing courses table
- `file_name` - Original filename (VARCHAR 255)
- `file_path` - Path to uploaded file (VARCHAR 255)
- `created_at` - Upload timestamp (DATETIME)

**Migration Command:**
```bash
php spark make:migration CreateMaterialsTable
php spark migrate
```

### Step 2: Material Model ✅
**File:** `app/Models/MaterialModel.php`

Implemented methods:
- `insertMaterial($data)` - Insert new material record
- `getMaterialsByCourse($course_id)` - Get all materials for a course
- `getMaterialById($id)` - Get specific material by ID
- `getMaterialsForEnrolledCourses($user_id)` - Get materials for student's enrolled courses

### Step 3: Materials Controller ✅
**File:** `app/Controllers/Materials.php`

Implemented methods with full validation and security:

#### `upload($course_id)`
- Authentication check (admin/instructor only)
- File validation (max 10MB, allowed extensions: pdf, doc, docx, ppt, pptx, txt, zip)
- Secure file upload to `writable/uploads/materials/`
- Database record creation

#### `download($material_id)`
- Authentication check
- Enrollment verification (students must be enrolled)
- File existence check
- Secure file download with original filename

#### `delete($material_id)`
- Authentication check (admin/instructor only)
- File and database record deletion
- Error handling for missing files

#### `index()`
- Display all materials for enrolled courses
- Student dashboard view

### Step 4: File Upload View ✅
**File:** `app/Views/materials/upload.php`

Features:
- Bootstrap 5 styled form
- File input with validation
- Success/error message display
- Upload guidelines
- CSRF protection
- Bootstrap Icons integration

### Step 5: Materials List View ✅
**File:** `app/Views/materials/list.php`

Features:
- Responsive table layout
- Course badge display
- File icons
- Download buttons
- Delete buttons (admin/instructor only)
- Empty state message

### Step 6: Dashboard Integration ✅
**Files Modified:**
- `app/Controllers/Auth.php` - Added materials data retrieval
- `app/Views/auth/dashboard.php` - Added materials display for all user roles

**Admin Dashboard:**
- View all recent materials
- Download and delete capabilities
- Materials overview table

**Teacher Dashboard:**
- Upload material button for each course
- View own course materials
- Manage materials (download/delete)

**Student Dashboard:**
- View materials from enrolled courses
- Download functionality
- Link to full materials page

### Step 7: Upload Directory Structure ✅
**Created:**
- `writable/uploads/materials/` - Storage directory
- `writable/uploads/materials/index.html` - Prevent directory listing
- `writable/uploads/materials/.htaccess` - Additional security

### Step 8: Routes Configuration ✅
**File:** `app/Config/Routes.php`

Added routes:
```php
$routes->get('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->get('/materials/delete/(:num)', 'Materials::delete/$1');
$routes->get('/materials/download/(:num)', 'Materials::download/$1');
$routes->get('/materials', 'Materials::index');
```

## Security Features

1. **Authentication & Authorization**
   - Login verification for all actions
   - Role-based access control (admin, instructor, student)
   - Enrollment verification for downloads

2. **File Upload Security**
   - File type validation
   - File size limitation (10MB max)
   - Random filename generation
   - Storage outside public directory

3. **Directory Protection**
   - .htaccess prevents direct file access
   - index.html prevents directory listing
   - Files served through controller only

4. **CSRF Protection**
   - All forms include CSRF tokens
   - CodeIgniter's built-in CSRF protection enabled

## Database Schema

### materials Table
```sql
CREATE TABLE materials (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id INT(11) UNSIGNED NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);
```

## File Structure
```
app/
├── Controllers/
│   ├── Materials.php (Enhanced with full validation)
│   └── Auth.php (Updated with materials integration)
├── Models/
│   └── MaterialModel.php
├── Views/
│   ├── materials/
│   │   ├── upload.php
│   │   └── list.php
│   └── auth/
│       └── dashboard.php (Updated)
├── Database/
│   └── Migrations/
│       └── 20251023162233_CreateMaterialsTable.php
└── Config/
    └── Routes.php (Updated)

writable/
└── uploads/
    └── materials/
        ├── index.html
        ├── .htaccess
        └── [uploaded files]
```

## Testing Checklist

### Admin/Instructor Testing:
- [ ] Login as admin/instructor
- [ ] Navigate to dashboard
- [ ] Click "Upload Material" for a course
- [ ] Upload a PDF file
- [ ] Verify file appears in materials list
- [ ] Download the file
- [ ] Delete the file
- [ ] Verify file is removed from server

### Student Testing:
- [ ] Login as student
- [ ] Enroll in a course
- [ ] View materials in dashboard
- [ ] Click "View All" to see full materials list
- [ ] Download a material
- [ ] Try accessing download without enrollment (should fail)

### Security Testing:
- [ ] Try accessing upload page as student (should redirect)
- [ ] Try uploading invalid file type (should fail)
- [ ] Try uploading file over 10MB (should fail)
- [ ] Try directly accessing file URL (should be blocked)
- [ ] Try accessing materials directory (should show 403)

## How to Test the Application

1. **Start XAMPP**
   - Start Apache and MySQL

2. **Access the Application**
   - URL: `http://localhost/ITE311-RUALES/public`

3. **Create Test Accounts**
   - Register as instructor
   - Register as student
   - (Or use existing accounts)

4. **Test Upload Flow**
   - Login as instructor
   - Go to dashboard
   - Upload material for a course
   - Verify success message

5. **Test Download Flow**
   - Login as student
   - Enroll in course (if not enrolled)
   - View materials
   - Download material
   - Verify file downloads correctly

## Key Features Implemented

✅ Database migration for materials table
✅ Complete CRUD operations for materials
✅ File upload with validation
✅ Secure file download with enrollment check
✅ Role-based access control
✅ Bootstrap-styled user interface
✅ Dashboard integration for all user roles
✅ File management (view, download, delete)
✅ Security measures (CSRF, file validation, directory protection)
✅ Error handling and user feedback

## Notes
- Maximum file size: 10MB
- Allowed file types: PDF, DOC, DOCX, PPT, PPTX, TXT, ZIP
- Files are stored in `writable/uploads/materials/` with random names
- Original filenames are preserved in database and used for download
- Students can only download materials from enrolled courses
- Admins and instructors can manage all materials

## GitHub Submission
After testing, commit and push all changes:
```bash
git add .
git commit -m "Lab 7: Implemented File Upload and Management System"
git push origin main
```

## Screenshots Required for Submission
1. Screenshot of materials table schema (phpMyAdmin)
2. Screenshot of file upload form (admin/instructor view)
3. Screenshot of materials list (student view)
4. Screenshot of upload directory with files
5. Screenshot of GitHub repository with latest commit
