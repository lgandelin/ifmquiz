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
    <script src="https://cdn.ckeditor.com/ckeditor5/10.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#intro_text_editor'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
                .create(document.querySelector('#outro_text_editor'))
                .then(editor => {
            console.log(editor);
        })
        .catch(error => {
            console.error(error);
        });
    </script>

@endsection