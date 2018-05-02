@extends('ifmquiz::back.master')

@section('main-content')
    <div class="container">
        <div class="box header">
            <h1 class="title">{{ $quiz->title }}</h1>
            <a class="button is-text" style="float:right" href="{{ route('quiz_results', ['uuid' => $quiz->id]) }}">Retour</a>
            <h2>{{ $quiz->subtitle }}</h2>
        </div>

        <div class="questions">
            @foreach($questions as $i => $question)
                <div class="box" @if($question->answer->correct === 1) style="background: rgba(36, 204, 92, 0.3);"@endif @if($question->answer->correct === 0) style="background: rgba(204, 15, 25, 0.3);"@endif>
                    <h3>{{ $question->title }}</h3>
                    <p class="description">{{ $question->description }}</p>

                    <div class="control">
                        @if ($question->type == 1)
                            @foreach ($question->items as $j => $item)
                                <label class="radio">
                                    <input disabled type="radio" name="answer_{{ $question->id }}_{{ $item->id }}" @if ($question->answer->items[0]->id == $item->id)checked="checked"@endif /> {{ $item->title }}
                                </label>
                            @endforeach
                        @elseif ($question->type == 2)
                            @foreach ($question->items as $j => $item)
                                <label class="checkbox">
                                    <input disabled type="checkbox" name="answer_{{ $question->id }}_{{ $item->id }}" @if (in_array($item->id, $question->answer->item_ids))checked="checked"@endif /> {{ $item->title }}
                                </label>
                            @endforeach
                        @elseif ($question->type == 3)
                            <div class="columns">
                                <div class="column left">
                                    @foreach ($question->items_left as $j => $item)
                                        <div class="control">
                                            <label>
                                                <span class="n" style="font-weight: bold">{{ $j+1 }}</span> {{ $item->title }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="column right">
                                    @foreach ($question->items_right as $j => $item)
                                        <div class="control">
                                            <label>
                                                <input disabled style="font-weight:bold; width: 2rem;" type="text" name="answer_{{ $question->id }}_{{ $item->id }}" value="{{ $question->answer->items_right[$j]->associated_item }}" /> {{ $item->title }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif ($question->type == 4)
                            <form action="{{ route('quiz_user_answers_valid_answer', ['uuid' => $quiz->id, 'attempt_id' => $attempt_id]) }}" method="post">
                                <div class="field">
                                    <textarea disabled class="textarea" placeholder="Répondre içi" name="textanswer_{{ $question->id }}">{!! $question->answer->text !!}</textarea>
                                </div>

                                <label class="radio">
                                    <strong>Réponse valide ?</strong>
                                    <input type="radio" value="1" name="is_correct" @if ($question->answer->correct == null || $question->answer->correct == 1)checked="checked"@endif /> Oui
                                    <input type="radio" value="0" name="is_correct" @if ($question->answer->correct === 0)checked="checked"@endif /> Non
                                </label>

                                <input type="submit" class="button is-primary" value="Enregistrer" />
                                <input type="hidden" name="question_id" value="{{ $question->id }}" />
                                {{ csrf_field() }}
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <script src="{{ asset('js/dist/front.js') }}"></script>

@endsection