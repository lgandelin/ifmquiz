@extends('ifmquiz::back.master')

@section('main-content')

    <div class="container">
        <h1 class="title">Dashboard</h1>

        <a href="{{ route('quiz_create') }}">Créer un questionnaire</a>

        <ul>
        @foreach ($quizs as $quiz)
            <li>
                <h3>{{ $quiz->title }}</h3>

                <a href="{{ route('quiz_update', ['uuid' => $quiz->id]) }}">Editer</a>
                <a href="{{ route('quiz_results', ['uuid' => $quiz->id]) }}">Résultats</a>
            </li>
        @endforeach
        </ul>
    </div>

@endsection