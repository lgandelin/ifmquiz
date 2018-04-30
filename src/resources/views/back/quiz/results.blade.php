@extends('ifmquiz::back.master')

@section('main-content')

    <div class="container">
        <h1 class="title">Résultats</h1>
        <a class="button is-text" style="float:right" href="{{ route('quiz_list') }}">Retour</a>
        <h2>{{ $quiz->title }}</h2>

        <div class="field" style="width:30%">
            <input id="user-filter" type="text" class="input" placeholder="Recherche..." />
        </div>

        <table id="user-results" class="table is-bordered">
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
                @foreach ($attempts as $attempt)
                    <tr class="status-{{ $attempt->status }}">
                        <td>{{ $attempt->user->last_name }}</td>
                        <td>{{ $attempt->user->first_name }}</td>
                        <td>{{ $attempt->user->email }}</td>
                        @foreach ($attempt->answers as $answer)
                            <td>{{ $answer }}</td>
                        @endforeach
                        <td>{{ $attempt->result }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="is-selected">
                    <td colspan="3">Moyenne</td>
                    @foreach ($average_by_questions as $i => $question)
                        <td>{{ $question }}</td>
                    @endforeach
                    <td>{{ round($average_result, 1) }}/{{ sizeof($questions) }}</td>
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