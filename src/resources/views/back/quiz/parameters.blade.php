@extends('ifmquiz::back.master')

@section('main-content')
    
    <div class="header">
        <div class="container">
            <a class="button back-button" href="{{ route('quiz_update', ['uuid' => $quiz->id]) }}">Retour</a>
            <h1 class="title">Paramètres</h1>
            <h2 class="subtitle">{{ $quiz->title }}</h2>
        </div>
    </div>

    <div class="container">
        <div class="container page-template parameters-template">
            <div class="page-content">
                <form action="{{ route('quiz_parameters_handler', ['uuid' => $quiz->id]) }}" method="post">

                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Texte d'introduction</h3>
                        </div>
                        
                        <div class="block-content">
                            <textarea class="textarea" placeholder="Texte d'introduction" name="intro_text" id="intro_text_editor">{!! $quiz->intro_text !!}</textarea>
                        </div>
                    </div>

                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Texte de remerciement</h3>
                        </div>

                        <div class="block-content">
                            <textarea class="textarea" placeholder="Texte de remerciement" name="outro_text" id="outro_text_editor">{!! $quiz->outro_text !!}</textarea>
                        </div>

                        {{ csrf_field() }}
                        <input type="submit" class="button submit" value="Sauvegarder" />
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="notifications" style="width: 300px; top: 0px; right: 0px; @if ($confirmation || $error)display:block @endif">
        <span>
            <div data-id="4" class="notification-wrapper" style="transition: all 300ms;">
                @if ($confirmation)
                    <div class="notification vue-notification success">
                        <div class="notification-title">Informations sauvegardées</div>
                        <div class="notification-content">Les informations du questionnaire ont été sauvegardées avec succès.</div>
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

    <script src="https://cdn.ckeditor.com/ckeditor5/10.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#intro_text_editor'))
            .then(editor => {
            })
            .catch(error => {
            });

        ClassicEditor
                .create(document.querySelector('#outro_text_editor'))
                .then(editor => {
        })
        .catch(error => {
        });
    </script>

@endsection