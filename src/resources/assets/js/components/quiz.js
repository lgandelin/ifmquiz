import axios from 'axios';
import Vue from 'vue';
import Datepicker from 'vuejs-datepicker';
import {fr} from 'vuejs-datepicker/dist/locale';

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
            lang: fr
        }
    },
    components: {
        datepicker: Datepicker,
    },
    mounted: function() {
        var store = this.$store;
        var quiz_id = document.getElementById('quiz_id').value;

        axios.get("/admin/quiz/" + quiz_id)
            .then(function (response) {
                store.state.quiz = response.data
            });
    },
    methods: {
        add_question: function() {
            this.$store.commit('add_question', {
                title: this.new_question_title,
                description: this.new_question_description,
                factor: 1,
            });

            this.new_question_title = '';
            this.new_question_description = '';
        },
        save_questions: function() {
            var quiz_id = document.getElementById('quiz_id').value;
            var quiz = this;
            quiz.saving = true;

            axios.post(
            "/admin/quiz/" + quiz_id, {
                quiz: this.$store.state.quiz
            })
            .then(function (response) {
                quiz.saving = false;
            });

            this.$notify({
                group: 'quiz',
                title: 'Informations sauvegardées',
                text: 'Les informations du questionnaire ont été sauvegardées avec succès.',
                type: 'success',
            });
        },
        reorder_questions: function(event) {
            const moved_question = this.$store.state.quiz.questions.splice(event.oldIndex, 1)[0];
            this.$store.state.quiz.questions.splice(event.newIndex, 0, moved_question);
        },
    },
});
