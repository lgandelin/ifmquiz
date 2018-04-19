import axios from 'axios';
import Vue from 'vue';

Vue.component('quiz', {
    template: '#quiz-template',
    data: function() {
        return {
            saving: false,
            new_question_title: '',
            new_question_description: '',
            updating_quiz_title: false,
            updating_quiz_subtitle: false,
            updating_quiz_time: false,
        }
    },
    mounted: function() {
        var store = this.$store;
        var quiz_id = document.getElementById('quiz_id').value;

        axios.get("/quiz/" + quiz_id)
            .then(function (response) {
                store.state.quiz = response.data
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
                "/quiz/" + quiz_id, {
                    quiz: this.$store.state.quiz
                })
                .then(function (response) {
                    quiz.saving = false;
                });
        },
        reorder_questions: function(event) {
            const moved_question = this.$store.state.quiz.questions.splice(event.oldIndex, 1)[0];
            this.$store.state.quiz.questions.splice(event.newIndex, 0, moved_question);
        },
    },
});
