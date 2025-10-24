# 🔧 Upload System Debug & Fix Guide

## ✅ What I Fixed

### 1. **Enhanced Materials Controller with Logging**
- Added detailed logging at every step
- Fixed session flash messages using `setFlashdata()` method
- Better error handling and validation
- Database insert verification

### 2. **Created Diagnostic Script**
- File: `test_upload.php`
- Checks upload directory
- Verifies database connection
- Shows table structure
- Displays PHP upload settings

---

## 🚀 IMMEDIATE TESTING STEPS

### **Step 1: Run Diagnostic Script**
1. Open browser
2. Go to: **http://localhost:8080/test_upload.php**
3. Check all items show "YES" or "OK"
4. Take screenshot for reference

### **Step 2: Clear Browser Cache**
```
Press: Ctrl + Shift + Delete
Clear: Cookies and Cache
Time range: Last hour
```

### **Step 3: Test Upload with Logging**
1. Go to: **http://localhost:8080/dashboard**
2. Click "Upload" on any course
3. Select a **small PDF file** (< 2MB)
4. Click "Upload Material"

### **Step 4: Check Logs Immediately**
```powershell
# In PowerShell, run:
cd C:\xampp\htdocs\ITE311-RUALES
Get-Content "writable\logs\log-2025-10-23.log" -Tail 100
```

Look for these log entries:
- ✅ "Upload POST request received"
- ✅ "File object:" with details
- ✅ "File size:" in bytes
- ✅ "Moving file to:"
- ✅ "Inserting to database:"
- ✅ "Material inserted successfully"

---

## 🔍 Common Issues & Fixes

### **Issue 1: No POST Request in Logs**
**Cause:** Form not submitting properly

**Fix:**
```php
// Check upload view file has correct form action
<form action="<?= site_url('/admin/course/' . $course_id . '/upload') ?>" 
      method="post" 
      enctype="multipart/form-data">
```

### **Issue 2: "No file uploaded" in Logs**
**Cause:** File input name mismatch or size limit

**Fix:**
1. Check PHP settings:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   ```
2. Restart Apache after changing php.ini

### **Issue 3: "Database insert failed"**
**Cause:** Table doesn't exist or field mismatch

**Fix:**
```bash
php spark migrate
```

### **Issue 4: Flash Messages Not Showing**
**Cause:** Session not persisting

**Fix:** Already implemented `session()->setFlashdata()` in controller

---

## 📋 Verification Checklist

After upload, verify ALL of these:

### **In Browser:**
- [ ] Green success message appears on dashboard
- [ ] Message says "File uploaded successfully!"
- [ ] Material appears in "Recent Course Materials" table
- [ ] Download button visible

### **In Database (phpMyAdmin):**
```sql
SELECT * FROM materials ORDER BY id DESC LIMIT 1;
```
- [ ] New row exists
- [ ] course_id is correct
- [ ] file_name matches uploaded file
- [ ] file_path starts with "writable/uploads/materials/"
- [ ] created_at has current timestamp

### **In File System:**
```
C:\xampp\htdocs\ITE311-RUALES\writable\uploads\materials\
```
- [ ] File exists with random name
- [ ] File size matches uploaded file
- [ ] File can be opened

### **In Logs:**
```
writable\logs\log-2025-10-23.log
```
- [ ] "Upload POST request received" exists
- [ ] "Material inserted successfully with ID:" exists
- [ ] No error messages

---

## 🎯 Quick Test Commands

### **1. Check if materials table exists:**
```bash
php spark db:table materials
```

### **2. Check last 50 log lines:**
```powershell
Get-Content writable\logs\log-2025-10-23.log -Tail 50
```

### **3. Count materials in database:**
```bash
php spark db:query "SELECT COUNT(*) as total FROM materials"
```

### **4. List uploaded files:**
```powershell
Get-ChildItem writable\uploads\materials\
```

---

## 💡 Expected Behavior

### **Successful Upload Flow:**

```
1. Admin clicks "Upload" button
   ↓
2. Upload form loads with course_id in URL
   ↓
3. Admin selects file (e.g., lecture.pdf)
   ↓
4. Admin clicks "Upload Material"
   ↓
5. POST request to /admin/course/4/upload
   ↓
6. Controller logs: "Upload POST request received"
   ↓
7. File validation passes
   ↓
8. File moved to: writable/uploads/materials/random123.pdf
   ↓
9. Database insert: course_id=4, file_name=lecture.pdf
   ↓
10. Log: "Material inserted successfully with ID: 1"
    ↓
11. Session flash: "File uploaded successfully!"
    ↓
12. Redirect to /dashboard
    ↓
13. Dashboard shows green success message
    ↓
14. Material appears in table
    ↓
15. ✅ SUCCESS!
```

---

## 🐛 If Upload Still Fails

### **Detailed Debug Steps:**

1. **Check Apache Error Log:**
   ```
   C:\xampp\apache\logs\error.log
   ```

2. **Enable All Error Reporting:**
   Add to `public/index.php`:
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```

3. **Test File Move Manually:**
   Create test file: `writable/test.txt`
   Try to move it:
   ```php
   rename('writable/test.txt', 'writable/uploads/materials/test.txt');
   ```

4. **Check Folder Permissions:**
   ```powershell
   icacls writable\uploads\materials
   ```
   Should show: `Everyone:(OI)(CI)(F)`

5. **Test Database Insert:**
   ```sql
   INSERT INTO materials (course_id, file_name, file_path, created_at) 
   VALUES (4, 'test.pdf', 'writable/uploads/materials/test.pdf', NOW());
   ```

---

## 📞 Contact Points

If you see specific errors:

**Error: "No file uploaded"**
→ Check browser console (F12) for JavaScript errors
→ Verify form has `enctype="multipart/form-data"`

**Error: "Database insert failed"**
→ Run: `php spark migrate`
→ Check database connection in `.env`

**Error: "Failed to move uploaded file"**
→ Check folder permissions
→ Verify Apache has write access

**No Error, No Message**
→ Check session is working: `<?php var_dump(session()->get('logged_in')); ?>`
→ Verify routes are correct: `php spark routes`

---

## ✅ Success Indicators

You'll know it works when you see:

1. ✅ Green banner: "Success! File uploaded successfully!"
2. ✅ Material in table with correct course name
3. ✅ File in `writable/uploads/materials/` directory
4. ✅ Database record in materials table
5. ✅ Student can see and download the file

---

## 🎉 Ready to Test!

**Next Steps:**
1. Run diagnostic: http://localhost:8080/test_upload.php
2. Try upload with logging enabled
3. Check logs immediately after upload
4. Verify in dashboard, database, and file system

**Report back with:**
- Screenshot of diagnostic results
- Last 50 lines of log file after upload attempt
- Any error messages you see

Let's get this working! 🚀
