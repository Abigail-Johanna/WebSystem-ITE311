<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use App\Models\EnrollmentModel;
use App\Models\NotificationModel;

class Materials extends BaseController
{
    public function upload($course_id)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Check role (allow admin, instructor, and teacher)
        $role = session()->get('role');
        if ($role !== 'admin' && $role !== 'instructor' && $role !== 'teacher') {
            return redirect()->to('/dashboard');
        }

        $model = new MaterialModel();

        // Handle POST request
        if ($this->request->getMethod() === 'POST') {
            
            // Get uploaded file
            $file = $this->request->getFile('material_file');
            
            // Simple validation
            if (!$file || !$file->isValid()) {
                $_SESSION['error'] = 'Please select a valid file to upload.';
                return redirect()->to('/admin/course/' . $course_id . '/upload');
            }

            // Check file size (10MB)
            if ($file->getSize() > 10485760) {
                $_SESSION['error'] = 'File is too large. Maximum size is 10MB.';
                return redirect()->to('/admin/course/' . $course_id . '/upload');
            }

            // Check extension
            $ext = strtolower($file->getExtension());
            $allowed = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'zip'];
            
            if (!in_array($ext, $allowed)) {
                $_SESSION['error'] = 'Invalid file type. Allowed: PDF, DOC, DOCX, PPT, PPTX, TXT, ZIP';
                return redirect()->to('/admin/course/' . $course_id . '/upload');
            }

            // Create directory if not exists
            $uploadPath = WRITEPATH . 'uploads/materials/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate new filename
            $newName = $file->getRandomName();
            
            // Move file
            if ($file->move($uploadPath, $newName)) {
                
                // Save to database
                $data = [
                    'course_id' => $course_id,
                    'file_name' => $file->getClientName(),
                    'file_path' => 'writable/uploads/materials/' . $newName,
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                if ($model->insert($data)) {
                    // Create notification for successful upload
                    try {
                        $db = \Config\Database::connect();
                        $courseRow = $db->table('courses')->select('title')->where('id', $course_id)->get()->getRowArray();
                        $courseTitle = $courseRow['title'] ?? 'a course';
                        
                        $notifModel = new NotificationModel();
                        $notifModel->insert([
                            'user_id'    => (int) session()->get('user_id'),
                            'message'    => 'You have successfully uploaded a file to ' . $courseTitle,
                            'is_read'    => 0,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    } catch (\Throwable $e) {
                        log_message('error', 'File upload notification failed: ' . $e->getMessage());
                    }
                    
                    $_SESSION['success'] = 'File uploaded successfully!';
                    return redirect()->to('/dashboard');
                } else {
                    // Delete file if database insert failed
                    @unlink($uploadPath . $newName);
                    $_SESSION['error'] = 'Database error. Please try again.';
                    return redirect()->to('/admin/course/' . $course_id . '/upload');
                }
                
            } else {
                $_SESSION['error'] = 'Failed to upload file. Please try again.';
                return redirect()->to('/admin/course/' . $course_id . '/upload');
            }
        }

        // Show upload form
        return view('materials/upload', ['course_id' => $course_id]);
    }

    public function download($material_id)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        $materialModel = new MaterialModel();
        $enrollmentModel = new EnrollmentModel();
        
        $material = $materialModel->getMaterialById($material_id);

        if (!$material) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }

        // Check if the student is enrolled in the course
        $user_id = session()->get('user_id');
        $role = session()->get('role');
        
        // Allow admins/instructors to download any material
        if ($role !== 'admin' && $role !== 'instructor') {
            $isEnrolled = $enrollmentModel->isAlreadyEnrolled($user_id, $material['course_id']);
            
            if (!$isEnrolled) {
                return redirect()->to('/dashboard')->with('error', 'You must be enrolled in this course to download materials.');
            }
        }

        // Check if file exists
        $filePath = ROOTPATH . $material['file_path'];
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        return $this->response->download($filePath, null)->setFileName($material['file_name']);
    }

    public function delete($material_id)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Check role (allow admin, instructor, and teacher)
        $role = session()->get('role');
        if ($role !== 'admin' && $role !== 'instructor' && $role !== 'teacher') {
            return redirect()->to('/dashboard');
        }

        $model = new MaterialModel();
        $material = $model->find($material_id);

        if ($material) {
            $filePath = ROOTPATH . $material['file_path'];
            
            // Delete the file if it exists
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            
            // Delete the database record
            $model->delete($material_id);
            $_SESSION['success'] = 'Material deleted successfully!';
            return redirect()->to('/dashboard');
        }

        $_SESSION['error'] = 'Material not found.';
        return redirect()->to('/dashboard');
    }

    public function index()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        $materialModel = new MaterialModel();
        $user_id = session()->get('user_id');
        
        // Get materials for enrolled courses
        $materials = $materialModel->getMaterialsForEnrolledCourses($user_id);
        
        return view('materials/list', ['materials' => $materials]);
    }
}
