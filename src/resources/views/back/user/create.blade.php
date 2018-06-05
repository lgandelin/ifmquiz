@extends('ifmquiz::back.master')

@section('main-content')

    <div class="header">
        <div class="container">
            <a class="button back-button" href="{{ route('quiz_list') }}">Retour</a>
            <h1 class="title">Créer un utilisateur</h1>
        </div>
    </div>


    <div class="container page-template user-template">
        <div class="page-content">

            <form action="{{ route('user_create_handler') }}" method="POST">

                <div class="field">
                    <label for="first_name">Prénom</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" />
                </div>

                <div class="field">
                    <label for="last_name">Nom</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" />
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="{{ old('email') }}" required />
                </div>

                <div class="field">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" autocomplete="new-password" value="" />
                </div>

                <div class="field">
                    <label for="password_confirm">Confirmation du mot de passe</label>
                    <input type="password" name="password_confirm" id="password_confirm" autocomplete="new-password" value="" />
                </div>

                @if (isset($error))
                    <div class="error">
                        {{ $error }}
                    </div>
                @endif

                @if (isset($confirmation))
                    <div class="success">
                        {{ $confirmation }}
                    </div>
                @endif

                <div class="submit-container" style="overflow: hidden; margin-top: 3.5rem">
                    <input class="button" style="float:right" type="submit" value="Valider" />

                    <a class="button" href="{{ route('user_list') }}">Retour</a>
                </div>

                {{ csrf_field() }}
            </form>

        </div>

    </div>
@stop