@extends('ifmquiz::front.master')

@section('main-content')
    <div class="front-template intro-template">
        <div class="header">
            <div class="container">
                @if ($quiz->time)
                    <span class="time-limit">
                        <img src="{{ asset('img/generic/time.png') }}" width="33" height="33" /> {{  $quiz->time }} min
                    </span>
                @endif
                <span class="questions-number"><img src="{{ asset('img/generic/questions.png') }}" width="22" height="24" /> {{ $questions_number }} questions</span>

                <h1 class="title">{{ $quiz->title }}</h1>
                <h2 class="subtitle">{{ $quiz->subtitle }}</h2>
            </div>
        </div>

        <div class="body">
            <div class="container" style="margin-bottom: 1rem; margin-top: 2rem;">
                <div class="intro_text">{!! $quiz->intro_text !!}</div>
            </div>

            <div class="container">
                @if (isset($error) && $error)
                    <div class="notification is-danger">{{ $error }}</div>
                @else
                    <div class="notification is-danger" style="margin-top: 2rem;">Le compte à rebours démarrera dès que vous aurez cliqué sur le bouton <strong>Démarrer</strong>.</div>

                    <form action="{{ route('quiz_front_intro_handler', ['uuid' => $quiz->id]) }}" method="post">
                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control">
                                <input type="text" class="input" value="{{ $user->email }}" disabled="disabled" />
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Nom</label>
                            <div class="control">
                                <input required type="text" class="input" value="{{ $user->last_name }}" name="last_name" placeholder="Nom" />
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Prénom</label>
                            <div class="control">
                                <input required type="text" class="input" value="{{ $user->first_name }}" name="first_name" placeholder="Prénom" />
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Société</label>
                            <div class="control">
                                <input required type="text" class="input" value="{{ $user->company }}" name="company" placeholder="Société" />
                            </div>
                        </div>

                        {{ csrf_field() }}
                        <input type="hidden" value="{{ $attempt_id }}" name="attempt_id" />

                        <div class="submit">
                            <input type="submit" class="button" value="Démarrer" />
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

@endsection