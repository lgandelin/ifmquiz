@extends('ifmquiz::back.master')

@section('main-content')

    <div class="container">
        <h1 class="title">Envoyer le questionnaire</h1>
        <a class="button is-text" style="float:right" href="{{ route('quiz_update', ['uuid' => $quiz->id]) }}">Retour</a>
        <h2>{{ $quiz->title }}</h2>

        <form action="{{ route('quiz_mailing_handler', ['uuid' => $quiz->id]) }}" method="post">
            <div class="field">
                <label class="label">Mailing list</label>
                <div class="control">
                    <textarea class="textarea" placeholder="exemple1@mail.com
exemple2@mail.com
exemple3@mail.com" name="mailing_list"></textarea>
                </div>
            </div>

            {{ csrf_field() }}
            <input type="submit" class="button is-primary" value="Envoyer" />
        </form>
    </div>

@endsection