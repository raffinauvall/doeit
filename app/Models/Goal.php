<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $table = 'goals';

    protected $fillable = [
        'users_id',
        'title',
        'amount_target',
        'amount_current',
        'photo',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
