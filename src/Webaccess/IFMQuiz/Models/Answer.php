<?php

namespace Webaccess\IFMQuiz\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = 'answers';
    public $incrementing = false;
    public $casts = [
        'id' => 'string'
    ];

    protected $fillable = [
        'quiz_id',
        'question_id',
        'user_id',
        'items',
        'items_left',
        'items_right',
        'correct',
    ];
}
