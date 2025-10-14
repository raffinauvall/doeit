<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalSaving extends Model
{
    use HasFactory;

    protected $table = 'goal_savings';

    protected $fillable = [
        'goal_id',
        'amount',
        'note',
        'saved_at',
    ];

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

}
