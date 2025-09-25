<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
<<<<<<< HEAD
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = ['name', 'email', 'password', 'role', 'created_at', 'updated_at'];

    // Auto–timestamps
=======

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'name', 'email', 'password', 'role', 'created_at', 'updated_at'
    ];

>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

<<<<<<< HEAD
    // Validation rules that match the ENUM in your migration
=======
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[255]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role'     => 'required|in_list[admin,teacher,student]',
    ];
<<<<<<< HEAD
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Create a new user and hash the password
     */
    public function createAccount(array $data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->insert($data);
    }

    /**
     * Find user by email
     */
    public function findUserByEmail(string $email)
=======

    protected $validationMessages = [
        'name' => [
            'required'   => 'Name is required',
            'min_length' => 'Name must be at least 3 characters long',
            'max_length' => 'Name cannot exceed 255 characters'
        ],
        'email' => [
            'required'    => 'Email is required',
            'valid_email' => 'Please provide a valid email address',
            'is_unique'   => 'This email is already registered'
        ],
        'password' => [
            'required'   => 'Password is required',
            'min_length' => 'Password must be at least 6 characters long'
        ],
        'role' => [
            'required' => 'Role is required',
            'in_list'  => 'Role must be admin, teacher, or student'
        ]
    ];

    // ---------- Relationships / Joins ----------
    public function withProjects()
    {
        return $this->select('users.*, projects.id as project_id, projects.title as project_title')
                    ->join('projects', 'projects.user_id = users.id', 'left');
    }

    public function withCourses()
    {
        return $this->select('users.*, courses.id as course_id, courses.title as course_title')
                    ->join('courses', 'courses.user_id = users.id', 'left');
    }

    public function withEnrollments()
    {
        return $this->select('users.*, enrollments.id as enrollment_id, enrollments.course_id')
                    ->join('enrollments', 'enrollments.student_id = users.id', 'left');
    }

    public function withNotifications()
    {
        return $this->select('users.*, notifications.id as notif_id, notifications.message as notif_message')
                    ->join('notifications', 'notifications.user_id = users.id', 'left');
    }

    /**
     * Example combined fetch:
     * $this->withProjects()->withNotifications()->findAll();
     */

    // ---------- Existing Methods ----------
    public function findUserByEmail($email)
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
    {
        return $this->where('email', $email)->first();
    }

<<<<<<< HEAD
    /**
     * Example dashboard stats (adjust with real logic)
     */
    public function getDashboardStats(string $role, int $userId)
    {
        // Replace with real queries for your dashboard
        return [
            'total_users'        => $this->countAllResults(),
=======
    public function createAccount($userData)
    {
        return $this->insert([
            'name'     => $userData['name'],
            'email'    => $userData['email'],
            'password' => password_hash($userData['password'], PASSWORD_DEFAULT),
            'role'     => $userData['role'],
        ]);
    }

    public function getDashboardStats($userRole, $userId = null)
    {
        $stats = [
            'total_users'        => 0,
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
            'total_projects'     => 0,
            'total_notifications'=> 0,
            'my_courses'         => 0,
            'my_notifications'   => 0,
        ];
<<<<<<< HEAD
=======

        $db = \Config\Database::connect();

        if ($userRole === 'admin') {
            $stats['total_users'] = $this->countAllResults();

            if ($db->tableExists('projects')) {
                $stats['total_projects'] = $db->table('projects')->countAllResults();
            }
            if ($db->tableExists('notifications')) {
                $stats['total_notifications'] = $db->table('notifications')->countAllResults();
            }

        } elseif ($userRole === 'teacher' && $userId) {
            if ($db->tableExists('courses')) {
                $stats['my_courses'] = $db->table('courses')
                                          ->where('user_id', $userId)
                                          ->countAllResults();
            }
            if ($db->tableExists('notifications')) {
                $stats['my_notifications'] = $db->table('notifications')
                                               ->where('user_id', $userId)
                                               ->countAllResults();
            }

        } elseif ($userRole === 'student' && $userId) {
            if ($db->tableExists('enrollments')) {
                $stats['my_courses'] = $db->table('enrollments')
                                          ->where('student_id', $userId)
                                          ->countAllResults();
            }
            if ($db->tableExists('notifications')) {
                $stats['my_notifications'] = $db->table('notifications')
                                               ->where('user_id', $userId)
                                               ->countAllResults();
            }
        }

        return $stats;
    }

    public function getAllUsers()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    public function getUsersByRole($role)
    {
        return $this->where('role', $role)->findAll();
    }

    public function updateUserRole($userId, $newRole)
    {
        return $this->update($userId, ['role' => $newRole]);
    }

    public function emailExists($email, $excludeId = null)
    {
        $query = $this->where('email', $email);
        if ($excludeId) {
            $query->where('id !=', $excludeId);
        }
        return $query->first() !== null;
    }

    public function getUserProfile($userId)
    {
        return $this->select('id, name, email, role, created_at, updated_at')
                    ->where('id', $userId)
                    ->first();
    }

    public function updateProfile($userId, $data)
    {
        if (isset($data['password']) && empty($data['password'])) {
            unset($data['password']);
        }
        return $this->update($userId, $data);
    }

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            if (!password_get_info($data['data']['password'])['algo']) {
                $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            }
        }
        return $data;
    }

    public function verifyCredentials($email, $password)
    {
        $user = $this->findUserByEmail($email);
        return ($user && password_verify($password, $user['password'])) ? $user : false;
    }

    public function getRecentUsers($limit = 5)
    {
        return $this->select('id, name, email, role, created_at')
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
    }
}
