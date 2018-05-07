@extends('ifmquiz::back.master')

@section('main-content')
    
    <div class="header">
        <div class="container">
            <a class="button back-button" href="{{ route('quiz_list') }}">Retour</a>
            <h1 class="title">Résultats</h1>
            <h2 class="subtitle">{{ $quiz->title }}</h2>
        </div>
    </div>

    <div class="container page-template results-template">
        <div class="search">
            <span class="search-label">Recherche</span>
            <input id="user-filter" type="text" class="search-input" placeholder="Nom / Prénom" />
        </div>

        <table id="user-results" class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Mail</th>
                    @foreach ($questions as $i => $question)
                        <th>Q{{ $i+1 }}</th>
                    @endforeach
                    <th>Résultats</th>
                    <th>Réponses</th>
                </tr>
            <thead>
            <tbody>
                @foreach ($attempts as $attempt)
                    <tr>
                        <td>{{ $attempt->user->last_name }}</td>
                        <td>{{ $attempt->user->first_name }}</td>
                        <td>{{ $attempt->user->email }}</td>
                        @foreach ($attempt->answers as $answer)
                            <td class="center no-border"><span @if ($answer > 0)style="border:1px solid orange"@endif>{{ $answer }}</span></td>
                        @endforeach
                        <td>{{ $attempt->result }}</td>
                        <td class="center"><a href="{{ route('quiz_user_answers', ['quiz_id' => $quiz->id, 'attempt_id' => $attempt->id]) }}">Voir</a></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="right">Moyenne</td>
                    @foreach ($average_by_questions as $i => $question)
                        <td>{{ round($question, 1) }}</td>
                    @endforeach
                    <td>{{ round($average_result, 1) }}/{{ sizeof($questions) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#user-filter').on('keyup', function() {
                apply_filters();
            });
        });

        function apply_filters() {
            $('#user-results  tbody tr').each(function() {
                var show = false;

                //Search bar
                var input_search = $('#user-filter');

                if (input_search.val().length == 0 || $(this).is(':contains("' + input_search.val() + '")'))
                    show = true;

                if (show)
                    $(this).fadeIn();
                else
                    $(this).fadeOut();
            });
        }

        //Overwrites ":contains" jQuery selector
        $.expr[':'].contains = function(a, i, m) {
            return $(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
        };
    </script>

@endsection