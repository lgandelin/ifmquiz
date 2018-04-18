import Vue from 'vue';
import Vuex from 'vuex';

const uuidv4 = require('uuid/v4');

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        questions: []
    },
    mutations: {
        add_question(state, question) {
            question.id = uuidv4();
            question.items = [];
            question.items_left = [];
            question.items_right = [];
            question.type = 1;
            state.questions.push(question);
        },
        update_question_title(state, params) {
            var number = params.question_number;
            var title = params.title;

            state.questions[number].title = title;
        },
        update_question_description(state, params) {
            var number = params.question_number;
            var description = params.description;

            state.questions[number].description = description;
        },
        update_question_type(state, params) {
            var number = params.question_number;
            var type = params.type;

            state.questions[number].type = type;
        },
        delete_question(state, number) {
            state.questions.splice(number, 1);
        },
        duplicate_question(state, number) {
            var question = Object.assign({}, state.questions[number]);
            question.id = uuidv4();
            question.title += " - copie";
            state.questions.splice(number+1, 0, question);
        },
        add_item(state, params) {
            var question_number = params.question_number;
            state.questions[question_number].items.push({
                id: uuidv4(),
                title: params.title,
                correct: params.correct
            })

            //For the radio buttons, if the first option is added, it must be selected
            if (state.questions[question_number].type == 1) {
                if (state.questions[question_number].items.length == 1) {
                    state.questions[question_number].items[0].correct = true
                }
            }
        },
        check_item(state, params) {
            var item_number = params.item_number;
            var question_number = params.question_number;

            //For the radio buttons, only one option can be selected at once
            if (state.questions[question_number].type == 1) {
                for (var i in state.questions[question_number].items) {
                    var item = state.questions[question_number].items[i];
                    item.correct = false;
                }
            }

            state.questions[question_number].items[item_number].correct = !state.questions[question_number].items[item_number].correct
        },
        update_item_title(state, params) {
            var item_number = params.item_number;
            var question_number = params.question_number;
            var title = params.title;

            state.questions[question_number].items[item_number].title = title;
        },
        delete_item(state, params) {
            var item_number = params.item_number;
            var question_number = params.question_number;

            state.questions[question_number].items.splice(item_number, 1);

            //For the radio buttons, at least one option must be selected
            if (state.questions[question_number].type == 1) {
                var one_item_selected = false;
                for (var i in state.questions[question_number].items) {
                    if (state.questions[question_number].items[i].correct) {
                        one_item_selected = true
                    }
                }

                if (!one_item_selected) {
                    state.questions[question_number].items[0].correct = true
                }
            }
        },
        add_item_left(state, params) {
            var question_number = params.question_number;

            if (state.questions[question_number].items_left == null) {
                state.questions[question_number].items_left = [];
            }

            state.questions[question_number].items_left.push({
                id: uuidv4(),
                title: params.title,
                correct: params.correct
            });
        },
        update_item_left_title(state, params) {
            var item_number = params.item_number;
            var question_number = params.question_number;
            var title = params.title;

            state.questions[question_number].items_left[item_number].title = title;
        },
        add_item_right(state, params) {
            var question_number = params.question_number;

            if (state.questions[question_number].items_right == null) {
                state.questions[question_number].items_right = [];
            }

            state.questions[question_number].items_right.push({
                id: uuidv4(),
                title: params.title,
                associated_item: parseInt(params.associated_item)
            });
        },
        update_item_right_title(state, params) {
            var item_number = params.item_number;
            var question_number = params.question_number;
            var title = params.title;

            state.questions[question_number].items_right[item_number].title = title;
        },
        delete_item_left(state, params) {
            var question_number = params.question_number;
            var item_number = params.item_number;

            state.questions[question_number].items_left.splice(item_number, 1);
        },
        delete_item_right(state, params) {
            var question_number = params.question_number;
            var item_number = params.item_number;

            state.questions[question_number].items_right.splice(item_number, 1);
        },
    }
});

export default store;