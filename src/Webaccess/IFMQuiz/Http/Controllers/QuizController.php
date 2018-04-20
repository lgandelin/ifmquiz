<?php

namespace Webaccess\IFMQuiz\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Ramsey\Uuid\Uuid;
use Webaccess\IFMQuiz\Models\Answer;
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
        $quizs = Quiz::orderBy('created_at', 'desc')->get();

        return view('ifmquiz::back.quiz.index', [
            'quizs' => $quizs
        ]);
    }

    public function create(Request $request) {
        $quiz = new Quiz();
        $quiz->id = Uuid::uuid4()->toString();
        $quiz->title = "Nouveau questionnaire";
        $quiz->subtitle = "Sous-titre";
        $quiz->time = 0;
        $quiz->save();

        return redirect()->route('quiz_update', ['uuid' => $quiz->id]);
    }

    public function update(Request $request, $quizID) {
        $quiz = Quiz::find($quizID);

        return view('ifmquiz::back.quiz.quiz', [
            'quiz' => $quiz,
        ]);
    }

    public function results(Request $request, $quizID) {
        $quiz = Quiz::find($quizID);
        $users = [['id' => 'ddb5d645-af5a-48d3-8ff2-87bf7823772d']];
        $questions = Question::where('quiz_id', '=', $quizID)->orderBy('number', 'asc')->get();

        foreach ($users as $i => $user) {
            $result = 0;
            $users[$i]['answers'] = [];

            foreach ($questions as $question) {
                $answer = Answer::where('question_id', '=', $question->id)->where('user_id', '=', $user['id'])->first();
                $users[$i]['answers'][] = $answer->correct;
                if ($answer->correct) {
                    $result++;
                }
            }
            $users[$i]['result'] = $result . '/' . sizeof($questions);
        }

        return view('ifmquiz::back.quiz.results', [
            'quiz' => $quiz,
            'questions' => $questions,
            'users' => $users,
        ]);
    }

    public function duplicate(Request $request, $quizID) {
        if ($quiz = Quiz::find($quizID)) {
            $questions = Question::where('quiz_id', '=', $quizID)->get();

            $quiz_copy = $quiz->replicate();
            $quiz_copy->id = Uuid::uuid4()->toString();
            $quiz_copy->title = $quiz_copy->title . ' - copie';
            $quiz_copy->save();

            foreach ($questions as $question) {
                $question_copy = $question->replicate();
                $question_copy->id = Uuid::uuid4()->toString();
                $question_copy->quiz_id = $quiz_copy->id;
                $question_copy->save();
            }
        }

        return redirect()->route('quiz_list');
    }

    public function delete(Request $request, $quizID) {
        if ($quiz = Quiz::find($quizID)) {
            $questions = Question::where('quiz_id', '=', $quizID)->delete();

            $quiz->delete();
        }

        return redirect()->route('quiz_list');
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
