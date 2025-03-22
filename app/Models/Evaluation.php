<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $table = 'evaluations';

    protected $fillable = [
        'candidate_id',
        'role',
        'criteria',
        'rating',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
