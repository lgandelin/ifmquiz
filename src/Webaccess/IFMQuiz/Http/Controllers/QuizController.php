<?php

namespace Webaccess\IFMQuiz\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webaccess\IFMQuiz\Models\Question;

class QuizController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(Request $request) {
        $quizs = [
            ['uuid' => '48a6dc74-8868-4aef-8efa-88491cc71361', 'name' => 'Questionnaire 1'],
            ['uuid' => '4f4b9738-86c9-4dd0-bd3f-694dcf6948cd', 'name' => 'Questionnaire 2'],
            ['uuid' => '2a791a2e-2ebe-49f3-9686-044a60222685', 'name' => 'Questionnaire 3'],
        ];

        return view('ifmquiz::index', [
            'quizs' => $quizs
        ]);
    }

    public function create(Request $request) {
        //return view('ifmquiz::index');
    }

    public function update(Request $request, $quizID) {
        $quiz = ['name' => 'Questionnaire 1'];

        return view('ifmquiz::update', [
            'quiz' => $quiz,
        ]);
    }

    public function results(Request $request, $quizID) {
    }

    public function duplicate(Request $request, $quizID) {
    }

    public function delete(Request $request, $quizID) {
    }

    public function questions(Request $request) {
        $questions = Question::orderBy('number', 'asc')->get();

        return $questions;
    }
}
