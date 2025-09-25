<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = ['name', 'email', 'password', 'role', 'created_at', 'updated_at'];

    // Auto–timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation rules that match the ENUM in your migration
    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[255]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role'     => 'required|in_list[admin,teacher,student]',
    ];
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
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Example dashboard stats (adjust with real logic)
     */
    public function getDashboardStats(string $role, int $userId)
    {
        // Replace with real queries for your dashboard
        return [
            'total_users'        => $this->countAllResults(),
            'total_projects'     => 0,
            'total_notifications'=> 0,
            'my_courses'         => 0,
            'my_notifications'   => 0,
        ];
    }
}
