<?php

namespace Webaccess\IFMQuiz\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';
    public $incrementing = false;
    public $casts = [
        'id' => 'string'
    ];

    protected $fillable = [
        'title',
        'description',
        'type',
        'number',
        'items',
        'items_left',
        'items_right',
        'linear_scale_start_number',
        'linear_scale_end_number',
        'linear_scale_start_label',
        'linear_scale_end_label',
        'quiz_id',
    ];
}
