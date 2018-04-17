Vue.component('quiz', {
    template: '#quiz-template',
    data: function() {
        return {
            new_question_title: '',
            new_question_description: '',
            questions: [],
        }
    },
    mounted: function() {
        var c = this;

        axios.get("/questions")
            .then(function (response) {
                c.questions = response.data
            })
    },
    methods: {
        valid_add_question: function() {
            this.questions.push({
                title: this.new_question_title,
                description: this.new_question_description,
            });

            this.new_question_title = '';
            this.new_question_description = '';
        },
        delete_question: function(number) {
            this.questions.splice(number, 1);
        },
        duplicate_question: function(number) {
            var question = Object.assign({}, this.questions[number]);
            question.id = uuidv4();
            question.title += " - copie";
            this.questions.splice(number+1, 0, question);
        }
    }
});

Vue.component('question', {
    template: '#question-template',
    props: {
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
        number: 0,
    },
    data: function() {
        return {
            mutable_type: '',
            mutable_answers: [],
            new_answer_title: '',
        }
    },
    mounted: function() {
        this.mutable_type = this.type;
        this.mutable_answers = this.answers;
    },
    methods: {
        valid_add_answer: function() {
            this.mutable_answers.push({
                correct: false,
                title: this.new_answer_title,
            });

            this.new_answer_title = '';
        },
        delete_answer: function(number) {
            this.mutable_answers.splice(number, 1);
        },
        check_answer: function(number) {
            /*if (this.type == 1) {
                for (var key in this.mutable_answers) {
                    this.mutable_answers[key].correct = false;
                    this.mutable_answers[key].is_checked = false;
                    this.mutable_answers[key].title = "";
                }
            }*/
        },
        delete_question: function(number) {
            this.$emit('delete_question', number);
        },
        duplicate_question: function(number) {
            this.$emit('duplicate_question', number);
        }
    }
});

Vue.component('text-answer', {
    template: '#text-answer-template',
    props: {
        number: 0,
        title: {
            default: '',
            type: String
        },
        correct: {
            default: false,
            type: Boolean,
        },
    },
    data: function() {
        return {
            is_checked: false,
        }
    },
    methods: {
        check: function(number) {
            this.$emit('check_answer', number);
            this.is_checked = !this.is_checked;
        },
        delete_answer: function(number) {
            this.$emit('delete_answer', number);
        },
    },
    mounted: function() {
        this.is_checked = this.correct;
    },
});

new Vue({
    el: '#quiz'
});