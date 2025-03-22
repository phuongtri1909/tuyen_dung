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
        'lm_name',
        'lm_date',
        'final_name',
        'final_date',

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
}
