<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecommendedAction extends Model
{
    protected $table = 'recommended_actions';

    protected $fillable = [
        'candidate_id',
        'role',
        'action',
        'propose_next_step',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

}
