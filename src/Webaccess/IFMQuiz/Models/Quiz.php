<?php

namespace Webaccess\IFMQuiz\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quizs';
    public $incrementing = false;
    public $casts = [
        'id' => 'string'
    ];

    protected $fillable = [
        'title',
        'subtitle',
        'time',
        'intro_text',
        'outro_text',
        'training_date',
    ];
}