@extends('ifmquiz::front.master')

@section('main-content')
    <div class="container">
        <div class="box header">
            <h1 class="title">{{ $quiz->title }}</h1>
            <h2>{{ $quiz->subtitle }}</h2>

            <p>Temps : {{  $quiz->time }} min</p>
            <p>{{ sizeof($questions) }} questions</p>
        </div>

        <div class="questions">
            <form action="{{ route('quiz_front_handler', ['uuid' => $quiz->id]) }}" method="post">
                @foreach($questions as $i => $question)
                    <div class="box">
                        <h3>{{ $question->title }}</h3>
                        <p class="description">{{ $question->description }}</p>

                        <div class="control">
                            @if ($question->type == 1)
                                @foreach ($question->items as $j => $item)
                                    <label class="radio">
                                        <input type="radio" name="answer_{{ $question->id }}_{{ $item->id }}" /> {{ $item->title }}
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
                                                    <span class="n" style="font-weight: bold">{{ $j+1 }}</span> {{ $item->title }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="column right">
                                        @foreach ($question->items_right as $j => $item)
                                            <div class="control">
                                                <label>
                                                    <input style="font-weight:bold; width: 2rem;" type="text" name="answer_{{ $question->id }}_{{ $item->id }}" /> {{ $item->title }}
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
                <input type="submit" class="button is-link" value="Valider" />

            </form>

        </div>
    </div>

@endsection