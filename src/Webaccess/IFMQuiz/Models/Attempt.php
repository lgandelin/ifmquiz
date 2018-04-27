<?php

namespace Webaccess\IFMQuiz\Models;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    protected $table = 'attempts';
    public $incrementing = false;
    public $casts = [
        'id' => 'string'
    ];

    protected $fillable = [
        'quiz_id',
        'user_id',
        'started_at',
        'ends_at',
    ];
}
