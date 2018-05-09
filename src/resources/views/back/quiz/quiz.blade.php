@extends('ifmquiz::back.master')

@section('main-content')
        <div id="quiz" class="quiz-template page-template">
            <div class="top-bar">
                <div class="container">
                    <a class="is-pulled-right icon mailing" href="{{ route('quiz_mailing', ['uuid' => $quiz->id]) }}">Mailing</a>
                    <a class="is-pulled-right icon settings" href="{{ route('quiz_parameters', ['uuid' => $quiz->id]) }}">Paramètres</a>
                    <a class="is-pulled-right button" href="{{ route('quiz_list') }}">Retour</a>
                </div>
            </div>
            
            <quiz></quiz>
            <input type="hidden" id="quiz_id" value="{{ $quiz->id }}" />
        </div>

    <script type="text/x-template" id="quiz-template">
        <div class="quiz">
            <div class="header">
                <div class="container is-clearfix">
                    <span class="time" style="float:right">
                        <span class="number" v-text="$store.state.quiz.time" v-on:click="updating_quiz_time = true" v-show="!updating_quiz_time"></span>
                        <input type="text" class="number updating_time" v-show="updating_quiz_time" v-model="$store.state.quiz.time" v-on:blur="updating_quiz_time = false" />
                    min</span>
                    <span class="questions-number"><span class="number" v-text="$store.state.quiz.questions.length"></span> questions</span>
                    <h1 class="title" v-text="$store.state.quiz.title" v-on:click="updating_quiz_title = true" v-show="!updating_quiz_title"></h1>
                    <input type="text" class="title is-spaced updating_title" v-show="updating_quiz_title" v-model="$store.state.quiz.title" v-on:blur="updating_quiz_title = false" />
                    <h2 class="subtitle" v-text="$store.state.quiz.subtitle" v-on:click="updating_quiz_subtitle = true" v-show="!updating_quiz_subtitle"></h2>
                    <input type="text" class="subtitle updating_subtitle" v-show="updating_quiz_subtitle" v-model="$store.state.quiz.subtitle" v-on:blur="updating_quiz_subtitle = false">

                    <div style="clear:both"></div>
                    <button class="button submit" v-on:click="save_questions" v-show="!saving">Sauvegarder</button>
                    <button class="button" v-on:click="save_questions" v-show="saving" :disabled="saving">Sauvegarde...</button>
                </div>
            </div>

            <div class="container">
                <div class="page-content">
                    <div id="questions" v-sortable="{onEnd: reorder_questions, handle: '.move-button'}">
                        <question
                                v-for="(question, index) in $store.state.quiz.questions"
                                v-bind:title="question.title"
                                v-bind:question_number="index"
                                v-bind:description="question.description"
                                v-bind:items="question.items"
                                v-bind:items_left="question.items_left"
                                v-bind:items_right="question.items_right"
                                v-bind:type="question.type"
                                v-bind:key="question.id"
                        ></question>
                    </div>

                    <div class="question block add-question">
                        <div class="block-content">
                            <div class="inline-field question-title">
                                <span class="label">Titre</span>
                                <textarea class="question-title" placeholder="Ajouter une question" v-model="new_question_title" v-on:keyup.enter="add_question"></textarea>
                            </div>

                            <div class="inline-field question-description">
                                <span class="label">Description</span>
                                <textarea class="question-description" placeholder="Ajouter une description" v-model="new_question_description" v-on:keyup.enter="add_question"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="is-clearfix">
                        <button class="button submit" v-on:click="save_questions" v-show="!saving">Sauvegarder</button>
                        <button class="button submit" v-on:click="save_questions" v-show="saving" :disabled="saving">Sauvegarde...</button>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="question-template">
        <div class="question block">
            <div class="block-header">
                <button class="move-button"></button>

                <div style="display:none">
                    <button class="button duplicate-button" v-on:click="$store.commit('delete_question', question_number)">S</button>
                    <button class="button duplicate-button" v-on:click="$store.commit('duplicate_question', question_number)">D</button>
                </div>

                <div class="inline-field question-title">
                    <span class="label">Titre</span>
                    <textarea @input="update_question_title($event, question_number)">@{{ title }}</textarea>
                </div>

                <div class="inline-field question-description">
                    <span class="label">Description</span>
                    <textarea @input="update_question_description($event, question_number)">@{{ description }}</textarea>
                </div>

                <button class="button toggle-button" v-on:click="is_opened = !is_opened" :class="{ up : is_opened, down : !is_opened }"></button>
            </div>
            <div class="block-content" v-show="is_opened">
                <div class="content">
                    <div class="type">
                        <label class="label">Type</label>
                        <div class="select">
                            <select v-bind:value="type" @input="update_question_type($event, question_number)">
                                <option value="1">Boutons radios</option>
                                <option value="2">Choix multiples</option>
                                <option value="3">Association d'items</option>
                                <option value="4">Réponse simple</option>
                            </select>
                        </div>
                    </div>

                    <div class="items" v-if="type == 1 || type == 2">
                        <item-text
                                v-for="(item, index) in items"
                                v-bind:title="item.title"
                                v-bind:item_number="index"
                                v-bind:correct="item.correct"
                                v-bind:question_number="question_number"
                                v-bind:key="item.id"
                        ></item-text>

                        <div class="field add-answer">
                            <label class="label">Ajouter une réponse</label>
                            <input type="text" placeholder="Ajouter une réponse" v-model="new_item_title" v-on:keyup.enter="add_item(question_number)" />
                        </div>
                    </div>

                    <div class="items" v-if="type == 3">
                        <div class="items-left">
                            <item-left
                                    v-for="(item, index) in items_left"
                                    v-bind:title="item.title"
                                    v-bind:item_number="index"
                                    v-bind:question_number="question_number"
                                    v-bind:key="item.id"
                            ></item-left>

                            <div class="field add-answer">
                                <label class="label">Ajouter un item</label>
                                <input type="text" placeholder="Ajouter un item" v-model="new_item_left_title" v-on:keyup.enter="add_item_left(question_number)" />
                            </div>
                        </div>
                        <div class="items-right">
                            <item-right
                                    v-for="(item, index) in items_right"
                                    v-bind:title="item.title"
                                    v-bind:associated_item="item.associated_item"
                                    v-bind:item_number="index"
                                    v-bind:question_number="question_number"
                                    v-bind:key="item.id"
                                    ></item-right>

                            <div class="field add-answer">
                                <label class="label">Ajouter un item</label>
                                <input type="text" placeholder="Ajouter un item" v-model="new_item_right_title" v-on:keyup.enter="add_item_right(question_number)" />
                                <input class="associated-item" type="text" v-model="new_item_right_associated_item" v-on:keyup.enter="add_item_right(question_number)" />
                            </div>
                        </div>
                    </div>

                    <div class="items" v-if="type == 4">

                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="item-text-template">
        <div class="field">
            <label class="label">Réponse @{{ item_number+1 }}
                <span :class="{correct: true, checked: correct}" v-on:click="check_item(item_number, question_number)"></span>
            </label>
            <input :class="{highlighted: correct}" type="text" placeholder="" :value="title" @input="update_item_title($event, item_number, question_number)" />
            <span class="delete" v-on:click="delete_item(item_number, question_number)">x</span>
        </div>
    </script>

    <script type="text/x-template" id="item-left-template">
        <div class="field">
            <label class="label">Nom d'item @{{ item_number+1 }}</label>
            <input type="text" placeholder="" :value="title" @input="update_item_left_title($event, item_number, question_number)" />
            <span class="delete" v-on:click="delete_item_left(item_number, question_number)">x</span>
        </div>
    </script>

    <script type="text/x-template" id="item-right-template">
        <div class="field">
            <label class="label">Nom d'item @{{ item_number+1 }}</label>
            <input type="text" placeholder="" :value="title" @input="update_item_right_title($event, item_number, question_number)" />
            <input class="associated-item" type="text" :value="associated_item" />
            <span class="delete" v-on:click="delete_item_right(item_number, question_number)"></span>
        </div>
    </script>

    <script src="{{ asset('js/dist/back.js') }}"></script>
@endsection