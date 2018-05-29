@extends('ifmquiz::front.master')

@section('main-content')
    <div class="front-template">
        <div class="header">
            <div class="container">
                @if ($quiz->time)
                    <span class="time-limit">
                        <img src="{{ asset('img/generic/time.png') }}" width="33" height="33" /> {{  $quiz->time }} min

                        @if ($quiz->time > 0)
                            <div id="countdown" class="remaining-time">
                                <countdown :time="{{ $seconds_remaining * 1000 }}" @countdownend="countdownend">
                                <template slot-scope="props">(@{{ props.hours }}h @{{ props.minutes }}min)</template>
                                </countdown>
                            </div>
                        @endif
                    </span>
                @endif
                <span class="questions-number"><img src="{{ asset('img/generic/questions.png') }}" width="22" height="24" /> {{ sizeof($questions) }} questions</span>

                <h1 class="title">{{ $quiz->title }}</h1>
                <h2 class="subtitle">{{ $quiz->subtitle }}</h2>
            </div>
        </div>

        <div class="container">
            <div class="questions">
                <form action="{{ route('quiz_front_handler', ['uuid' => $quiz->id]) }}" method="post">
                    @foreach($questions as $i => $question)
                        <div class="question @if ($i == 0) first-question @endif @if ($i == sizeof($questions)-1) last-question @endif">
                            <div class="status"></div>
                            <div class="statement">
                                <h3>{{ $question->title }}</h3>
                                <p class="description">{{ $question->description }}</p>
                            </div>

                            <div class="control items">
                                @if ($question->type == 1)
                                    @foreach ($question->items as $j => $item)
                                        <label class="radio">
                                            <input type="radio" name="oneanswer_{{ $question->id }}" value="{{ $item->id }}" /> {{ $item->title }}
                                        </label>
                                    @endforeach
                                @elseif ($question->type == 2)
                                    @foreach ($question->items as $j => $item)
                                        <label class="checkbox">
                                            <input type="checkbox" name="answer_{{ $question->id }}_{{ $item->id }}" /> {{ $item->title }}
                                        </label>
                                    @endforeach
                                @elseif ($question->type == 3)
                                    <div class="columns">
                                        <div class="column left">
                                            @foreach ($question->items_left as $j => $item)
                                                <div class="control">
                                                    <label>
                                                        <span class="n">{{ $j+1 }} - </span> {{ $item->title }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="column right">
                                            @foreach ($question->items_right as $j => $item)
                                                <div class="control">
                                                    <label>
                                                        <input type="text" name="answer_{{ $question->id }}_{{ $item->id }}" /> {{ $item->title }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @elseif ($question->type == 4)
                                    <textarea class="textarea" placeholder="Répondre içi" name="textanswer_{{ $question->id }}"></textarea>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    {{ csrf_field() }}
                    <input type="hidden" name="attempt_id" value="{{ $attempt_id }}" />

                    <div class="submit">
                        <input type="submit" class="button" value="Valider" />
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script src="{{ asset('js/dist/front.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.question .items input, .question .items textarea').change(function(e) {
               $(this).closest('.question').addClass('answered');
            });
        });
    </script>

@endsection