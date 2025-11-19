<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Advanced Database',
                'description' => 'Learn the advanced fundamentals of Database',
                'instructor_id'=> 2,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Web Development',
                'description' => 'Build modern web applications using HTML, CSS, and JavaScript.',
                'instructor_id'=> 2,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'System Architecture and Integration',
                'description' => 'Explore system architecture principles and integration techniques.',
                'instructor_id'=> 2,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Networking Fundamentals',
                'description' => 'Understand the basics of computer networking and protocols.',
                'instructor_id'=> 2,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('courses')->insertBatch($data);
    }
}
