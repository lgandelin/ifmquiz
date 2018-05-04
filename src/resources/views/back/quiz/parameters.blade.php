@extends('ifmquiz::back.master')

@section('main-content')

    <div class="container">
        <h1 class="title">Paramètres</h1>
        <a class="button is-text" style="float:right" href="{{ route('quiz_update', ['uuid' => $quiz->id]) }}">Retour</a>
        <h2>{{ $quiz->title }}</h2>

        <form action="{{ route('quiz_parameters_handler', ['uuid' => $quiz->id]) }}" method="post">
            <div class="field">
                <label class="label">Texte d'introduction</label>
                <div class="control">
                    <textarea class="textarea" placeholder="Texte d'introduction" name="intro_text" id="intro_text_editor">{!! $quiz->intro_text !!}</textarea>
                </div>
            </div>

            <div class="field">
                <label class="label">Texte de remerciement</label>
                <div class="control">
                    <textarea class="textarea" placeholder="Texte de remerciement" name="outro_text" id="outro_text_editor">{!! $quiz->outro_text !!}</textarea>
                </div>
            </div>

            {{ csrf_field() }}
            <input type="submit" class="button is-primary" value="Sauvegarder" />
        </form>
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