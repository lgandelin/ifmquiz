<?php

namespace Webaccess\IFMQuiz\Http\Controllers;

use Mail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Ramsey\Uuid\Uuid;
use Webaccess\IFMQuiz\Models\Answer;
use Webaccess\IFMQuiz\Models\Attempt;
use Webaccess\IFMQuiz\Models\Question;
use Webaccess\IFMQuiz\Models\Quiz;
use Webaccess\IFMQuiz\Models\User;

class QuizController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(Request $request) {
        $quizs = Quiz::orderBy('created_at', 'desc')->get();

        foreach ($quizs as $quiz) {
            $validated_attempts = Attempt::where('quiz_id', '=', $quiz->id)->where('completed_at', '=', null)->get()->count();
            $attempts = Attempt::where('quiz_id', '=', $quiz->id)->get()->count();

            $quiz->completion = ($attempts > 0) ? ($validated_attempts / $attempts) : 0;

            $quiz->average = 'N/A';
        }

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
        $questions = Question::where('quiz_id', '=', $quizID)->orderBy('number', 'asc')->get();

        $totalByQuestions = [];
        $totalResults = 0;
        foreach ($questions as $question) {
            $totalByQuestions[$question->id] = 0;
        }

        $attempts = Attempt::where('quiz_id', '=', $quizID)->get();

        $users = [];
        foreach ($attempts as $attempt) {
            $result = 0;
            $answers = [];

            foreach ($questions as $question) {
                $answer = Answer::where('question_id', '=', $question->id)->where('attempt_id', '=', $attempt->id)->first();

                if (isset($answer->correct)) {
                    $answers[] = $answer->correct;

                    if ($answer->correct) {
                        $result++;
                        $totalByQuestions[$question->id]++;
                    }
                } else {
                    $answers[] = 'N/A';
                }
            }

            $user = User::find($attempt->user_id);
            $user->answers = $answers;
            $user->result = $result;
            $totalResults += $result;
            $users[]= $user;
        }

        $averageResult = (sizeof($users) > 0) ? ($totalResults / sizeof($users)) : 0;
        $averageByQuestions = [];

        foreach ($questions as $question) {
            $averageByQuestions[$question->id] = (sizeof($users) > 0) ? ($totalByQuestions[$question->id] / sizeof($users)) : 0;
        }

        return view('ifmquiz::back.quiz.results', [
            'quiz' => $quiz,
            'questions' => $questions,
            'users' => $users,
            'average_result' => $averageResult,
            'average_by_questions' => $averageByQuestions,
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

    public function parameters(Request $request, $quizID) {
        $quiz = Quiz::find($quizID);

        return view('ifmquiz::back.quiz.parameters', [
            'quiz' => $quiz,
        ]);
    }

    public function parameters_handler(Request $request, $quizID) {
        $quiz = Quiz::find($quizID);

        $quiz->intro_text = $request->intro_text;
        $quiz->outro_text = $request->outro_text;
        $quiz->save();

        return redirect()->route('quiz_parameters', ['uuid' => $quizID]);
    }

    public function mailing(Request $request, $quizID) {
        $quiz = Quiz::find($quizID);

        return view('ifmquiz::back.quiz.mailing', [
            'quiz' => $quiz,
        ]);
    }

    public function mailing_handler(Request $request, $quizID) {
        $quiz = Quiz::find($quizID);
        $url = route('quiz_front_intro', ['uuid' => $quizID]);

        $emails = explode(PHP_EOL, $request->mailing_list);
        foreach($emails as $i => $email) {
            $email = trim(preg_replace('/\r/', '', $email));

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                //Create user if not existing
                if (!$user = User::where('email', $email)->first()) {
                    $user = new User();
                    $user->id = Uuid::uuid4()->toString();
                    $user->email = $email;
                    $user->save();
                }

                //Create attempt
                $attempt = new Attempt();
                $attempt->id = Uuid::uuid4()->toString();
                $attempt->user_id = $user->id;
                $attempt->quiz_id = $quizID;
                $attempt->save();

                Mail::send('ifmquiz::emails.quiz', ['url' => $url . '?attempt_id=' . $attempt->id], function ($m) use ($url, $user, $quiz) {
                    $m->to($user->email)->subject(sprintf('[%s] Le lien pour accéder à votre examen', $quiz->title));
                });
            }
        }

        return redirect()->route('quiz_mailing', ['uuid' => $quizID]);
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
