@extends('ifmquiz::back.master')

@section('main-content')

    <div class="header">
        <div class="container">
            <a class="button back-button" href="{{ route('quiz_update', ['uuid' => $quiz->id]) }}">Retour</a>
            <h1 class="title">Envoyer le questionnaire</h1>
            <h2 class="subtitle">{{ $quiz->title }}</h2>
        </div>
    </div>

    <div class="container page-template mailing-template">
        <div class="page-content">
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Mailing list</h3>
                </div>
                <div class="block-content">
                    @if (!isset($links))
                    <form action="{{ route('quiz_mailing_handler', ['uuid' => $quiz->id]) }}" method="post">
                        <textarea placeholder="exemple1@mail.com
exemple2@mail.com
exemple3@mail.com" name="mailing_list"></textarea>
                        {{ csrf_field() }}
                        <input type="submit" class="button submit" value="Envoyer" />
                    </form>
                    @else
                        @foreach ($links as $link)
                            <div class="box">
                                {{ $link->email }}<br/>
                                <small><a href="{{ $link->url }}">{{ $link->url }}</a></small>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection