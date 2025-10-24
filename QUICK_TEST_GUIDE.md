# 🚀 QUICK TEST GUIDE - File Upload System

## ⚡ 3-Minute Test

### Test 1: Admin Upload (2 minutes)

**1. Refresh Browser** → Press `Ctrl + F5`

**2. Login as Admin** → http://localhost:8080/dashboard

**3. Scroll to "Courses Management" section**
   - You should see your 6 courses listed
   - Each course has an "Upload" button

**4. Click "Upload" on "Database Fundamentals" (course #4)**

**5. Upload Page Appears:**
   - Choose a small PDF file (< 5MB)
   - Click "Upload Material"

**6. SUCCESS! You should see:**
   ```
   ✓ Success! File uploaded successfully!
   ```

**7. Verify in Dashboard:**
   - Scroll to "Recent Course Materials"
   - Your uploaded file should appear there

**8. Verify in Database (phpMyAdmin):**
   ```sql
   SELECT * FROM materials;
   ```
   - Should show 1 row with your file

---

### Test 2: Student Download (1 minute)

**1. Logout** → Click "Logout"

**2. Login as Student**

**3. Enroll in "Database Fundamentals"** (if not enrolled)
   - Find course in "Available Courses"
   - Click "Enroll"

**4. Scroll to "My Course Materials"**
   - You should see the file you uploaded
   - Course badge shows "Database Fundamentals"
   - File name is your original filename

**5. Click "Download"**
   - File downloads immediately
   - Opens correctly

**6. ✅ SUCCESS! Everything works!**

---

## 🎯 What You Should See

### Admin Dashboard (After Login):

```
┌─────────────────────────────────────────────────────────┐
│  ✓ Success! File uploaded successfully!                 │
└─────────────────────────────────────────────────────────┘

Statistics:  [4 Users]  [6 Courses]  [2 Students]  [1 Teacher]

┌─────────────────────────────────────────────────────────┐
│ 📚 Courses Management                                   │
├────┬──────────────────────┬──────────────┬─────────────┤
│ #  │ Course Title         │ Description  │  Actions    │
├────┼──────────────────────┼──────────────┼─────────────┤
│ 1  │ Database Fundamental │ Learn about. │  [Upload]   │
│ 2  │ Web Development      │ Build modern │  [Upload]   │
│ 3  │ System Architecture  │ Explore sys. │  [Upload]   │
│ 4  │ Web Development      │ Build modern │  [Upload]   │
│ 5  │ Advanced Database    │ Learn about. │  [Upload]   │
│ 6  │ System Architecture  │ Explore sys. │  [Upload]   │
└────┴──────────────────────┴──────────────┴─────────────┘

┌─────────────────────────────────────────────────────────┐
│ 📄 Recent Course Materials            [1 materials]     │
├─────────────┬────────────────┬────────────┬────────────┤
│   Course    │   File Name    │  Uploaded  │  Actions   │
├─────────────┼────────────────┼────────────┼────────────┤
│ Database... │ lecture.pdf    │  Oct 24    │  ↓  🗑️    │
└─────────────┴────────────────┴────────────┴────────────┘
```

### Student Dashboard:

```
┌─────────────────────────────────────────────────────────┐
│  Welcome to your student dashboard!                     │
└─────────────────────────────────────────────────────────┘

[2 Enrolled Courses]            [0 Upcoming Deadlines]

My Enrolled Courses          │   Available Courses
─────────────────────────────┼───────────────────────────
• Database Fundamentals      │   • Web Development [Enroll]
• System Architecture        │   • Advanced Database [Enroll]

┌─────────────────────────────────────────────────────────┐
│ 📄 My Course Materials                    [View All]    │
├─────────────┬────────────────┬────────────┬────────────┤
│   Course    │   File Name    │  Uploaded  │  Action    │
├─────────────┼────────────────┼────────────┼────────────┤
│ Database... │ lecture.pdf    │  Oct 24    │ [Download] │
│ System...   │ notes.docx     │  Oct 23    │ [Download] │
└─────────────┴────────────────┴────────────┴────────────┘
```

---

## 🔍 Quick Checks

### ✅ Upload Working if:
1. No error messages appear
2. Success message shows on dashboard
3. File appears in materials table (database)
4. File exists in `writable/uploads/materials/`
5. Material shows in "Recent Course Materials"

### ✅ Student Access Working if:
1. Student can enroll in course
2. Material appears in "My Course Materials"
3. Download button works
4. File downloads with correct filename
5. File opens correctly

### ❌ Something Wrong if:
1. "No file selected" → You didn't choose a file
2. "File is too large" → File > 10MB
3. "Invalid file type" → Not PDF/DOC/PPT/etc
4. "Database error" → Check database connection
5. Nothing in materials table → Upload failed silently

---

## 📝 Test Files to Use

**Good Test Files:**
- ✅ sample.pdf (< 5MB)
- ✅ lecture-notes.docx (< 5MB)
- ✅ presentation.pptx (< 5MB)
- ✅ assignment.txt (any size)

**Files That Will Fail:**
- ❌ video.mp4 (wrong type)
- ❌ software.exe (wrong type)
- ❌ large-file.pdf (> 10MB)
- ❌ image.jpg (wrong type)

---

## 🆘 Quick Fixes

### Problem: Upload button not showing
**Fix:** Hard refresh (Ctrl + F5)

### Problem: File uploads but not in database
**Fix:** Check error logs at `writable/logs/`

### Problem: Student can't see material
**Fix:** 
1. Verify enrollment: `SELECT * FROM enrollments;`
2. Check course_id matches

### Problem: Download gives 404
**Fix:** 
1. Check file exists in `writable/uploads/materials/`
2. Verify file_path in database is correct

---

## 🎉 Ready to Test!

**Current Status:**
- ✅ Materials table created
- ✅ Upload controller fixed
- ✅ Dashboard updated
- ✅ Success messages added
- ✅ Error handling improved

**Server Running:** http://localhost:8080

**What to Do Now:**
1. Close all browser tabs
2. Open fresh browser window
3. Go to http://localhost:8080/login
4. Follow Test 1 above
5. Then do Test 2
6. ✅ Success!

---

## 📸 Screenshots Needed for Lab

After successful testing, capture:

1. **Admin Dashboard** - showing courses with upload buttons
2. **Upload Success** - green success message
3. **Materials Table** - phpMyAdmin showing uploaded file
4. **File Directory** - Windows Explorer showing uploaded file
5. **Student Dashboard** - showing downloadable material

---

## ✅ Completion Checklist

Mark these as you test:

**Admin Tests:**
- [ ] Can see courses table
- [ ] Can click Upload button
- [ ] Upload form loads
- [ ] Can select file
- [ ] Upload succeeds
- [ ] Success message appears
- [ ] File in database
- [ ] File on disk
- [ ] Material shows in dashboard

**Student Tests:**
- [ ] Can enroll in course
- [ ] Material appears after enrollment
- [ ] Can click Download
- [ ] File downloads
- [ ] File opens correctly
- [ ] Cannot access non-enrolled materials

**All tests passed?** → Ready to submit Lab 7! 🎉
