<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.0/css/bulma.css" />
</head>
<body>

    <div class="container" id="quiz">
        <quiz></quiz>
    </div>

    <script type="text/x-template" id="question-template">
        <div class="question">
            <textarea class="question-title textarea">@{{ title }}</textarea>
            <textarea class="question-description textarea">@{{ description }}</textarea>

            <div class="content">
                <div class="type">
                    <label class="label">Type:</label>
                    <select class="select" v-model="mutable_type">
                        <option value="1">Boutons radios</option>
                        <option value="2">Choix multiples</option>
                        <option value="3">Association d'items</option>
                        <option value="4">Réponse simple</option>
                    </select>
                </div>

                <div class="items" v-if="mutable_type == 1 || mutable_type == 2">
                    <text-answer
                            v-for="(answer, index) in mutable_answers"
                            v-bind:title="answer.title"
                            v-bind:number="index"
                            v-bind:correct="answer.correct"
                            v-bind:key="answer.id"
                            v-on:delete_answer="delete_answer"
                            v-on:check_answer="check_answer"
                    ></text-answer>

                    <div class="field">
                        <label class="label">Ajouter une réponse</label>
                        <input type="text" class="input" placeholder="Ajouter une réponse" v-model="new_answer_title" />
                        <button class="button" v-on:click="valid_add_answer">OK</button>
                    </div>
                </div>

                <div class="items" v-if="mutable_type == 3">
                    <div class="div-50">
                        <div class="field">
                            <label class="label">Nom d'item 1</label>
                            <input type="text" class="input" placeholder="Nom d'item 1" />
                        </div>

                        <div class="field">
                            <label class="label">Nom d'item 2</label>
                            <input type="text" class="input" placeholder="Nom d'item 2" />
                        </div>

                        <div class="field">
                            <label class="label">Ajouter un item</label>
                            <input type="text" class="input" placeholder="Ajouter un item" />
                        </div>
                    </div>
                    <div class="div-50">
                        <div class="field">
                            <label class="label">Nom d'item</label>
                            <input type="text" class="input" placeholder="Nom d'item 1" />
                            <input class="input" type="number" value="1" />
                        </div>

                        <div class="field">
                            <label class="label">Nom d'item</label>
                            <input type="text" class="input" placeholder="Nom d'item 1" />
                            <input class="input" type="number" value="2" />
                        </div>

                        <div class="field">
                            <label class="label">Nom d'item</label>
                            <input type="text" class="input" placeholder="Nom d'item 1" />
                            <input class="input" type="number" value="2" />
                        </div>

                        <div class="field">
                            <label class="label">Ajouter une réponse</label>
                            <input type="text" class="input" placeholder="Ajouter un item" />
                        </div>
                    </div>
                </div>

                <div class="items" v-if="mutable_type == 4">

                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="quiz-template">
        <div class="quiz">
            <question
                    v-for="(question, index) in questions"
                    v-bind:title="question.title"
                    v-bind:description="question.description"
                    v-bind:answers="[{id: '1', title: 'Lorem ipsum', correct: true}, {id: '2', title: 'Dolor sit amet', correct: false}, {id: '3', title: 'Last answser', correct: true }]"
                    v-bind:type="question.type"
                    v-bind:key="question.id"
            ></question>

            <div class="question add-question">
                <textarea class="question-title textarea" placeholder="Ajouter une question" v-model="new_question_title"></textarea>
                <textarea class="question-description textarea" placeholder="Ajouter une description" v-model="new_question_description"></textarea>
                <button class="add-button button" @click="valid_add_question">OK</button>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="text-answer-template">
        <div class="field">
            <label class="label">Réponse @{{ number+1 }}<span :class="{correct: true, checked: mutable_correct}" v-on:click="check(number)">V</span></label>
            <input type="text" class="input" placeholder="" :value="title" />
            <span class="delete" v-on:click="delete_answer(number)">x</span>
        </div>
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.13/dist/vue.js"></script>
    <script src="/js/quiz.js"></script>

    <style type="text/css">
        .question {
            margin-bottom: 2rem;
            padding: 2rem;

            background: #ccc;
        }

        .question-title {
            width: 250px;
            min-width: auto;
            display: inline-block;
            height: 50px;
        }

        .question-description {
            display: inline-block;
            min-width: auto;
            width: 850px;
            height: 50px;
        }

        .add-question {
            background: #eee;
        }

        .add-button {
            display: inline-block;
            vertical-align: top;
        }

        .type {
            vertical-align: top;
            display: inline-block;
            width: 250px;
        }

        .items {
            display: inline-block;
            width: 850px;
        }
        
        .content {
            margin-top: 3rem;
        }

        .correct {
            float: right;

            cursor: pointer;
        }

        .correct.checked {
            color: orange;
        }

        .input {
            width: 97%;
        }

        .div-50 {
            width: 50%;
            float: left;
        }

        .div-50 .input {
            width: 80%;
        }

        .div-50 input[type="number"] {
            width: 50px;
        }
    </style>
</body>