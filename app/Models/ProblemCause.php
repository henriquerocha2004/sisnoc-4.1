<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProblemCause extends Model
{
    protected $table = 'problem_cause';

    public function category(){
        return $this->belongsTo(CategoryProblem::class, 'id_category', 'id');
    }

}
