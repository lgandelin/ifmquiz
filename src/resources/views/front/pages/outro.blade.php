@extends('ifmquiz::front.master')

@section('main-content')
    <div class="container">
        <div class="box header">
            <h1 class="title">{{ $quiz->title }}</h1>
            <h2>{{ $quiz->subtitle }}</h2>

            <div class="outro_text" style="margin-bottom: 1rem; margin-top: 2rem;">{!! $quiz->outro_text !!}</div>
        </div>
    </div>

@endsection