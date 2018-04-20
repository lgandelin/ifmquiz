@extends('ifmquiz::back.master')

@section('main-content')

    <div class="container">
        <h1 class="title">Résultats</h1>
        <a class="button is-text" style="float:right" href="{{ route('quiz_list') }}">Retour</a>
        <h2>{{ $quiz->title }}</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>UUID</th>
                    @foreach ($questions as $i => $question)
                        <th>Q{{ $i+1 }}</th>
                    @endforeach
                    <th>Résultats</th>
                </tr>
            <thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user['id'] }}</td>
                        @foreach ($user['answers'] as $answer)
                            <td>{{ $answer }}</td>
                        @endforeach
                        <td>{{ $user['result'] }}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>

@endsection