import axios from 'axios';
import Vue from 'vue';

Vue.component('quiz', {
    template: '#quiz-template',
    data: function() {
        return {
            saving: false,
            new_question_title: '',
            new_question_description: '',
        }
    },
    mounted: function() {
        var store = this.$store;
        var quiz_id = document.getElementById('quiz_id').value;

        axios.get("/questionnaires/" + quiz_id + "/questions")
            .then(function (response) {
                store.state.questions = response.data
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
        save_questions: function() {
            var quiz_id = document.getElementById('quiz_id').value;
            var quiz = this;
            quiz.saving = true;

            axios.post(
                "/questionnaires/" + quiz_id + "/questions", {
                    questions: this.$store.state.questions
                })
                .then(function (response) {
                    quiz.saving = false;
                });
        },
        reorder_questions: function(event) {
            const moved_question = this.$store.state.questions.splice(event.oldIndex, 1)[0];
            this.$store.state.questions.splice(event.newIndex, 0, moved_question);
        },
    },
});
