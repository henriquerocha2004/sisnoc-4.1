<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProblemCause;

class CategoryProblem extends Model
{
    protected $table = 'category_problem';

    public function problems(){
        return $this->hasMany(ProblemCause::class, 'id_category', 'id');
    }
}
