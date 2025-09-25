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
        }

        echo "Users seeded successfully!";
    }
}
