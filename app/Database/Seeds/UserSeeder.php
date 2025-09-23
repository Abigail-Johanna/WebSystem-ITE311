<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        $model = new UserModel();

        $users = [
            [
                'name'     => 'Admin',
                'email'    => 'admin@example.com',
                'password' => 'admin123',
                'role'     => 'admin'  
            ],
            [
                'name'     => 'Teacher',
                'email'    => 'teacher@example.com',
                'password' => 'teacher123',  
                'role'     => 'teacher'     
            ],
            [
                'name'     => 'Student',
                'email'    => 'teacher@example.com',
                'password' => 'student123',  
                'role'     => 'student'     
            ]
        ];

        foreach ($users as $user) {
            $model->skipValidation(true)->save($user);
        }

        echo "Users seeded successfully!";
    }
}
