<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id';
    protected $allowedFields = ['course_id', 'file_name', 'file_path', 'created_at'];

    public function insertMaterial($data)
    {
        return $this->insert($data);
    }

    public function getMaterialsByCourse($course_id)
    {
        return $this->where('course_id', $course_id)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getMaterialById($id)
    {
        return $this->find($id);
    }

    public function getMaterialsForEnrolledCourses($user_id)
    {
        return $this->select('materials.*, courses.title as course_name')
                    ->join('courses', 'courses.id = materials.course_id')
                    ->join('enrollments', 'enrollments.course_id = materials.course_id')
                    ->where('enrollments.user_id', $user_id)
                    ->orderBy('materials.created_at', 'DESC')
                    ->findAll();
    }
}
