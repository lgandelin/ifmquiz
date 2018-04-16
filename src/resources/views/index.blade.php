<h1>Dashboard</h1>
<h2>Questionnaires</h2>

<a href="{{ route('quiz_create') }}">Créer un questionnaire</a>

<ul>
@foreach ($quizs as $quiz)
    <li>
        <h3>{{ $quiz['name'] }}</h3>

        <a href="{{ route('quiz_update', ['uuid' => $quiz['uuid']]) }}">Editer</a>
        <a href="{{ route('quiz_results', ['uuid' => $quiz['uuid']]) }}">Résultats</a>
    </li>
@endforeach
</ul>