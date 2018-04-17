Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        questions: []
    },
    mutations: {
        add_question(state, question) {
            question.answers = [];
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

        add_answer(state, params) {
            var question_number = params.question_number;

            state.questions[question_number].answers.push({
                title: params.title,
                correct: params.correct
            })
        },
        delete_answer(state, params) {
            var answer_number = params.answer_number;
            var question_number = params.question_number;

            state.questions[question_number].answers.splice(answer_number, 1);

            //For the radio buttons, at least one option must be selected
            if (state.questions[question_number].type == 1) {
                var one_answer_selected = false;
                for (var i in state.questions[question_number].answers) {
                    if (state.questions[question_number].answers[i].correct) {
                        one_answer_selected = true
                    }
                }

                if (!one_answer_selected) {
                    state.questions[question_number].answers[0].correct = true
                }
            }
        },
        check_answer(state, params) {
            var answer_number = params.answer_number;
            var question_number = params.question_number;

            //For the radio buttons, only one option can be selected at once
            if (state.questions[question_number].type == 1) {
                for (var i in state.questions[question_number].answers) {
                    var answer = state.questions[question_number].answers[i];
                    answer.correct = false;
                }
            }

            state.questions[question_number].answers[answer_number].correct = !state.questions[question_number].answers[answer_number].correct
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
        axios.get("/questions")
            .then(function (response) {
                store.state.questions = response.data
            })

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
        answers: Array,
    },
    data: function() {
        return {
            is_opened: true,
            new_answer_title: '',
        }
    },
    methods: {
        add_answer: function(question_number) {
            this.$store.commit('add_answer', {
                title: this.new_answer_title,
                correct: false,
                question_number: question_number
            });

            this.new_answer_title = '';
        },
        update_question_type: function(e, question_number) {
            this.$store.commit('update_question_type', {
                type: parseInt(e.target.value),
                question_number: question_number,
            });
        }
    }
});

Vue.component('text-answer', {
    template: '#text-answer-template',
    props: {
        answer_number: 0,
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
        check_answer: function(answer_number, question_number) {
            this.$store.commit('check_answer', {
                answer_number: answer_number,
                question_number: question_number
            });
        },
        delete_answer: function(answer_number, question_number) {
            this.$store.commit('delete_answer', {
                answer_number: answer_number,
                question_number: question_number
            });
        },
    },
});

new Vue({
    el: '#quiz',
    store,
});