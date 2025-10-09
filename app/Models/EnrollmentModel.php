<?php namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'course_id', 'enrolled_at'];
    protected $returnType = 'array';

    /**
     * Insert a new enrollment record.
     * @param array $data ['user_id'=>int,'course_id'=>int,'enrolled_at'=>string]
     * @return int|false insert ID or false
     */
    public function enrollUser(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Fetch all courses a user is enrolled in (joins to courses table).
     * @param int $user_id
     * @return array
     */
    public function getUserEnrollments(int $user_id): array
    {
        return $this->select('enrollments.*, courses.id as course_id, courses.title, courses.description')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->where('enrollments.user_id', $user_id)
                    ->orderBy('enrolled_at', 'DESC')
                    ->findAll();
    }

    /**
     * Check if a user is already enrolled in a course.
     * @param int $user_id
     * @param int $course_id
     * @return bool
     */
    public function isAlreadyEnrolled(int $user_id, int $course_id): bool
    {
        return (bool) $this->where(['user_id' => $user_id, 'course_id' => $course_id])->first();
    }
}
