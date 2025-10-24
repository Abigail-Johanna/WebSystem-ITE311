<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MaterialModel;
use CodeIgniter\Controller;

class Auth extends BaseController
{
    protected $helpers = ['form', 'url'];

    // LOGIN
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        if ($this->request->getMethod() === 'GET') {
            return view('auth/login');
        }

        if ($this->request->getMethod() === 'POST') {
            if (!$this->validate([
                'email'    => 'required|valid_email',
                'password' => 'required|min_length[6]'
            ])) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $userModel = new UserModel();
            $email     = $this->request->getPost('email');
            $password  = $this->request->getPost('password');
            $user      = $userModel->findUserByEmail($email);

            if (!$user) {
                return redirect()->back()->withInput()->with('error', 'Email not found.');
            }

            if (!password_verify($password, $user['password'])) {
                return redirect()->back()->withInput()->with('error', 'Incorrect password.');
            }

            if (isset($user['status']) && $user['status'] !== 'active') {
                return redirect()->back()->with('error', 'Your account is not active. Please contact admin.');
            }

            session()->set([
                'user_id'   => $user['id'],
                'user_name' => $user['name'],
                'user_role' => strtolower($user['role']),
                'role'      => strtolower($user['role']), // Add role alias for consistency
                'logged_in' => true,
            ]);

            return redirect()->to('/dashboard')->with('success', 'You have successfully logged in.');
        }
    }

    // REGISTER
    public function register()
    {
        if ($this->request->getMethod() === 'GET') {
            return view('auth/register');
        }

        if ($this->request->getMethod() === 'POST') {
            if (!$this->validate([
                'name'              => 'required|min_length[3]|max_length[255]',
                'email'             => 'required|valid_email|is_unique[users.email]',
                'password'          => 'required|min_length[6]',
                'confirm_password'  => 'required|matches[password]',
                'role'              => 'required|in_list[admin,teacher,student]',
            ])) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $userModel = new UserModel();
            $result = $userModel->createAccount([
                'name'     => $this->request->getPost('name'),
                'email'    => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'role'     => strtolower($this->request->getPost('role')),
            ]);

            if (is_array($result)) {
                return redirect()->back()->withInput()->with('errors', $result);
            }

            return redirect()->to('/auth/login')->with('success', 'Account created successfully. You can now login.');
        }
    }

    // LOGOUT
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'You have been logged out.');
    }

    // DASHBOARD
    public function dashboard()
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $db        = \Config\Database::connect();
        $userModel = new UserModel();

        $userId   = $session->get('user_id');
        $userRole = strtolower($session->get('user_role'));
        $user     = $userModel->find($userId);

        $users = [];
        $courses = [];
        $deadlines = [];
        $enrolledCourses = [];
        $materials = [];

        try {
            // ADMIN
            if ($userRole === 'admin') {
                $users = $userModel->select('id, name, email, role')->findAll();

                if ($this->tableExists('courses')) {
                    $courses = $db->table('courses')->get()->getResultArray();
                }

                // Get all materials for admin
                if ($this->tableExists('materials')) {
                    $materialModel = new MaterialModel();
                    $materials = $db->table('materials')
                        ->select('materials.*, courses.title as course_name')
                        ->join('courses', 'courses.id = materials.course_id')
                        ->orderBy('materials.created_at', 'DESC')
                        ->limit(5)
                        ->get()
                        ->getResultArray();
                }
            }

            // TEACHER
            elseif ($userRole === 'teacher') {
                if ($this->tableExists('courses')) {
                    $courses = $db->table('courses')
                        ->where('instructor_id', $userId)
                        ->get()
                        ->getResultArray();
                }

                // Get materials for teacher's courses
                if ($this->tableExists('materials')) {
                    $materials = $db->table('materials')
                        ->select('materials.*, courses.title as course_name')
                        ->join('courses', 'courses.id = materials.course_id')
                        ->where('courses.instructor_id', $userId)
                        ->orderBy('materials.created_at', 'DESC')
                        ->limit(5)
                        ->get()
                        ->getResultArray();
                }
            }

            // STUDENT
            elseif ($userRole === 'student') {
                if ($this->tableExists('courses')) {
                    $courses = $db->table('courses')
                        ->select('id, title, description')
                        ->get()
                        ->getResultArray();
                }

                if ($this->tableExists('enrollments')) {
                    // Get enrolled courses
                    $enrolledCourses = $db->table('enrollments')
                        ->select('courses.id, courses.title, courses.description')
                        ->join('courses', 'enrollments.course_id = courses.id')
                        ->where('enrollments.user_id', $userId)
                        ->get()
                        ->getResultArray();

                    // Hide enrolled courses from available list
                    $enrolledIds = array_column($enrolledCourses, 'id');
                    $courses = array_filter($courses, fn($c) => !in_array($c['id'], $enrolledIds));
                }

                // Get materials for enrolled courses
                if ($this->tableExists('materials')) {
                    $materialModel = new MaterialModel();
                    $materials = $materialModel->getMaterialsForEnrolledCourses($userId);
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'Dashboard error: ' . $e->getMessage());
        }

        $stats = $userModel->getDashboardStats($userRole, $userId);

        $data = [
            'title'            => ucfirst($userRole) . ' Dashboard',
            'user'             => $user,
            'user_name'        => $session->get('user_name'),
            'user_role'        => $userRole,
            'users'            => $users,
            'courses'          => $courses,
            'enrolledCourses'  => $enrolledCourses,
            'stats'            => $stats,
            'deadlines'        => $deadlines,
            'materials'        => $materials,
        ];

        return view('auth/dashboard', $data);
    }

    private function tableExists($tableName): bool
    {
        $db = \Config\Database::connect();
        return $db->query("SHOW TABLES LIKE " . $db->escape($tableName))->getNumRows() > 0;
    }

    private function columnExists($tableName, $columnName): bool
    {
        $db = \Config\Database::connect();
        if (!$this->tableExists($tableName)) {
            return false;
        }
        $fields = $db->getFieldNames($tableName);
        return in_array($columnName, $fields);
    }
}
