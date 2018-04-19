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

    public function quiz(Request $request, $quizID) {
        $quiz = Quiz::find($quizID);

        $questions = Question::where('quiz_id', '=', $quizID)->orderBy('number', 'asc')->get();

        foreach ($questions as $question) {
            $question->items = json_decode($question->items);
            $question->items_left = json_decode($question->items_left);
            $question->items_right = json_decode($question->items_right);
        }

        $quiz->questions = $questions;

        return $quiz;
    }

    public function quiz_handler(Request $request, $quizID) {
        $quiz = Quiz::find($quizID);
        $quiz->title = $request->quiz['title'];
        $quiz->subtitle = $request->quiz['subtitle'];
        $quiz->time = $request->quiz['time'];
        $quiz->save();

        Question::where('quiz_id', '=', $quizID)->delete();
        foreach ($request->quiz['questions'] as $question_number => $question) {
            $q = new Question();
            $q->id = $question['id'];
            $q->description = $question['description'];
            $q->type = $question['type'];
            $q->title = $question['title'];
            $q->number = $question_number+1;
            $q->items = json_encode($question['items']);
            $q->items_left = json_encode($question['items_left']);
            $q->items_right = json_encode($question['items_right']);
            $q->quiz_id = $quizID;
            $q->save();
        }
    }
}
