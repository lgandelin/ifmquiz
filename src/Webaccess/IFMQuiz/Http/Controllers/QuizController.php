<?php

namespace Webaccess\IFMQuiz\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webaccess\IFMQuiz\Models\Question;
use Webaccess\IFMQuiz\Models\Quiz;

class QuizController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(Request $request) {
        $quizs = Quiz::all();

        return view('ifmquiz::dashboard.index', [
            'quizs' => $quizs
        ]);
    }

    public function create(Request $request) {
    }

    public function update(Request $request, $quizID) {
        $quiz = Quiz::find($quizID);

        return view('ifmquiz::quiz.update', [
            'quiz' => $quiz,
        ]);
    }

    public function results(Request $request, $quizID) {
    }

    public function duplicate(Request $request, $quizID) {
    }

    public function delete(Request $request, $quizID) {
    }

    public function questions(Request $request, $quizID) {
        $questions = Question::where('quiz_id', '=', $quizID)->orderBy('number', 'asc')->get();

        foreach ($questions as $question) {
            $question->answers = [
                ['id' => '1', 'title' => 'ek lzezlhgl zei zzeffh zrgle ', 'correct' => true],
                ['id' => '2', 'title' => 'ùer igh ao ziezze rgae', 'correct' => false],
                ['id' => '3', 'title' => 'aef oze jjoz ej ezoj', 'correct' => true],
            ];

            $question->item_left_answers = [
                ['id' => '1', 'title' => 'ek lzezlhgl zei zzeffh zrgle'],
                ['id' => '2', 'title' => 'ùer igh ao ziezze rgae'],
                ['id' => '3', 'title' => 'aef oze jjoz ej ezoj'],
                ['id' => '4', 'title' => 'aef oze jjoz ej ezoj'],
            ];

            $question->item_right_answers = [
                ['id' => '5', 'title' => 'paoeir pfdmj erpùogjmoq jere', 'item' => 2],
                ['id' => '6', 'title' => 'bjpfjpfjg hiohae ogiha eorghoahe ', 'item' => 1],
                ['id' => '7', 'title' => 'oireg jfg aera merg', 'item' => 1],
                ['id' => '8', 'title' => 'pzg pojrgo joaerhig oaehr g', 'item' => 2],
            ];
        }

        return $questions;
    }
}
