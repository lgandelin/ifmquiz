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

    <script type="text/x-template" id="quiz-template">
        <div class="quiz">
            <question
                    v-for="(question, index) in $store.state.questions"
                    v-bind:title="question.title"
                    v-bind:question_number="index"
                    v-bind:description="question.description"
                    v-bind:answers="question.answers"
                    v-bind:type="question.type"
                    v-bind:key="question.id"
            ></question>

            <div class="question add-question">
                <textarea class="question-title textarea" placeholder="Ajouter une question" v-model="new_question_title"></textarea>
                <textarea class="question-description textarea" placeholder="Ajouter une description" v-model="new_question_description"></textarea>
                <button class="add-button button" v-on:click="add_question">OK</button>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="question-template">
        <div class="question">
            <button class="button duplicate-button" v-on:click="$store.commit('delete_question', question_number)">S</button>
            <button class="button duplicate-button" v-on:click="$store.commit('duplicate_question', question_number)">D</button>

            <textarea class="question-title textarea">@{{ title }}</textarea>
            <textarea class="question-description textarea">@{{ description }}</textarea>

            <div class="content">
                <div class="type">
                    <label class="label">Type:</label>
                    <select class="select" v-on:change="update_question_type($event, question_number)">
                        <option value="1">Boutons radios</option>
                        <option value="2">Choix multiples</option>
                        <option value="3">Association d'items</option>
                        <option value="4">Réponse simple</option>
                    </select>
                </div>

                <div class="items" v-if="type == 1 || type == 2">
                    <text-answer
                            v-for="(answer, index) in answers"
                            v-bind:title="answer.title"
                            v-bind:answer_number="index"
                            v-bind:question_number="question_number"
                            v-bind:correct="answer.correct"
                            v-bind:key="answer.id"
                    ></text-answer>

                    <div class="field">
                        <label class="label">Ajouter une réponse</label>
                        <input type="text" class="input" placeholder="Ajouter une réponse" v-model="new_answer_title" />
                        <button class="button" v-on:click="add_answer(question_number)">OK</button>
                    </div>
                </div>

                <div class="items" v-if="type == 3">
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

                <div class="items" v-if="type == 4">

                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="text-answer-template">
        <div class="field">
            <label class="label">Réponse @{{ answer_number+1 }}<span :class="{correct: true, checked: correct}" v-on:click="check_answer(answer_number, question_number)">V</span></label>
            <input type="text" class="input" placeholder="" :value="title" />
            <span class="delete" v-on:click="delete_answer(answer_number, question_number)">x</span>
        </div>
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.13/dist/vue.js"></script>
    <script src="https://unpkg.com/vuex@3.0.1/dist/vuex.js"></script>
    <script src="/js/vendor/uuid.js"></script>
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

        .duplicate-button, .delete-button {
            float: right;
            margin-left: 0.5rem;
        }
    </style>
</body>