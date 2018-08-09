import axios from 'axios';
import Vue from 'vue';
import Datepicker from 'vuejs-datepicker';
import {fr} from 'vuejs-datepicker/dist/locale';

Vue.component('quiz', {
    template: '#quiz-template',
    data: function() {
        return {
            footer_image: '',
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
        datepicker: Datepicker
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

            //Putting footer text in store manually because CKeditor doesn't work properly with VueJS
            this.$store.state.quiz.footer_text = document.getElementById('footer_text').value;
            this.$store.state.quiz.header_logo = document.getElementById('header_logo').src;
            this.$store.state.quiz.footer_image = document.getElementById('footer_image').src;

            console.log(this.$store.state.quiz);

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
        upload_header_logo: function(event) {
            var quiz_id = document.getElementById('quiz_id').value;
            this.header_logo = event.target.files[0];

            const form_data = new FormData();
            form_data.append('header_logo', this.header_logo, 'header_logo');

            axios.post(
                "/admin/quiz/" + quiz_id + "/upload_image/header_logo",
                form_data
            ).then(function (response) {
                document.getElementById('header_logo').src = response.data.image;
                document.getElementById('header_logo_upload_wrapper').style.display = 'none';
                document.getElementById('header_logo_delete').style.display = 'block';
            });
        },
        delete_header_logo: function(event) {
            var quiz_id = document.getElementById('quiz_id').value;

            axios.post("/admin/quiz/" + quiz_id + "/delete_image/header_logo")
            .then(function (response) {
                document.getElementById('header_logo').src = "";
                document.getElementById('header_logo_upload_wrapper').style.display = 'block';
                document.getElementById('header_logo_delete').style.display = 'none';
            });
        },
        upload_footer_image: function(event) {
            var quiz_id = document.getElementById('quiz_id').value;
            this.footer_image = event.target.files[0];

            const form_data = new FormData();
            form_data.append('footer_image', this.footer_image, 'footer_image');

            axios.post(
                "/admin/quiz/" + quiz_id + "/upload_image/footer_image",
                form_data
            ).then(function (response) {
                document.getElementById('footer_image').src = response.data.image;
                document.getElementById('footer_image_upload_wrapper').style.display = 'none';
                document.getElementById('footer_image_delete').style.display = 'block';
            });
        },
        delete_footer_image: function(event) {
            var quiz_id = document.getElementById('quiz_id').value;

            axios.post("/admin/quiz/" + quiz_id + "/delete_image/footer_image")
            .then(function (response) {
                document.getElementById('footer_image').src = "";
                document.getElementById('footer_image_upload_wrapper').style.display = 'block';
                document.getElementById('footer_image_delete').style.display = 'none';
            });
        }
    },
});
