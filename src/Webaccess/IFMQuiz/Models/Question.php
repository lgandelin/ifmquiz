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
        'quiz_id',
    ];

    public function quiz()
    {
        return $this->belongsTo('Webaccess\IFMQuiz\Models\Quiz');
    }
}
