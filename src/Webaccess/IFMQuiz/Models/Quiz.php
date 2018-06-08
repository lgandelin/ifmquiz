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

    const EXAMEN_TYPE = 1;
    const SONDAGE_TYPE = 2;

    protected $fillable = [
        'title',
        'subtitle',
        'time',
        'intro_text',
        'outro_text',
        'header_logo',
        'footer_text',
        'footer_image',
        'training_date',
    ];
}