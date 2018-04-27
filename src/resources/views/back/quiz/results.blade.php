@extends('ifmquiz::back.master')

@section('main-content')

    <div class="container">
        <h1 class="title">Résultats</h1>
        <a class="button is-text" style="float:right" href="{{ route('quiz_list') }}">Retour</a>
        <h2>{{ $quiz->title }}</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Mail</th>
                    @foreach ($questions as $i => $question)
                        <th>Q{{ $i+1 }}</th>
                    @endforeach
                    <th>Résultats</th>
                </tr>
            <thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->email }}</td>
                        @foreach ($user->answers as $answer)
                            <td>{{ $answer }}</td>
                        @endforeach
                        <td>{{ round($user->result, 1) }}/{{ sizeof($questions) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Moyenne</td>
                    @foreach ($average_by_questions as $i => $question)
                        <td>{{ $question }}</td>
                    @endforeach
                    <td>{{ round($average_result, 1) }}/{{ sizeof($questions) }}</td>
                </tr>
            </tfoot>
        </table>

    </div>

@endsection