@extends('ifmquiz::front.master')

@section('main-content')
    <div class="container">
        <div class="box header">
            <h1 class="title">{{ $quiz->title }}</h1>
            <h2>{{ $quiz->subtitle }}</h2>

            <p>Temps : {{  $quiz->time }} min</p>

            <div class="intro_text" style="margin-bottom: 1rem; margin-top: 2rem;">{!! $quiz->intro_text !!}</div>

            <div class="notification is-danger" style="margin-top: 2rem;">Le compte à rebours démarrera dès que vous aurez cliqué sur le lien <strong>Démarrer l'examen</strong>.</div>

            <form action="{{ route('quiz_front_intro_handler', ['uuid' => $quiz->id]) }}" method="post">
                <div class="field">
                    <label class="label">Email</label>
                    <div class="control">
                        <input type="text" class="input" value="{{ $email }}" disabled="disabled" />
                    </div>
                </div>

                <div class="field">
                    <label class="label">Nom</label>
                    <div class="control">
                        <input type="text" class="input" value="" name="last_name" placeholder="Nom" />
                    </div>
                </div>

                <div class="field">
                    <label class="label">Prénom</label>
                    <div class="control">
                        <input type="text" class="input" value="" name="first_name" placeholder="Prénom" />
                    </div>
                </div>

                <input type="hidden" value="{{ $email }}" name="email" />
                {{ csrf_field() }}
                <input type="submit" class="button is-primary" value="Démarrer l'examen" />
            </form>
        </div>
    </div>

@endsection