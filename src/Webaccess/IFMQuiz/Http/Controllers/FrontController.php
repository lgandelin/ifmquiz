<?php

namespace Webaccess\IFMQuiz\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Ramsey\Uuid\Uuid;
use Webaccess\IFMQuiz\Models\Answer;
use Webaccess\IFMQuiz\Models\Attempt;
use Webaccess\IFMQuiz\Models\Question;
use Webaccess\IFMQuiz\Models\Quiz;
use Webaccess\IFMQuiz\Models\User;

class FrontController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function quiz(Request $request, $quizID)
    {
        $quiz = Quiz::find($quizID);
        $questions = Question::where('quiz_id', '=', $quizID)->orderBy('number', 'asc')->get();

        foreach ($questions as $question) {
            $question->items = json_decode($question->items);
            $question->items_left = json_decode($question->items_left);
            $question->items_right = json_decode($question->items_right);
        }

        $seconds_remaining = null;
        if ($quiz->time > 0) {
            $attemptID = $request->attempt_id;
            $attempt = Attempt::find($attemptID);

            if ($attempt->ends_at) {
                $endDate = new DateTime($attempt->ends_at);
                $seconds_remaining = $endDate->getTimestamp() - time();
            }
        }

        return view('ifmquiz::front.pages.quiz', [
            'quiz' => $quiz,
            'questions' => $questions,
            'attempt_id' => $request->attempt_id,
            'seconds_remaining' => $seconds_remaining,
        ]);
    }

    public function quiz_handler(Request $request, $quizID)
    {
        $attemptID = $request->attempt_id;
        $attempt = Attempt::find($attemptID);

        //Check that the time is not elapsed
        if ($attempt->ends_at) {
            if (new DateTime() > new DateTime($attempt->ends_at)) {
                return;
            }
        }

        //Update status / date of the attempt
        $attempt->completed_at = new DateTime();
        $attempt->status = Attempt::STATUS_COMPLETED;
        $attempt->save();

        $answers = [];
        foreach($request->all() as $key => $value) {
            if (preg_match('/textanswer_/', $key)) {
                $questionID = str_replace('textanswer_', '', $key);
                $answers[$questionID][]= $value;
            } elseif (preg_match('/answer_/', $key)) {
                $key = str_replace('answer_', '', $key);
                list($questionID, $itemID) = explode('_', $key);

                $answer = ['id' => $itemID];
                if ($value != 'on') {
                    $answer['associated_item'] = (int) $value;
                }
                $answers[$questionID][]= $answer;
            }
        }

        foreach ($answers as $questionID => $question_answers) {
            $question = Question::find($questionID);

            $answer = new Answer();
            $answer->id = Uuid::uuid4()->toString();
            $answer->question_id = $questionID;
            $answer->attempt_id = $attemptID;
            $answer->created_at = new DateTime();
            $answer->updated_at = new DateTime();

            switch($question->type) {
                case 1:
                    $answer->items = json_encode($answers[$questionID]);
                    break;
                case 2:
                    $answer->items = json_encode($answers[$questionID]);
                    break;
                case 3:
                    $answer->items_right = json_encode($answers[$questionID]);
                    break;
                case 4:
                    $answer->items = $question_answers[0];
                    break;
            }
            $answer->save();
        }

        return redirect()->route('quiz_front_outro', ['uuid' => $quizID]);
    }

    public function quiz_intro(Request $request, $quizID)
    {
        $quiz = Quiz::find($quizID);
        $attemptID = $request->attempt_id;
        $error = null;

        //Check that the attempt is valid
        if (!$attempt = Attempt::find($attemptID)) {
            $error = 'Le lien pour accéder à votre questionnaire a expiré.';
        }

        //Check that the user has not already answered the quiz
        elseif ($attempt->status != Attempt::STATUS_SENT) {
            $error = 'Vous avez déjà rempli le questionnaire.';
        }

        $user = ($attempt) ? User::find($attempt->user_id) : null;

        return view('ifmquiz::front.pages.intro', [
            'quiz' => $quiz,
            'user' => $user,
            'attempt_id' => $attemptID,
            'error' => $error,
        ]);
    }

    public function quiz_front_intro_handler(Request $request, $quizID)
    {
        $attemptID = $request->attempt_id;
        $quiz = Quiz::find($quizID);

        //Check that the attempt is valid
        if (!$attempt = Attempt::find($attemptID)) {
            return;
        }

        //Check that the user has not already answered the quiz
        elseif ($attempt->status != Attempt::STATUS_SENT) {
            return;
        }

        //Update user with lastname / firstname
        $user = User::find($attempt->user_id);
        $user->last_name = $request->last_name;
        $user->first_name = $request->first_name;
        $user->save();

        //Update attempt start date
        $attempt = Attempt::find($attemptID);
        $attempt->started_at = new DateTime();

        if ($quiz->time > 0 && is_int($quiz->time)) {
            $endTime = clone $attempt->started_at;
            $endTime->add(new \DateInterval('PT' . $quiz->time . 'M'));
            $attempt->ends_at = $endTime;
        }
        $attempt->save();

        return redirect()->route('quiz_front', ['uuid' => $quizID, 'attempt_id' => $attemptID]);
    }

    public function quiz_outro(Request $request, $quizID)
    {
        $quiz = Quiz::find($quizID);

        return view('ifmquiz::front.pages.outro', [
            'quiz' => $quiz,
        ]);
    }
}