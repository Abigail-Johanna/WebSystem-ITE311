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

    // MANAGE USERS (Admin only)
    public function manageUsers()
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $userRole = strtolower($session->get('user_role'));
        if ($userRole !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $userModel = new UserModel();
        
        // Get all users (including deleted ones so they can be restored)
        // Users with status 'deleted' will still appear in the table with a "Restore" button
        $db = \Config\Database::connect();
        
        // Get all users - including deleted ones so admin can restore them
        $users = $db->table('users')
                    ->orderBy('created_at', 'DESC')
                    ->get()
                    ->getResultArray();

        $data = [
            'title'     => 'Manage Users',
            'user_name' => $session->get('user_name'),
            'user_role' => $userRole,
            'users'     => $users,
        ];

        return view('auth/manage_users', $data);
    }

    // DELETE USER (Soft delete - set status to 'deleted')
    public function deleteUser($userId)
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $userRole = strtolower($session->get('user_role'));
        if ($userRole !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $userModel = new UserModel();
        $currentUserId = $session->get('user_id');

        // Prevent self-deletion
        if ($userId == $currentUserId) {
            return redirect()->to('/manage-users')->with('error', 'You cannot delete your own account.');
        }

        // Get user to check if protected
        $user = $userModel->find($userId);
        if (!$user) {
            return redirect()->to('/manage-users')->with('error', 'User not found.');
        }

        // Check if user is the first admin (protected) - only id=1 admin is fully protected
        if ($user['id'] == 1 && $user['role'] === 'admin') {
            return redirect()->to('/manage-users')->with('error', 'This admin account is protected and cannot be deleted.');
        }

        // Soft delete: set status to 'deleted'
        $db = \Config\Database::connect();
        $userId = (int)$userId;
        
        // First, check and modify status column if it's an ENUM that doesn't include 'deleted'
        try {
            $columnInfo = $db->query("SHOW COLUMNS FROM `users` LIKE 'status'")->getRowArray();
            if ($columnInfo && isset($columnInfo['Type'])) {
                $columnType = $columnInfo['Type'];
                // If it's an ENUM without 'deleted', modify it
                if (stripos($columnType, 'enum') !== false && stripos($columnType, 'deleted') === false) {
                    $db->query("ALTER TABLE `users` MODIFY COLUMN `status` VARCHAR(20) DEFAULT NULL");
                }
            }
        } catch (\Exception $e) {
            // Column might not exist, create it
            try {
                $db->query("ALTER TABLE `users` ADD COLUMN `status` VARCHAR(20) DEFAULT NULL");
            } catch (\Exception $e2) {
                // Column might already exist, ignore
            }
        }
        
        // Use simpleQuery - executes SQL directly without returning results
        $sql = "UPDATE `users` SET `status` = 'deleted' WHERE `id` = " . (int)$userId;
        $db->simpleQuery($sql);
        
        // Verify the update worked
        $verify = $db->query("SELECT `status` FROM `users` WHERE `id` = " . (int)$userId);
        $row = $verify->getRowArray();
        
        if ($row && isset($row['status']) && $row['status'] === 'deleted') {
            return redirect()->to('/manage-users')->with('success', 'User removed from admin view successfully! (User data preserved in database)');
        }
        
        // If simpleQuery didn't work, try table builder
        $updateResult = $db->table('users')->where('id', $userId)->update(['status' => 'deleted']);
        
        if ($updateResult) {
            $check = $db->table('users')->where('id', $userId)->get()->getRowArray();
            if ($check && isset($check['status']) && $check['status'] === 'deleted') {
                return redirect()->to('/manage-users')->with('success', 'User removed from admin view successfully! (User data preserved in database)');
            }
        }
        
        // Last resort: Model update
        $userModel->skipValidation(true);
        if ($userModel->update($userId, ['status' => 'deleted'])) {
            $final = $userModel->find($userId);
            if ($final && isset($final['status']) && $final['status'] === 'deleted') {
                return redirect()->to('/manage-users')->with('success', 'User removed from admin view successfully! (User data preserved in database)');
            }
        }
        
        // If all failed, get current status
        $current = $db->table('users')->where('id', $userId)->get()->getRowArray();
        $currentStatus = $current['status'] ?? 'NULL';
        
        return redirect()->to('/manage-users')->with('error', 'Failed to update user status. Current status: ' . $currentStatus);
    }

    // DEACTIVATE USER (Set status to 'inactive')
    public function deactivateUser($userId)
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $userRole = strtolower($session->get('user_role'));
        if ($userRole !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $userModel = new UserModel();
        $currentUserId = $session->get('user_id');

        // Prevent self-deactivation
        if ($userId == $currentUserId) {
            return redirect()->to('/manage-users')->with('error', 'You cannot deactivate your own account.');
        }

        // Get user to check if protected
        $user = $userModel->find($userId);
        if (!$user) {
            return redirect()->to('/manage-users')->with('error', 'User not found.');
        }

        // Check if user is the first admin (protected) - only id=1 admin is fully protected
        if ($user['id'] == 1 && $user['role'] === 'admin') {
            return redirect()->to('/manage-users')->with('error', 'This admin account is protected and cannot be deactivated.');
        }

        // Deactivate: set status to 'inactive'
        $db = \Config\Database::connect();
        $userId = (int)$userId;
        
        // Use simpleQuery for reliable update
        $sql = "UPDATE `users` SET `status` = 'inactive' WHERE `id` = " . (int)$userId;
        $db->simpleQuery($sql);
        
        // Verify
        $verify = $db->query("SELECT `status` FROM `users` WHERE `id` = " . (int)$userId);
        $row = $verify->getRowArray();
        
        if ($row && isset($row['status']) && $row['status'] === 'inactive') {
            return redirect()->to('/manage-users')->with('success', 'User deactivated successfully.');
        }
        
        // Fallback: Use table builder
        $db->table('users')->where('id', $userId)->update(['status' => 'inactive']);
        
        return redirect()->to('/manage-users')->with('success', 'User deactivated successfully.');
    }

    // CHANGE USER ROLE
    public function changeRole()
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $userRole = strtolower($session->get('user_role'));
        if ($userRole !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/manage-users')->with('error', 'Invalid request method.');
        }

        $userId = $this->request->getPost('user_id');
        $newRole = $this->request->getPost('role');

        if (!$userId || !$newRole) {
            return redirect()->to('/manage-users')->with('error', 'Missing required parameters.');
        }

        // Only allow changing to teacher or student (admin is protected)
        if (!in_array($newRole, ['teacher', 'student'])) {
            return redirect()->to('/manage-users')->with('error', 'Invalid role selected. Only teacher and student roles can be assigned.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/manage-users')->with('error', 'User not found.');
        }

        // Check if user is the first admin (protected) - only id=1 admin is fully protected
        if ($user['id'] == 1 && $user['role'] === 'admin') {
            return redirect()->to('/manage-users')->with('error', 'This admin account is protected and its role cannot be changed.');
        }

        // Update role
        $userModel->update($userId, ['role' => strtolower($newRole)]);

        return redirect()->to('/manage-users')->with('success', 'User role updated successfully.');
    }

    // RESTORE USER (Set status from 'deleted' to 'active')
    public function restoreUser($userId)
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $userRole = strtolower($session->get('user_role'));
        if ($userRole !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/manage-users')->with('error', 'User not found.');
        }

        // Check if user is actually deleted
        if (isset($user['status']) && $user['status'] !== 'deleted') {
            return redirect()->to('/manage-users')->with('error', 'User is not deleted. Cannot restore.');
        }

        // Restore: set status to 'active'
        $db = \Config\Database::connect();
        $userId = (int)$userId;
        
        // Use simpleQuery for direct execution
        $sql = "UPDATE `users` SET `status` = 'active' WHERE `id` = " . (int)$userId;
        $db->simpleQuery($sql);
        
        // Verify the update worked
        $verify = $db->query("SELECT `status` FROM `users` WHERE `id` = " . (int)$userId);
        $row = $verify->getRowArray();
        
        if ($row && isset($row['status']) && $row['status'] === 'active') {
            return redirect()->to('/manage-users')->with('success', 'User restored successfully! Status changed to active.');
        }
        
        // Fallback: Use table builder
        $db->table('users')->where('id', $userId)->update(['status' => 'active']);
        
        return redirect()->to('/manage-users')->with('success', 'User restored successfully! Status changed to active.');
    }

    // ACTIVATE USER (Set status to 'active')
    public function activateUser($userId)
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $userRole = strtolower($session->get('user_role'));
        if ($userRole !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/manage-users')->with('error', 'User not found.');
        }

        // Activate: set status to 'active'
        $db = \Config\Database::connect();
        $userId = (int)$userId;
        
        // Use simpleQuery for reliable update
        $sql = "UPDATE `users` SET `status` = 'active' WHERE `id` = " . (int)$userId;
        $db->simpleQuery($sql);
        
        // Verify
        $verify = $db->query("SELECT `status` FROM `users` WHERE `id` = " . (int)$userId);
        $row = $verify->getRowArray();
        
        if ($row && isset($row['status']) && $row['status'] === 'active') {
            return redirect()->to('/manage-users')->with('success', 'User activated successfully.');
        }
        
        // Fallback: Use table builder
        $db->table('users')->where('id', $userId)->update(['status' => 'active']);
        
        return redirect()->to('/manage-users')->with('success', 'User activated successfully.');
    }

    // CREATE USER (Admin only - with auto-generated password)
    public function createUser()
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $userRole = strtolower($session->get('user_role'));
        if ($userRole !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/manage-users')->with('error', 'Invalid request method.');
        }

        if (!$this->validate([
            'name'     => 'required|min_length[3]|max_length[255]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'role'     => 'required|in_list[admin,teacher,student]',
            'status'   => 'permit_empty|in_list[active,inactive]',
        ])) {
            return redirect()->to('/manage-users')->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        
        // Generate role-based password
        $role = strtolower($this->request->getPost('role'));
        $password = $this->generateRoleBasedPassword($role);
        
        $result = $userModel->createAccount([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => $password,
            'role'     => $role,
            'status'   => $this->request->getPost('status') ?? 'active',
        ]);

        if (is_array($result)) {
            return redirect()->to('/manage-users')->withInput()->with('errors', $result);
        }

        return redirect()->to('/manage-users')->with('success', 'User created successfully! Auto-generated password: ' . $password . ' (Please save this password)');
    }

    // Generate role-based password
    private function generateRoleBasedPassword($role)
    {
        $role = strtolower($role);
        switch ($role) {
            case 'admin':
                return 'admin123';
            case 'teacher':
                return 'teacher123';
            case 'student':
                return 'student123';
            default:
                return 'password123';
        }
    }

    // CHANGE PASSWORD (For teachers and students only)
    public function changePassword()
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/auth/login')->with('error', 'Please login first.');
        }

        $userRole = strtolower($session->get('user_role'));
        
        // Only teachers and students can change their password
        if (!in_array($userRole, ['teacher', 'student'])) {
            return redirect()->to('/dashboard')->with('error', 'Only teachers and students can change their password.');
        }

        if ($this->request->getMethod() === 'GET') {
            $data = [
                'title'     => 'Change Password',
                'user_name' => $session->get('user_name'),
                'user_role' => $userRole,
            ];
            return view('auth/change_password', $data);
        }

        if ($this->request->getMethod() === 'POST') {
            if (!$this->validate([
                'current_password' => 'required',
                'new_password'     => 'required|min_length[6]',
                'confirm_password' => 'required|matches[new_password]',
            ])) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $userModel = new UserModel();
            $userId = $session->get('user_id');
            $user = $userModel->find($userId);

            if (!$user) {
                return redirect()->back()->with('error', 'User not found.');
            }

            // Verify current password
            if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
                return redirect()->back()->with('error', 'Current password is incorrect.');
            }

            // Update password
            $userModel->update($userId, [
                'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT)
            ]);

            return redirect()->to('/dashboard')->with('success', 'Password changed successfully.');
        }
    }
}
