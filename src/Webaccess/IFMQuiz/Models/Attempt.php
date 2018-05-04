<?php

namespace Webaccess\IFMQuiz\Models;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    const STATUS_SENT = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_MARKED = 3;

    protected $table = 'attempts';
    public $incrementing = false;
    public $casts = [
        'id' => 'string'
    ];

    protected $fillable = [
        'quiz_id',
        'user_id',
        'status',
        'started_at',
        'ends_at',
        'completed_at',
        'marked_at',
    ];
}
