@extends('ifmquiz::front.master')

@section('main-content')
    <div class="front-template intro-template">
        <div class="header">
            <div class="container">
                <h1 class="title">{{ $quiz->title }}</h1>
                <h2 class="subtitle">{{ $quiz->subtitle }}</h2>
            </div>
        </div>

        <div class="body">
            <div class="container" style="margin-bottom: 1rem; margin-top: 2rem;">
                <div class="intro_text">{!! $quiz->outro_text !!}</div>
            </div>
        </div>
    </div>

@endsection