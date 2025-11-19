<?php

namespace App\Controllers;

use App\Models\EnrollmentModel;
use App\Models\NotificationModel;
use CodeIgniter\Controller;

class Course extends BaseController
{
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