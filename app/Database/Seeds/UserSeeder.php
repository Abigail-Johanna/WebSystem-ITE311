<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $fields = $this->db->getFieldNames('users');
        if (!in_array('role', $fields)) {
            echo "⚠️ The 'role' column does not exist in 'users' table. Please add it first.\n";
            return;
        }

        $data = [
            [
                'name'       => 'Admin',
                'email'      => 'admin@example.com',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Instructor One',
                'email'      => 'instructor1@example.com',
                'password'   => password_hash('teach123', PASSWORD_DEFAULT),
                'role'       => 'instructor',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Student One',
                'email'      => 'student1@example.com',
                'password'   => password_hash('student123', PASSWORD_DEFAULT),
                'role'       => 'student',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($data as $user) {
            $exists = $this->db->table('users')->where('email', $user['email'])->get()->getRow();
            if (!$exists) {
                $this->db->table('users')->insert($user);
                echo "✅ Inserted user: {$user['email']} with role: {$user['role']}\n";
            } else {
                echo "ℹ️ User already exists: {$user['email']}, skipped.\n";
            }
        }
    }
}
