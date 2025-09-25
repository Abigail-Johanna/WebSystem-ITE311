<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        $model = new UserModel();

<<<<<<< HEAD
        $users = [
            [
                'name'     => 'Admin User',
                'email'    => 'admin@example.com',
                'password' => 'admin123',
                'role'     => 'admin'  
            ],
            [
                'name'     => 'Regular User',
                'email'    => 'user@example.com',
                'password' => 'user123',  
                'role'     => 'teacher'     
            ]
        ];

        foreach ($users as $user) {

            $model->skipValidation(true)->save($user);
=======
        $users = [  
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
                'role'       => 'teacher',
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

        // Loop correctly
        foreach ($users as $user) {
            $model->skipValidation(true)->insert($user);
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
        }

        echo "Users seeded successfully!";
    }
}
