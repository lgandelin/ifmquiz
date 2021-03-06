@extends('ifmquiz::back.master')

@section('main-content')
    
    <div class="header">
        <div class="container">
            <h1 class="title">Dashboard</h1>
            <h2 class="subtitle">Questionnaires</h2>
        </div>
    </div>

    <div class="container dashboard-template page-template">
        <div class="list">
            <div class="item add">
                <div class="create-quiz">
                    <span class="plus">+</span>
                    <span class="create">Créer un<br/> questionnaire</span>
                </div>

                <div class="select-quiz-type">
                    <a href="" class="back">Retour</a>
                    <span class="select-type">Sélectionnez le type<br/> de questionnaire</span>
                    
                    <a class="button" href="{{ route('quiz_create') }}">Examen</a>
                    <a class="button" href="{{ route('quiz_create') }}">Sondage</a>
                </div>
            </div>

            @foreach ($quizs as $quiz)
                <div class="item">
                    <span class="menu-icon"></span>
                    <h3 class="title">{{ $quiz->title }}</h3>
                    <span class="type">Type : Examen</span>
                    <span class="training_date">Date de formation : {{ $quiz->training_date }}</span>
                    <div class="progress-bar">Taux de complétion <span class="is-pulled-right">{{ round(100*$quiz->completion) }}%</span><br/>
                        <progress class="progress" value="{{ $quiz->completion }}" max="1"></progress>
                    </div>

                    <div class="progress-bar">Note moyenne <span class="is-pulled-right">{{ round(100*$quiz->average) }}%</span><br/>
                        <progress class="progress" value="{{ $quiz->average }}" max="1"></progress>
                    </div>
                    <br/>

                    <a class="button" href="{{ route('quiz_update', ['uuid' => $quiz->id]) }}">Editer</a>
                    <a class="button" href="{{ route('quiz_results', ['uuid' => $quiz->id]) }}">Résultats</a>

                    <div class="menu" style="display: none">
                        <a href="{{ route('quiz_duplicate', ['uuid' => $quiz->id]) }}">Dupliquer</a>
                        <a href="{{ route('quiz_delete', ['uuid' => $quiz->id]) }}">Supprimer</a>    
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="notifications" style="width: 300px; top: 0px; right: 0px; @if ($confirmation || $error)display:block @endif">
        <span>
            <div data-id="4" class="notification-wrapper" style="transition: all 300ms;">
                @if ($confirmation)
                    <div class="notification vue-notification success">
                        <div class="notification-title">Informations sauvegardées</div>
                        <div class="notification-content">Les informations ont été sauvegardées avec succès.</div>
                    </div>
                @endif

                @if ($error)
                    <div class="notification vue-notification error">
                        <div class="notification-title">Une erreur est survenue</div>
                        <div class="notification-content">Une erreur est survenue lors de l'enregistrement. Veuillez retenter l'opération</div>
                    </div>
                @endif
            </div>
        </span>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.add .create-quiz').click(function() {
                $(this).parent().toggleClass('selecting');
            });

            $('.add .select-quiz-type .back').click(function(e) {
                $(this).closest('.add').toggleClass('selecting');
                e.preventDefault();
            });

            $('.item .menu-icon').click(function(e) {
                $(this).closest('.item').find('.menu').toggle();
            });
        });
    </script>

@endsection