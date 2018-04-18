@extends('ifmquiz::master')

@section('main-content')
    <div class="container" id="quiz">
        <h1 class="title">{{ $quiz->title }}</h1>
        <quiz></quiz>
        <input type="hidden" id="quiz_id" value="{{ $quiz->id }}" />
    </div>

    <script type="text/x-template" id="quiz-template">
        <div class="quiz">
            <div id="questions">
                <question
                        v-for="(question, index) in $store.state.questions"
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

            <div class="question add-question">
                <textarea class="question-title textarea" placeholder="Ajouter une question" v-model="new_question_title"></textarea>
                <textarea class="question-description textarea" placeholder="Ajouter une description" v-model="new_question_description"></textarea>
                <button class="add-button button" v-on:click="add_question">OK</button>
            </div>
            </div>
    </script>

    <script type="text/x-template" id="question-template">
        <div class="question">
            <button class="button move-button">M</button>

            <button class="button duplicate-button" v-on:click="$store.commit('delete_question', question_number)">S</button>
            <button class="button duplicate-button" v-on:click="$store.commit('duplicate_question', question_number)">D</button>

            <textarea class="question-title textarea">@{{ title }}</textarea>
            <textarea class="question-description textarea">@{{ description }}</textarea>

            <button class="button toggle-button" v-on:click="is_opened = !is_opened">O</button>
            <div class="content" v-show="is_opened">
                <div class="type">
                    <label class="label">Type:</label>
                    <select class="select" v-bind:value="type" v-on:change="update_question_type($event, question_number)">
                        <option value="1">Boutons radios</option>
                        <option value="2">Choix multiples</option>
                        <option value="3">Association d'items</option>
                        <option value="4">Réponse simple</option>
                    </select>
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

                    <div class="field">
                        <label class="label">Ajouter une réponse</label>
                        <input type="text" class="input" placeholder="Ajouter une réponse" v-model="new_item_title" />
                        <button class="button" v-on:click="add_item(question_number)">OK</button>
                    </div>
                </div>

                <div class="items" v-if="type == 3">
                    <div class="div-50">
                        <item-left
                                v-for="(item, index) in items_left"
                                v-bind:title="item.title"
                                v-bind:item_number="index"
                                v-bind:question_number="question_number"
                                v-bind:key="item.id"
                        ></item-left>

                        <div class="field">
                            <label class="label">Ajouter un item</label>
                            <input type="text" class="input" placeholder="Ajouter un item" v-model="new_item_left_title" />
                            <button class="button" v-on:click="add_item_left(question_number)">OK</button>
                        </div>
                    </div>
                    <div class="div-50">
                        <item-right
                                v-for="(item, index) in items_right"
                                v-bind:title="item.title"
                                v-bind:associated_item="item.associated_item"
                                v-bind:item_number="index"
                                v-bind:question_number="question_number"
                                v-bind:key="item.id"
                                ></item-right>

                        <div class="field">
                            <label class="label">Ajouter un item</label>
                            <input type="text" class="input" placeholder="Ajouter un item" v-model="new_item_right_title"  />
                            <input class="input" type="number" v-model="new_item_right_associated_item" />
                            <button class="button" v-on:click="add_item_right(question_number)">OK</button>
                        </div>
                    </div>
                </div>

                <div class="items" v-if="type == 4">

                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="item-text-template">
        <div class="field">
            <label class="label">Réponse @{{ item_number+1 }}<span :class="{correct: true, checked: correct}" v-on:click="check_item(item_number, question_number)">V</span></label>
            <input type="text" class="input" placeholder="" :value="title" />
            <span class="delete" v-on:click="delete_item(item_number, question_number)">x</span>
        </div>
    </script>

    <script type="text/x-template" id="item-left-template">
        <div class="field">
            <label class="label">Nom d'item @{{ item_number+1 }}</label>
            <input type="text" class="input" placeholder="" :value="title" />
            <span class="delete" v-on:click="delete_item_left(item_number, question_number)">x</span>
        </div>
    </script>

    <script type="text/x-template" id="item-right-template">
        <div class="field">
            <label class="label">Nom d'item @{{ item_number+1 }}</label>
            <input type="text" class="input" placeholder="" :value="title" />
            <input class="input" type="number" :value="associated_item" />
            <span class="delete" v-on:click="delete_item_right(item_number, question_number)">x</span>
        </div>
    </script>
@endsection