@extends('ifmquiz::back.master')

@section('main-content')

    <div class="container">
        <h1 class="title">Dashboard</h1>

        <a class="button is-primary" style="margin-bottom: 2rem;" href="{{ route('quiz_create') }}">Créer un questionnaire</a>

        <div class="tiles">
            @foreach ($quizs as $quiz)
                <div class="tile box">
                    <div>
                        <h3 class="title">{{ $quiz->title }}</h3>

                        <a class="button is-primary" href="{{ route('quiz_update', ['uuid' => $quiz->id]) }}">Editer</a>
                        <a class="button is-warning" href="{{ route('quiz_results', ['uuid' => $quiz->id]) }}">Résultats</a>
                        <a class="button is-link" href="{{ route('quiz_duplicate', ['uuid' => $quiz->id]) }}">Dupliquer</a>
                        <a class="button is-danger" href="{{ route('quiz_delete', ['uuid' => $quiz->id]) }}">Supprimer</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection