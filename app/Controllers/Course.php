<?php namespace App\Controllers;

use App\Models\EnrollmentModel;
use CodeIgniter\Controller;

class Course extends Controller
{
    protected $enrollmentModel;
    protected $db;

    public function __construct()
    {
        helper(['url', 'form']);
        $this->enrollmentModel = new EnrollmentModel();
        $this->db = \Config\Database::connect();
    }

    // AJAX Enroll method
    public function enroll()
    {
        $session = session();
        $user_id = $session->get('user_id');

        // Check if user is logged in
        if (!$user_id) {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $course_id = $this->request->getPost('course_id');

        // Validate input
        if (empty($course_id) || !is_numeric($course_id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid course ID']);
        }

        // Check if course exists
        $course = $this->db->table('courses')->where('id', $course_id)->get()->getRowArray();
        if (!$course) {
            return $this->response->setJSON(['success' => false, 'message' => 'Course not found']);
        }

        // Check if already enrolled
        if ($this->enrollmentModel->isAlreadyEnrolled($user_id, $course_id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Already enrolled']);
        }

        // Enroll user
        $data = [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'enrolled_at' => date('Y-m-d H:i:s')
        ];

        if ($this->enrollmentModel->enrollUser($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment successful!',
                'course' => $course
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Enrollment failed']);
    }

    // Optional: Display dashboard
    public function dashboard()
    {
        $session = session();
        $user_id = $session->get('user_id');

        if (!$user_id) return redirect()->to('/login');

        $enrolled = $this->enrollmentModel->getUserEnrollments($user_id);

        $enrolled_ids = array_column($enrolled, 'course_id');
        $builder = $this->db->table('courses');
        if (!empty($enrolled_ids)) {
            $builder->whereNotIn('id', $enrolled_ids);
        }
        $available = $builder->get()->getResultArray();

        return view('student/dashboard', [
            'enrolledCourses' => $enrolled,
            'availableCourses' => $available
        ]);
    }
}
