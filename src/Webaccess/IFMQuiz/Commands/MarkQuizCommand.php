<?php

namespace Webaccess\IFMQuiz\Commands;

use DateTime;
use Illuminate\Console\Command;
use Webaccess\IFMQuiz\Models\Answer;
use Webaccess\IFMQuiz\Models\Attempt;
use Webaccess\IFMQuiz\Models\Question;

class MarkQuizCommand extends Command
{
    protected $signature = 'ifmquiz:mark-quiz';

    protected $description = 'Corrige les réponses d\'un utilisateur pour un quiz';

    public function handle()
    {
        $attempts = Attempt::where('status', '=', Attempt::STATUS_COMPLETED)->get();
        foreach ($attempts as $attempt) {
            $questions = Question::where('quiz_id', '=', $attempt->quiz_id)->get();
            foreach ($questions as $question) {
                $question->items = json_decode($question->items);
                $question->items_left = json_decode($question->items_left);
                $question->items_right = json_decode($question->items_right);

                $answer = Answer::where('attempt_id', '=', $attempt->id)->where('question_id', '=', $question->id)->first();

                $answer_items = json_decode($answer->items);
                $answer_items_right = json_decode($answer->items_right);

                $answerIsCorrect = false;

                if ($answer) {
                    switch($question->type) {
                        case 1:
                            $correctItemID = null;
                            foreach ($question->items as $item) {
                                if ($item->correct === true) {
                                    $correctItemID = $item->id;
                                }
                            }

                            if (sizeof($answer_items) == 1 && $answer_items[0]->id == $correctItemID) {
                                $answerIsCorrect = true;
                            }

                            break;
                        case 2:
                            $correctItemIDs = [];
                            foreach ($question->items as $item) {
                                if ($item->correct === true) {
                                    $correctItemIDs[]= $item->id;
                                }
                            }

                            $answerItemIDs = array_column($answer_items, 'id');
                            array_multisort($correctItemIDs);
                            array_multisort($answerItemIDs);

                            if (serialize($answerItemIDs) == serialize($correctItemIDs)) {
                                $answerIsCorrect = true;
                            }

                            break;
                        case 3:
                            $errorFound = false;
                            foreach($answer_items_right as $i => $answer_item) {
                                if ((int) $answer_item->associated_item != $question->items_right[$i]->associated_item) {
                                    $errorFound = true;
                                }
                            }

                            if (!$errorFound) $answerIsCorrect = true;
                            break;
                        case 4:
                            //@TODO
                            break;
                    }
                }

                $answer->correct = $answerIsCorrect;
                $answer->save();
            }

            $attempt->status = Attempt::STATUS_MARKED;
            $attempt->marked_at = new DateTime();
            $attempt->save();
        }
    }
}
