@extends('ifmquiz::back.master')

@section('main-content')

    <div class="header">
        <div class="container">
            <a class="button back-button" href="{{ route('quiz_list') }}">Retour</a>
            <h1 class="title">Gestion des utilisateurs</h1>
        </div>
    </div>

    <div class="container page-template user-template">
        <div class="page-content">

            <table class="table">
                <tr>
                    <td>Nom complet</td>
                    <td>Email</td>
                    <td>Date de création</td>
                    <td>Action</td>
                </tr>

                @foreach ($users as $user)
                    <tr>
                        <td><strong>{{ $user->last_name }} {{ $user->first_name }}</strong></td>
                        <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                        <td>@if ($user->created_at){{ DateTime::createFromFormat('Y-m-d H:i:s', $user->created_at)->format('d/m/Y') }}@endif</td>
                        <td width="20%">
                            <a href="{{ route('user_update', $user->id) }}"><button class="button">Modifier</button></a>
                            <a href="{{ route('user_delete', $user->id) }}"><button class="button">Supprimer</button></a>
                        </td>
                    </tr>
                @endforeach
            </table>

            <a style="margin-bottom:2rem;" href="{{ route('user_create') }}"><button class="button">Créer</button></a>

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

        </div>
        <!-- MAIN CONTENT -->

    </div>
@stop