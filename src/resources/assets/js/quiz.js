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
            /*if (this.type == 1) {    NOT WORKING FOR SOME REASON...
                for (var i = 0; i < this.mutable_answers.length; i++) {
                    if (i != number) {
                        this.mutable_answers[i].correct = false;
                        this.mutable_answers[i].mutable_correct = false;
                    }
                }
            }*/
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
            mutable_correct: false,
        }
    },
    methods: {
        check: function(number) {
            this.mutable_correct = !this.mutable_correct;
            this.$emit('check_answer', number);
        },
        delete_answer: function(number) {
            this.$emit('delete_answer', number);
        }
    },
    mounted: function() {
        this.mutable_correct = this.correct;
    },
});

new Vue({
    el: '#quiz'
});