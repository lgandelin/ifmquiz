<?php

namespace Webaccess\IFMQuiz\Http\Controllers;

use DateTime;
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
            //Calculate completion
            $completed_attempts = Attempt::where('quiz_id', '=', $quiz->id)->where('status', '>=', Attempt::STATUS_COMPLETED)->get();
            $attempts = Attempt::where('quiz_id', '=', $quiz->id)->get();
            $quiz->completion = ($attempts->count() > 0) ? ($completed_attempts->count() / $attempts->count()) : 0;

            //Calculate average
            $totalResults = 0;
            $totalPoints = 0;
            $questions = Question::where('quiz_id', '=', $quiz->id)->orderBy('number', 'asc')->get();
            $marked_attempts = Attempt::where('quiz_id', '=', $quiz->id)->where('status', '=', Attempt::STATUS_MARKED)->get();
            foreach ($marked_attempts as $attempt) {
                $result = 0;

                $totalPoints = 0;
                foreach ($questions as $question) {
                    $answer = Answer::where('question_id', '=', $question->id)->where('attempt_id', '=', $attempt->id)->first();
                    if (isset($answer->score) && $answer->score) {
                        $result += $answer->score * $question->factor;
                    }
                    $totalPoints += $question->factor;
                }
                $totalResults += $result;
            }

            $average_score = (sizeof($marked_attempts) > 0) ? ($totalResults / sizeof($marked_attempts)) : 0;
            $quiz->average = ($totalPoints > 0) ? ($average_score / $totalPoints) : 0;

            $quiz->training_date = ($quiz->training_date != null) ? DateTime::createFromFormat('Y-m-d', $quiz->training_date)->format('d/m/Y') : 'N/A';
        }

        return view('ifmquiz::back.quiz.index', [
            'quizs' => $quizs,
            'confirmation' => $request->session()->get('confirmation'),
            'error' => $request->session()->get('error'),
        ]);
    }

    public function create(Request $request, $type) {
        $quiz = new Quiz();
        $quiz->id = Uuid::uuid4()->toString();
        $quiz->title = "Nouveau questionnaire";
        $quiz->subtitle = "Sous-titre";
        $quiz->type = (in_array($type, [Quiz::EXAMEN_TYPE, Quiz::SONDAGE_TYPE]) ? $type : Quiz::EXAMEN_TYPE);
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
        $completedQuizs = 0;

        foreach ($questions as $question) {
            $totalByQuestions[$question->id] = 0;
        }

        $attempts = Attempt::where('quiz_id', '=', $quizID)->get();

        $totalPoints = 0;
        foreach ($attempts as $attempt) {
            $result = 0;
            $answers = [];

            $totalPoints = 0;
            foreach ($questions as $question) {
                $answer = Answer::where('question_id', '=', $question->id)->where('attempt_id', '=', $attempt->id)->first();
                if (!$answer) {
                    $result = 'N/A';
                }

                $totalPoints += $question->factor;


                if (isset($answer->score)) {
                    $answers[] = $answer->score * $question->factor;

                    if ($answer->score) {
                        $result += $answer->score * $question->factor;
                        $totalByQuestions[$question->id]+= $answer->score * $question->factor;
                    }
                } else {
                    $answers[] = 'N/A';
                }
            }

            $user = User::find($attempt->user_id);
            $attempt->answers = $answers;
            if ($attempt->status == Attempt::STATUS_MARKED) {
                if (is_numeric($result)) $totalResults += $result;
                $attempt->result = round($result, 1) . '/' . $totalPoints;
                $completedQuizs++;
            } else {
                $attempt->result = 'N/A';
            }
            $attempt->user = $user;
        }

        $averageResult = ($completedQuizs > 0) ? ($totalResults / $completedQuizs) : 0;
        $averageByQuestions = [];

        foreach ($questions as $question) {
            $averageByQuestions[$question->id] = ($completedQuizs > 0) ? ($totalByQuestions[$question->id] / $completedQuizs) : 0;
        }

        return view('ifmquiz::back.quiz.results', [
            'quiz' => $quiz,
            'questions' => $questions,
            'attempts' => $attempts,
            'average_result' => $averageResult,
            'average_by_questions' => $averageByQuestions,
            'total_points' => $totalPoints,
        ]);
    }

    public function user_answers(Request $request, $quizID, $attemptID) {
        $quiz = Quiz::find($quizID);

        $questions = Question::where('quiz_id', '=', $quizID)->orderBy('number', 'asc')->get();
        foreach ($questions as $question) {
            $question->items = json_decode($question->items);
            $question->items_left = json_decode($question->items_left);
            $question->items_right = json_decode($question->items_right);

            if ($question->answer = Answer::where('attempt_id', '=', $attemptID)->where('question_id', '=', $question->id)->first()) {
                $question->answer->items = json_decode($question->answer->items);
                $question->answer->items_right = json_decode($question->answer->items_right);

                switch($question->type) {
                    case 2:
                        $answerItemIDs = array_column($question->answer->items, 'id');
                        $question->answer->item_ids = $answerItemIDs;
                        break;
                    case 4:
                        $question->answer->text = ($question->answer->items) ? $question->answer->items[0]->text : '';
                        break;
                }
            }
        }

        return view('ifmquiz::back.quiz.user_answers', [
            'quiz' => $quiz,
            'attempt_id' => $attemptID,
            'questions' => $questions,
        ]);
    }

    public function user_answers_valid_answer(Request $request, $quizID, $attemptID) {
        if ($answer = Answer::where('attempt_id', '=', $attemptID)->where('question_id', '=', $request->question_id)->first()) {
            $answer->score = $request->score / 100;
            $answer->save();
        }

        return redirect()->route('quiz_user_answers', ['uuid' => $quizID, 'attempt_uuid' => $attemptID]);
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

            $request->session()->flash('confirmation', true);
        }

        return redirect()->route('quiz_list');
    }

    public function delete(Request $request, $quizID) {
        if ($quiz = Quiz::find($quizID)) {
            $questions = Question::where('quiz_id', '=', $quizID)->delete();

            if ($quiz->delete()) {
                $request->session()->flash('confirmation', true);
            } else {
                $request->session()->flash('error', true);
            }
        }

        return redirect()->route('quiz_list');
    }

    public function parameters(Request $request, $quizID) {
        $quiz = Quiz::find($quizID);

        return view('ifmquiz::back.quiz.parameters', [
            'quiz' => $quiz,
            'confirmation' => $request->session()->get('confirmation'),
            'error' => $request->session()->get('error'),
        ]);
    }

    public function parameters_handler(Request $request, $quizID) {
        $quiz = Quiz::find($quizID);

        $quiz->intro_text = $request->intro_text;
        $quiz->outro_text = $request->outro_text;

        if ($quiz->save()) {
            $request->session()->flash('confirmation', true);
        } else {
            $request->session()->flash('error', true);
        }

        return redirect()->route('quiz_parameters', ['uuid' => $quizID]);
    }

    public function mailing(Request $request, $quizID) {
        $quiz = Quiz::find($quizID);

        return view('ifmquiz::back.quiz.mailing', [
            'quiz' => $quiz,
        ]);
    }

    /*public function mailing_handler(Request $request, $quizID) {
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
    }*/

    public function mailing_handler(Request $request, $quizID) {
        $quiz = Quiz::find($quizID);
        $url = route('quiz_front_intro', ['uuid' => $quizID]);
        $links = [];
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

                $links[]= (object)array('url' => $url . '?attempt_id=' . $attempt->id, 'email' => $email);
            }
        }

        return view('ifmquiz::back.quiz.mailing', [
            'quiz' => $quiz,
            'links' => $links,
        ]);
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
        $quiz->header_logo = $request->quiz['header_logo'];
        $quiz->footer_image = $request->quiz['footer_image'];
        $quiz->footer_text = $request->quiz['footer_text'];
        $quiz->training_date = (new DateTime($request->quiz['training_date']))->format('Y-m-d');
        $quiz->save();

        Question::where('quiz_id', '=', $quizID)->delete();

        foreach ($request->quiz['questions'] as $question_number => $question) {
            $q = new Question();
            $q->id = $question['id'];
            $q->description = $question['description'];
            $q->type = $question['type'];
            $q->factor = $question['factor'];
            $q->title = $question['title'];
            $q->number = $question_number+1;
            $q->items = json_encode($question['items']);
            $q->items_left = json_encode($question['items_left']);
            $q->items_right = json_encode($question['items_right']);
            $q->linear_scale_start_number = $question['linear_scale_start_number'];
            $q->linear_scale_end_number = $question['linear_scale_end_number'];
            $q->linear_scale_start_label = $question['linear_scale_start_label'];
            $q->linear_scale_end_label = $question['linear_scale_end_label'];
            $q->quiz_id = $quizID;
            $q->save();
        }
    }

    public function quiz_upload_image(Request $request, $quizID, $imageType) {
        $quiz = Quiz::find($quizID);

        $imageFolder = public_path('uploads/' . $quizID);
        @mkdir($imageFolder);

        $image = $request->get($imageType);

        if ($imageName = self::uploadImage($request->$imageType, $imageFolder, $request->file($imageType)->extension())) {
            $quiz->$imageType = 'uploads/' . $quizID . '/' . $imageName;
            $quiz->save();

            return response()->json([
                'image' => asset($quiz->$imageType)
            ]);
        }
    }

    public function quiz_delete_image(Request $request, $quizID, $imageType) {
        $quiz = Quiz::find($quizID);
        $quiz->$imageType = null;
        $quiz->save();

        return response()->json([
            'success' => true
        ]);
    }

    public static function uploadImage($imageFile, $folder, $extension) {
        $imageName = $imageFile->getClientOriginalName() . '.' . $extension;

        if (!is_dir($folder)) {
            @mkdir($folder);
        }

        if ($imageFile->move($folder, $imageName)) {
            return $imageName . '?t=' . time();
        }

        return false;
    }
}
