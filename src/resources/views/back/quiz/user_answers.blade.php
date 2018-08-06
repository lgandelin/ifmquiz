@extends('ifmquiz::back.master')

@section('main-content')
    <div class="front-template user-answers-template">
        <div class="header">
            <div class="container">
                @if ($quiz->header_logo)<img class="header_logo" src="{{ asset($quiz->header_logo) }}" />@endif

                @if ($quiz->time)
                    <span class="time-limit">
                        <img src="{{ asset('img/generic/time.png') }}" width="33" height="33" /> {{  $quiz->time }} min
                    </span>
                @endif
                <span class="questions-number"><img src="{{ asset('img/generic/questions.png') }}" width="22" height="24" /> {{ sizeof($questions) }} questions</span>

                <h1 class="title">{{ $quiz->title }}</h1>
                <h2 class="subtitle">{{ $quiz->subtitle }}</h2>
            </div>
        </div>

        <div class="container">
            <div class="questions">
                @foreach($questions as $i => $question)
                    <div class="question answered @if ($i == 0) first-question @endif @if ($i == sizeof($questions)-1) last-question @endif">
                        <div class="status"></div>
                        <div class="statement">
                            <h3>{{ $question->title }}</h3>
                            <p class="description">{{ $question->description }}</p>

                            @if ($question->type == 4)
                                <form action="{{ route('quiz_user_answers_valid_answer', ['uuid' => $quiz->id, 'attempt_id' => $attempt_id]) }}" method="post">
                                    <label class="radio user-answer-score">
                                        <strong>Note</strong>
                                        <input class="score" type="text" value="@if (isset($question->answer->score)){{ 100*$question->answer->score }}@endif" name="score" /> %

                                        <input type="submit" class="button" value="Enregistrer" />
                                        <input type="hidden" name="question_id" value="{{ $question->id }}" />
                                    </label>
                                    {{ csrf_field() }}
                                </form>
                            @endif
                        </div>

                        <div class="control items">
                            @if ($question->type == 1)
                                @foreach ($question->items as $j => $item)
                                    <label class="radio">
                                        <input disabled type="radio" name="answer_{{ $question->id }}_{{ $item->id }}" @if ($question->answer && $question->answer->items[0]->id == $item->id)checked="checked"@endif /> {{ $item->title }}
                                    </label>
                                @endforeach
                            @elseif ($question->type == 2)
                                @foreach ($question->items as $j => $item)
                                    <label class="checkbox">
                                        <input disabled type="checkbox" name="answer_{{ $question->id }}_{{ $item->id }}" @if ($question->answer && in_array($item->id, $question->answer->item_ids))checked="checked"@endif /> {{ $item->title }}
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
                                                    <input disabled style="font-weight:bold; width: 2rem; padding: 0;" type="text" name="answer_{{ $question->id }}_{{ $item->id }}" @if ($question->answer)value="{{ $question->answer->items_right[$j]->associated_item }}"@endif /> {{ $item->title }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @elseif ($question->type == 4)
                                <div class="field">
                                    <textarea disabled class="textarea" placeholder="Répondre içi" name="textanswer_{{ $question->id }}">@if ($question->answer){!! $question->answer->text !!}@endif</textarea>
                                </div>
                            @elseif ($question->type == 5)
                                <div id="linear-scale-{{ $question->id }}" class="linear-scale" data-min="{{ $question->linear_scale_start_number }}" data-max="{{ $question->linear_scale_end_number }}" data-value="{{ $question->answer->items }}"></div>
                                @if ($question->linear_scale_start_label)<span class="linear_scale_start_label">{{ $question->linear_scale_start_label }}</span>@endif
                                @if ($question->linear_scale_end_label)<span class="linear_scale_end_label">{{ $question->linear_scale_end_label }}</span>@endif
                            @endif
                        </div>
                    </div>
                @endforeach

            </div>

        </div>

        <div class="footer">
            <div class="container">
                @if ($quiz->footer_image)<img id="footer_image" class="footer_image" src="{{ asset($quiz->footer_image) }}" width="50%" alt="" />@endif

                @if ($quiz->footer_text)
                    <div class="footer_text">
                        {!! $quiz->footer_text !!}
                    </div>
                @endif
            </div>
        </div>

        <a class="button back" href="{{ route('quiz_results', ['uuid' => $quiz->id]) }}">Retour</a>

    </div>

    <link rel="stylesheet" href="{{ asset('css/front.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script>
        $(document).ready(function() {
            $(".linear-scale").each(function() {
                $(this).slider({
                    value: $(this).data('value'),
                    min: $(this).data('min'),
                    max: $(this).data('max'),
                    step: 1,
                    range: "min",
                    disabled: true
                }).each(function() {
                    // Get the options for this slider
                    var opt = $(this).data().uiSlider.options;

                    // Get the number of possible values
                    var vals = opt.max - opt.min;

                    // Space out values
                    for (var i = 0; i <= vals; i++) {
                        var el = $('<label>'+(i+opt.min)+'</label>').css('left', (i/vals*100)+'%');
                        $(this).append(el);
                    }
                });
            });
        });
    </script>

@endsection