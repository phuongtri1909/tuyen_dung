<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $table = 'candidates';

    protected $fillable = [
        'full_name',
        'desired_position',
        'outlet_department',
        'employment_type',

        'hr_name',
        'hr_date',
        'hr_interview_date',
        'hr_notified',
        'hr_interviewer_id',
        
        'lm_name',
        'lm_date',
        'lm_interview_date',
        'lm_notified',
        'lm_interviewer_id',
        
        'final_name',
        'final_date',
        'final_interview_date',
        'final_notified',
        'final_interviewer_id',

        'can_work_holidays',
        'can_work_different_shifts',
        'can_work_split_shifts',
        'can_work_overtime',
        'can_work_late_shift',
        'notice_days',
        'available_date',
        'min_salary',

        'reference_feedback',

        'department_id',
        'cv',

        
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }


    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function recommendations()
    {
        return $this->hasMany(RecommendedAction::class);
    }

    // Thêm các quan hệ với người phỏng vấn
    public function hrInterviewer()
    {
        return $this->belongsTo(User::class, 'hr_interviewer_id');
    }
    
    public function lmInterviewer()
    {
        return $this->belongsTo(User::class, 'lm_interviewer_id');
    }
    
    public function finalInterviewer()
    {
        return $this->belongsTo(User::class, 'final_interviewer_id');
    }
}
