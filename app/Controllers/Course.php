<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;
use App\Models\NotificationModel;
use App\Models\CourseModel;
use CodeIgniter\Controller;

class Course extends BaseController
{
    /**
     * Display courses listing page with search functionality
     */
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        $userRole = strtolower(session()->get('user_role') ?? '');

        $courseModel = new CourseModel();
        $courses = $courseModel->getAllCourses();

        // Get enrolled course IDs for students
        $enrolledIds = [];
        if ($userRole === 'student' && $this->tableExists('enrollments')) {
            $enrollments = $db->table('enrollments')
                ->select('course_id')
                ->where('user_id', $userId)
                ->get()
                ->getResultArray();
            
            $enrolledIds = array_column($enrollments, 'course_id');
        }

        // Add enrolled status to each course
        foreach ($courses as &$course) {
            $course['is_enrolled'] = in_array($course['id'], $enrolledIds);
        }

        $data = [
            'title' => 'Courses',
            'courses' => $courses,
            'user_role' => session()->get('user_role'),
            'user_name' => session()->get('user_name'),
            'enrolled_ids' => $enrolledIds,
        ];

        return view('courses/index', $data);
    }

    /**
     * Search courses (server-side search)
     * Accepts both GET and POST requests
     * Returns JSON for AJAX requests or redirects for regular requests
     */
    public function search()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'You must be logged in to search courses.'
                ]);
            }
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        $userRole = strtolower(session()->get('user_role'));

        // Get search term from request
        $searchTerm = $this->request->getGet('q') ?? $this->request->getPost('q') ?? '';

        $courseModel = new CourseModel();

        // If empty search term, return all courses
        if (empty(trim($searchTerm))) {
            $results = $courseModel->getAllCourses();
        } else {
            // Perform search using CourseModel
            $results = $courseModel->searchCourses(trim($searchTerm));
        }

        // Get enrolled course IDs for students
        $enrolledIds = [];
        if ($userRole === 'student' && $this->tableExists('enrollments')) {
            $enrollments = $db->table('enrollments')
                ->select('course_id')
                ->where('user_id', $userId)
                ->get()
                ->getResultArray();
            
            $enrolledIds = array_column($enrollments, 'course_id');
        }

        // Add enrolled status to each course (instead of filtering out)
        foreach ($results as &$course) {
            $course['is_enrolled'] = in_array($course['id'], $enrolledIds);
        }

        // If AJAX request, return JSON
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'success',
                'results' => $results,
                'count' => count($results),
                'search_term' => $searchTerm,
                'enrolled_ids' => $enrolledIds
            ]);
        }

        // For regular requests, render view with results
        $data = [
            'title' => 'Search Results',
            'courses' => $results,
            'search_term' => $searchTerm,
            'user_role' => session()->get('user_role'),
            'user_name' => session()->get('user_name'),
        ];

        return view('courses/index', $data);
    }

    /**
     * Helper method to check if table exists
     */
    private function tableExists($tableName): bool
    {
        $db = \Config\Database::connect();
        return $db->query("SHOW TABLES LIKE " . $db->escape($tableName))->getNumRows() > 0;
    }
    public function enroll()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'You must be logged in to enroll.'
            ]);
        }

        $user_id = session()->get('user_id');
        $course_id = $this->request->getPost('course_id');

        if (empty($course_id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No course selected.'
            ]);
        }

        $enrollmentModel = new EnrollmentModel();

        if ($enrollmentModel->isAlreadyEnrolled($user_id, $course_id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'You are already enrolled in this course.'
            ]);
        }

        $data = [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'enrolled_at' => date('Y-m-d H:i:s')
        ];

        try {
            if ($enrollmentModel->insert($data)) {
                // Create notification for the student
                try {
                    $db = \Config\Database::connect();
                    $row = $db->table('courses')->select('title')->where('id', $course_id)->get()->getRowArray();
                    $title = $row['title'] ?? 'the course';

                    $notifModel = new NotificationModel();
                    $notifModel->insert([
                        'user_id'    => (int) $user_id,
                        'message'    => 'You have been enrolled in ' . $title,
                        'is_read'    => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                } catch (\Throwable $e) {
                    // Log but do not block the enrollment response
                    log_message('error', 'Notification insert failed: ' . $e->getMessage());
                }

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Enrollment successful!'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Enrollment failed. Please try again.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }
}