Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        questions: []
    },
    mutations: {
        add_question(state, question) {
            question.items = [];
            question.items_left = [];
            question.items_right = [];
            question.type = 1;
            state.questions.push(question);
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

            state.questions[question_number].items_left.push({
                title: params.title,
                correct: params.correct
            });
        },
        add_item_right(state, params) {
            var question_number = params.question_number;

            state.questions[question_number].items_right.push({
                title: params.title,
                associated_item: parseInt(params.associated_item)
            });
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
        }
    }
});

Vue.component('quiz', {
    template: '#quiz-template',
    data: function() {
        return {
            new_question_title: '',
            new_question_description: '',
        }
    },
    mounted: function() {
        var store = this.$store;
        var quiz_id = document.getElementById('quiz_id').value;

        axios.get("/questions/" + quiz_id)
            .then(function (response) {
                store.state.questions = response.data
            })

        //Initialize drag and drop
        var el = document.getElementById('questions');
        var sortable = new Sortable(el, {
            handle: '.move-button',
        });
    },
    methods: {
        add_question: function() {
            this.$store.commit('add_question', {
                title: this.new_question_title,
                description: this.new_question_description,
            });

            this.new_question_title = '';
            this.new_question_description = '';
        },
    }
});

Vue.component('question', {
    template: '#question-template',
    props: {
        question_number: 0,
        title: {
            default: '',
            type: String
        },
        description: {
            default: '',
            type: String
        },
        type: {
            default: 1,
            type: Number
        },
        items: Array,
        items_left: Array,
        items_right: Array,
    },
    data: function() {
        return {
            is_opened: true,
            new_item_title: '',
            new_item_left_title : '',
            new_item_right_title : '',
            new_item_right_associated_item : 0,
        }
    },
    methods: {
        add_item: function(question_number) {
            this.$store.commit('add_item', {
                title: this.new_item_title,
                correct: false,
                question_number: question_number
            });

            this.new_item_title = '';
        },
        update_question_type: function(e, question_number) {
            this.$store.commit('update_question_type', {
                type: parseInt(e.target.value),
                question_number: question_number,
            });
        },
        add_item_left: function(question_number) {
            this.$store.commit('add_item_left', {
                title: this.new_item_left_title,
                question_number: question_number
            });

            this.new_item_left_title = '';
        },
        add_item_right: function(question_number) {
            this.$store.commit('add_item_right', {
                title: this.new_item_right_title,
                associated_item: this.new_item_right_associated_item,
                question_number: question_number,
            });

            this.new_item_right_title = '';
            this.new_item_right_associated_item = 0;
        },
    }
});

Vue.component('item-text', {
    template: '#item-text-template',
    props: {
        item_number: 0,
        question_number: 0,
        title: {
            default: '',
            type: String
        },
        correct: {
            default: false,
            type: Boolean,
        },
    },
    methods: {
        check_item: function(item_number, question_number) {
            this.$store.commit('check_item', {
                item_number: item_number,
                question_number: question_number
            });
        },
        delete_item: function(item_number, question_number) {
            this.$store.commit('delete_item', {
                item_number: item_number,
                question_number: question_number
            });
        },
    },
});

Vue.component('item-left', {
    template: '#item-left-template',
    props: {
        item_number: 0,
        question_number: 0,
        title: {
            default: '',
            type: String
        },
    },
    methods: {
        delete_item_left: function (item_number, question_number) {
            this.$store.commit('delete_item_left', {
                item_number: item_number,
                question_number: question_number
            });
        },
    }
});

Vue.component('item-right', {
    template: '#item-right-template',
    props: {
        item_number: 0,
        question_number: 0,
        title: {
            default: '',
            type: String
        },
        associated_item: {
            default: 0,
            type: Number
        }
    },
    methods: {
        delete_item_right: function(item_number, question_number) {
            this.$store.commit('delete_item_right', {
                item_number: item_number,
                question_number: question_number
            });
        }
    }
});

new Vue({
    el: '#quiz',
    store,
});